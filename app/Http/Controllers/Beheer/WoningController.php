<?php

namespace App\Http\Controllers\Beheer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Beheer\WoningRequest;
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

class WoningController extends Controller
{
    /**
     * Kolommen van de woning die in het formulier staan.
     */
    private const KOLOMMEN = [
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
     * Teksten van de woning die per taal worden opgeslagen.
     */
    private const TEKSTEN = ['short_description', 'description', 'house_rules'];

    public function edit(): View
    {
        $woning = $this->woning();

        return view('beheer.woning', [
            'woning' => $woning,
            'talen' => Language::active()->get(),
            'voorzieningen' => Facility::with('translations')->orderBy('sort_order')->get(),
            'aanwezig' => $woning->facilities->modelKeys(),
            'iconen' => config('beheer.voorziening_iconen'),
        ]);
    }

    public function update(WoningRequest $request): RedirectResponse
    {
        $woning = $this->woning();
        $talen = Language::active()->get();
        $data = $request->validated();

        $gewijzigd = DB::transaction(function () use ($woning, $talen, $data) {
            $gewijzigd = $this->bewaarWoning($woning, $data);
            $gewijzigd = $this->bewaarTeksten($woning, $data['vertalingen'] ?? [], $talen) || $gewijzigd;
            $gewijzigd = $this->bewaarVoorzieningen($woning, $data, $talen) || $gewijzigd;

            if ($gewijzigd) {
                $woning->touch();
            }

            return $gewijzigd;
        });

        return redirect()->route('beheer.woning')
            ->with('status', $gewijzigd ? 'De woninggegevens zijn opgeslagen en staan direct op de website.' : 'Er was niets gewijzigd.');
    }

    /**
     * De woning die op de website staat, of een nieuwe als die er nog niet is.
     */
    private function woning(): Accommodation
    {
        return Accommodation::current() ?? new Accommodation(['is_active' => true]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function bewaarWoning(Accommodation $woning, array $data): bool
    {
        $woning->fill(Arr::only($data, self::KOLOMMEN));
        $woning->slug ??= $this->uniek(Accommodation::query(), 'slug', Str::slug($woning->name) ?: 'woning', '-');

        return $this->bewaar($woning);
    }

    /**
     * @param  array<string, array<string, ?string>>  $teksten
     * @param  Collection<int, Language>  $talen
     */
    private function bewaarTeksten(Accommodation $woning, array $teksten, Collection $talen): bool
    {
        $gewijzigd = false;

        foreach ($talen as $taal) {
            $vertaling = $woning->translations->firstWhere('language_id', $taal->id)
                ?? $woning->translations()->make(['language_id' => $taal->id]);

            foreach (self::TEKSTEN as $veld) {
                $vertaling->{$veld} = $teksten[$veld][$taal->code] ?? null;
            }

            $leeg = ! $taal->is_default && blank(array_filter($vertaling->only(self::TEKSTEN), 'filled'));

            $gewijzigd = $this->bewaar($vertaling, verwijderen: $leeg) || $gewijzigd;
        }

        return $gewijzigd;
    }

    /**
     * Zet de aangevinkte voorzieningen bij de woning, werk namen en iconen bij
     * en voeg eventueel een nieuwe voorziening toe.
     *
     * @param  array<string, mixed>  $data
     * @param  Collection<int, Language>  $talen
     */
    private function bewaarVoorzieningen(Accommodation $woning, array $data, Collection $talen): bool
    {
        $invoer = $data['voorzieningen'] ?? [];
        $aanwezig = $woning->facilities->modelKeys();
        $gewijzigd = false;

        foreach (Facility::with('translations')->whereKey(array_keys($invoer))->get() as $voorziening) {
            $velden = $invoer[$voorziening->id];

            $aanwezig = $velden['aanwezig']
                ? array_unique([...$aanwezig, $voorziening->id])
                : array_diff($aanwezig, [$voorziening->id]);

            $voorziening->icon = $velden['icon'] ?? null;

            $gewijzigd = $this->bewaar($voorziening) || $gewijzigd;
            $gewijzigd = $this->bewaarNamen($voorziening, $velden['naam'] ?? [], $talen) || $gewijzigd;
        }

        $nieuw = $data['nieuwe_voorziening'] ?? [];
        $naam = $nieuw['naam'][$talen->firstWhere('is_default', true)?->code] ?? null;

        if ($naam !== null) {
            $voorziening = Facility::create([
                'code' => $this->uniek(Facility::query(), 'code', Str::limit(Str::slug($naam, '_'), 40, '') ?: 'voorziening', '_'),
                'icon' => $nieuw['icon'] ?? null,
                'sort_order' => Facility::max('sort_order') + 1,
            ]);

            $this->bewaarNamen($voorziening, $nieuw['naam'], $talen);

            $aanwezig[] = $voorziening->id;
            $gewijzigd = true;
        }

        $koppelingen = $woning->facilities()->sync($aanwezig);

        return $gewijzigd || $koppelingen['attached'] || $koppelingen['detached'];
    }

    /**
     * @param  array<string, ?string>  $namen
     * @param  Collection<int, Language>  $talen
     */
    private function bewaarNamen(Facility $voorziening, array $namen, Collection $talen): bool
    {
        $gewijzigd = false;

        foreach ($talen as $taal) {
            $vertaling = $voorziening->translations->firstWhere('language_id', $taal->id)
                ?? $voorziening->translations()->make(['language_id' => $taal->id]);

            $naam = $namen[$taal->code] ?? null;

            if ($naam !== null) {
                $vertaling->name = $naam;
            }

            $gewijzigd = $this->bewaar($vertaling, verwijderen: $naam === null) || $gewijzigd;
        }

        return $gewijzigd;
    }

    /**
     * Sla een model op, of verwijder het. Geeft terug of er iets in de
     * database is veranderd.
     */
    private function bewaar(Model $model, bool $verwijderen = false): bool
    {
        if ($verwijderen) {
            return $model->exists && $model->delete();
        }

        return $model->isDirty() && $model->save();
    }

    /**
     * Een waarde die nog niet in de kolom voorkomt, zoals the-caribbean-palm-tree-2.
     */
    private function uniek(Builder $query, string $kolom, string $basis, string $scheiding): string
    {
        $waarde = $basis;

        for ($nummer = 2; (clone $query)->where($kolom, $waarde)->exists(); $nummer++) {
            $waarde = $basis.$scheiding.$nummer;
        }

        return $waarde;
    }
}
