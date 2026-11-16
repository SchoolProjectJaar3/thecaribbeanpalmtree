<?php

namespace App\Http\Requests\Admin;

use App\Models\Facility;
use App\Models\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class AccommodationRequest extends FormRequest
{
    /**
     * The numeric fields with their lower and upper bound.
     *
     * @var array<string, array{int, int}>
     */
    public const NUMBERS = [
        'max_guests' => [1, 50],
        'bedrooms' => [0, 50],
        'bathrooms' => [0, 50],
        'surface_area_m2' => [1, 100000],
    ];

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('country_code'))) {
            $this->merge(['country_code' => mb_strtoupper(trim($this->input('country_code')))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $languages = Language::active()->get();

        return [
            'name' => ['required', 'string', 'max:150'],
            ...collect(self::NUMBERS)->map(fn (array $bounds, string $field) => [
                $field === 'surface_area_m2' ? 'nullable' : 'required',
                'integer',
                "min:{$bounds[0]}",
                "max:{$bounds[1]}",
            ])->all(),
            'pets_allowed' => ['required', 'boolean'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'street' => ['nullable', 'string', 'max:150'],
            'house_number' => ['nullable', 'string', 'max:10'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'country_code' => ['nullable', 'regex:/^[A-Z]{2}$/'],
            ...$this->translations($languages),
            ...$this->facilities($languages),
            ...$this->newFacility($languages),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Dit veld mag niet leeg zijn.',
            'required_with' => 'Vul ook hier de naam in.',
            'string' => 'Vul hier een tekst in.',
            'integer' => 'Vul een heel getal in.',
            'min' => 'Dit getal mag niet lager zijn dan :min.',
            'max' => 'Deze tekst is te lang (maximaal :max tekens).',
            ...collect(self::NUMBERS)->mapWithKeys(fn (array $bounds, string $field) => [
                "{$field}.max" => 'Dit getal mag niet hoger zijn dan :max.',
            ])->all(),
            'boolean' => 'Maak een keuze.',
            'date_format' => 'Vul een tijd in, bijvoorbeeld 15:00.',
            'country_code.regex' => 'Vul een landcode van twee letters in, bijvoorbeeld CW.',
            'in' => 'Kies een van de opties.',
            'unique' => 'Deze voorziening bestaat al.',
        ];
    }

    /**
     * The description is required in the default language; in the other
     * languages an empty field falls back to the default language.
     *
     * @param  Collection<int, Language>  $languages
     * @return array<string, list<string>>
     */
    private function translations(Collection $languages): array
    {
        return $languages->flatMap(fn (Language $language) => [
            "translations.short_description.{$language->code}" => [$language->is_default ? 'required' : 'nullable', 'string', 'max:255'],
            "translations.description.{$language->code}" => [$language->is_default ? 'required' : 'nullable', 'string', 'max:5000'],
            "translations.house_rules.{$language->code}" => ['nullable', 'string', 'max:5000'],
        ])->all();
    }

    /**
     * Rules for the facilities that were part of the form.
     *
     * @param  Collection<int, Language>  $languages
     * @return array<string, list<mixed>>
     */
    private function facilities(Collection $languages): array
    {
        $ids = Facility::whereKey(array_keys((array) $this->input('facilities')))->pluck('id');

        return $ids->flatMap(fn (int $id) => [
            "facilities.{$id}.selected" => ['required', 'boolean'],
            "facilities.{$id}.icon" => ['nullable', Rule::in(array_keys(config('admin.facility_icons')))],
            ...$languages->mapWithKeys(fn (Language $language) => [
                "facilities.{$id}.name.{$language->code}" => [$language->is_default ? 'required' : 'nullable', 'string', 'max:100'],
            ]),
        ])->all();
    }

    /**
     * A new facility is optional, but needs a name in the default language
     * as soon as anything has been filled in.
     *
     * @param  Collection<int, Language>  $languages
     * @return array<string, list<mixed>>
     */
    private function newFacility(Collection $languages): array
    {
        $others = $languages->reject->is_default
            ->map(fn (Language $language) => "new_facility.name.{$language->code}")
            ->implode(',');

        return [
            'new_facility.icon' => ['nullable', Rule::in(array_keys(config('admin.facility_icons')))],
            ...$languages->mapWithKeys(fn (Language $language) => [
                "new_facility.name.{$language->code}" => [
                    'nullable',
                    ...($language->is_default && $others ? ["required_with:{$others}"] : []),
                    'string',
                    'max:100',
                    Rule::unique('facility_translations', 'name')->where('language_id', $language->id),
                ],
            ])->all(),
        ];
    }
}
