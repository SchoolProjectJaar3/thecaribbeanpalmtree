<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccommodationRequest;
use App\Models\Accommodation;
use App\Models\Facility;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccommodationController extends Controller
{
    /**
     * Columns of the accommodation that are part of the form.
     */
    private const COLUMNS = [
        'name',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'surface_area_m2',
        'pets_allowed',
        'check_in_time',
        'check_out_time',
        'street',
        'house_number',
        'postal_code',
        'city',
        'country_code',
    ];

    /**
     * Texts of the accommodation that are stored per language.
     */
    private const TEXTS = ['short_description', 'description', 'house_rules'];

    public function edit(): View
    {
        $accommodation = $this->accommodation();

        return view('admin.accommodation', [
            'accommodation' => $accommodation,
            'languages' => Language::active()->get(),
            'facilities' => Facility::with('translations')->orderBy('sort_order')->get(),
            'selected' => $accommodation->facilities->modelKeys(),
            'icons' => config('admin.facility_icons'),
        ]);
    }

    public function update(AccommodationRequest $request): RedirectResponse
    {
        $accommodation = $this->accommodation();
        $languages = Language::active()->get();
        $data = $request->validated();

        $changed = DB::transaction(function () use ($accommodation, $languages, $data) {
            $changed = $this->saveAccommodation($accommodation, $data);
            $changed = $this->saveTexts($accommodation, $data['translations'] ?? [], $languages) || $changed;
            $changed = $this->saveFacilities($accommodation, $data, $languages) || $changed;

            if ($changed) {
                $accommodation->touch();
            }

            return $changed;
        });

        return redirect()->route('admin.accommodation')
            ->with('status', $changed ? 'De woninggegevens zijn opgeslagen en staan direct op de website.' : 'Er was niets gewijzigd.');
    }

    /**
     * The accommodation shown on the website, or a new one if none exists yet.
     */
    private function accommodation(): Accommodation
    {
        return Accommodation::current() ?? new Accommodation(['is_active' => true]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function saveAccommodation(Accommodation $accommodation, array $data): bool
    {
        $accommodation->fill(Arr::only($data, self::COLUMNS));
        $accommodation->slug ??= $this->unique(Accommodation::query(), 'slug', Str::slug($accommodation->name) ?: 'accommodation', '-');

        return $this->save($accommodation);
    }

    /**
     * @param  array<string, array<string, ?string>>  $texts
     * @param  Collection<int, Language>  $languages
     */
    private function saveTexts(Accommodation $accommodation, array $texts, Collection $languages): bool
    {
        $changed = false;

        foreach ($languages as $language) {
            $translation = $accommodation->translations->firstWhere('language_id', $language->id)
                ?? $accommodation->translations()->make(['language_id' => $language->id]);

            foreach (self::TEXTS as $field) {
                $translation->{$field} = $texts[$field][$language->code] ?? null;
            }

            $empty = ! $language->is_default && blank(array_filter($translation->only(self::TEXTS), 'filled'));

            $changed = $this->save($translation, delete: $empty) || $changed;
        }

        return $changed;
    }

    /**
     * Attach the checked facilities to the accommodation, update names and
     * icons and add a new facility if one was filled in.
     *
     * @param  array<string, mixed>  $data
     * @param  Collection<int, Language>  $languages
     */
    private function saveFacilities(Accommodation $accommodation, array $data, Collection $languages): bool
    {
        $input = $data['facilities'] ?? [];
        $selected = $accommodation->facilities->modelKeys();
        $changed = false;

        foreach (Facility::with('translations')->whereKey(array_keys($input))->get() as $facility) {
            $fields = $input[$facility->id];

            $selected = $fields['selected']
                ? array_unique([...$selected, $facility->id])
                : array_diff($selected, [$facility->id]);

            $facility->icon = $fields['icon'] ?? null;

            $changed = $this->save($facility) || $changed;
            $changed = $this->saveNames($facility, $fields['name'] ?? [], $languages) || $changed;
        }

        $new = $data['new_facility'] ?? [];
        $name = $new['name'][$languages->firstWhere('is_default', true)?->code] ?? null;

        if ($name !== null) {
            $facility = Facility::create([
                'code' => $this->unique(Facility::query(), 'code', Str::limit(Str::slug($name, '_'), 40, '') ?: 'facility', '_'),
                'icon' => $new['icon'] ?? null,
                'sort_order' => Facility::max('sort_order') + 1,
            ]);

            $this->saveNames($facility, $new['name'], $languages);

            $selected[] = $facility->id;
            $changed = true;
        }

        $synced = $accommodation->facilities()->sync($selected);

        return $changed || $synced['attached'] || $synced['detached'];
    }

    /**
     * @param  array<string, ?string>  $names
     * @param  Collection<int, Language>  $languages
     */
    private function saveNames(Facility $facility, array $names, Collection $languages): bool
    {
        $changed = false;

        foreach ($languages as $language) {
            $translation = $facility->translations->firstWhere('language_id', $language->id)
                ?? $facility->translations()->make(['language_id' => $language->id]);

            $name = $names[$language->code] ?? null;

            if ($name !== null) {
                $translation->name = $name;
            }

            $changed = $this->save($translation, delete: $name === null) || $changed;
        }

        return $changed;
    }

    /**
     * Save a model, or delete it. Returns whether anything changed in the
     * database.
     */
    private function save(Model $model, bool $delete = false): bool
    {
        if ($delete) {
            return $model->exists && $model->delete();
        }

        return $model->isDirty() && $model->save();
    }

    /**
     * A value that does not yet exist in the column, such as the-caribbean-palm-tree-2.
     */
    private function unique(Builder $query, string $column, string $base, string $separator): string
    {
        $value = $base;

        for ($number = 2; (clone $query)->where($column, $value)->exists(); $number++) {
            $value = $base.$separator.$number;
        }

        return $value;
    }
}
