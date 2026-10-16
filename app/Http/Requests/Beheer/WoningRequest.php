<?php

namespace App\Http\Requests\Beheer;

use App\Models\Facility;
use App\Models\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class WoningRequest extends FormRequest
{
    /**
     * De aantallen met hun ondergrens en bovengrens.
     *
     * @var array<string, array{int, int}>
     */
    public const AANTALLEN = [
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
        $talen = Language::active()->get();

        return [
            'name' => ['required', 'string', 'max:150'],
            ...collect(self::AANTALLEN)->map(fn (array $grenzen, string $veld) => [
                $veld === 'surface_area_m2' ? 'nullable' : 'required',
                'integer',
                "min:{$grenzen[0]}",
                "max:{$grenzen[1]}",
            ])->all(),
            'pets_allowed' => ['required', 'boolean'],
            'check_in_time' => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'street' => ['nullable', 'string', 'max:150'],
            'house_number' => ['nullable', 'string', 'max:10'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'country_code' => ['nullable', 'regex:/^[A-Z]{2}$/'],
            ...$this->vertalingen($talen),
            ...$this->voorzieningen($talen),
            ...$this->nieuweVoorziening($talen),
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
            ...collect(self::AANTALLEN)->mapWithKeys(fn (array $grenzen, string $veld) => [
                "{$veld}.max" => 'Dit getal mag niet hoger zijn dan :max.',
            ])->all(),
            'boolean' => 'Maak een keuze.',
            'date_format' => 'Vul een tijd in, bijvoorbeeld 15:00.',
            'country_code.regex' => 'Vul een landcode van twee letters in, bijvoorbeeld CW.',
            'in' => 'Kies een van de opties.',
            'unique' => 'Deze voorziening bestaat al.',
        ];
    }

    /**
     * De beschrijving is verplicht in de standaardtaal; in de andere talen
     * valt een leeg veld terug op de standaardtaal.
     *
     * @param  Collection<int, Language>  $talen
     * @return array<string, list<string>>
     */
    private function vertalingen(Collection $talen): array
    {
        return $talen->flatMap(fn (Language $taal) => [
            "vertalingen.short_description.{$taal->code}" => [$taal->is_default ? 'required' : 'nullable', 'string', 'max:255'],
            "vertalingen.description.{$taal->code}" => [$taal->is_default ? 'required' : 'nullable', 'string', 'max:5000'],
            "vertalingen.house_rules.{$taal->code}" => ['nullable', 'string', 'max:5000'],
        ])->all();
    }

    /**
     * Regels voor de voorzieningen die in het formulier stonden.
     *
     * @param  Collection<int, Language>  $talen
     * @return array<string, list<mixed>>
     */
    private function voorzieningen(Collection $talen): array
    {
        $ids = Facility::whereKey(array_keys((array) $this->input('voorzieningen')))->pluck('id');

        return $ids->flatMap(fn (int $id) => [
            "voorzieningen.{$id}.aanwezig" => ['required', 'boolean'],
            "voorzieningen.{$id}.icon" => ['nullable', Rule::in(array_keys(config('beheer.voorziening_iconen')))],
            ...$talen->mapWithKeys(fn (Language $taal) => [
                "voorzieningen.{$id}.naam.{$taal->code}" => [$taal->is_default ? 'required' : 'nullable', 'string', 'max:100'],
            ]),
        ])->all();
    }

    /**
     * Een nieuwe voorziening is optioneel, maar heeft een naam in de
     * standaardtaal nodig zodra er iets is ingevuld.
     *
     * @param  Collection<int, Language>  $talen
     * @return array<string, list<mixed>>
     */
    private function nieuweVoorziening(Collection $talen): array
    {
        $anderen = $talen->reject->is_default
            ->map(fn (Language $taal) => "nieuwe_voorziening.naam.{$taal->code}")
            ->implode(',');

        return [
            'nieuwe_voorziening.icon' => ['nullable', Rule::in(array_keys(config('beheer.voorziening_iconen')))],
            ...$talen->mapWithKeys(fn (Language $taal) => [
                "nieuwe_voorziening.naam.{$taal->code}" => [
                    'nullable',
                    ...($taal->is_default && $anderen ? ["required_with:{$anderen}"] : []),
                    'string',
                    'max:100',
                    Rule::unique('facility_translations', 'name')->where('language_id', $taal->id),
                ],
            ])->all(),
        ];
    }
}
