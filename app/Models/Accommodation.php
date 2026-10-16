<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'surface_area_m2',
        'pets_allowed',
        'street',
        'house_number',
        'postal_code',
        'city',
        'country_code',
        'latitude',
        'longitude',
        'check_in_time',
        'check_out_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_guests' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'surface_area_m2' => 'integer',
            'pets_allowed' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(AccommodationTranslation::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Facility::class,
            'accommodation_facilities',
            'accommodation_id',
            'facility_id'
        )->orderBy('facilities.sort_order');
    }

    /**
     * De woning die op de website staat, met de vertalingen en voorzieningen.
     */
    public static function current(): ?self
    {
        return static::with(['translations.language', 'facilities.translations.language'])
            ->where('is_active', true)
            ->orderBy('id')
            ->first();
    }

    /**
     * Eén tekst van de woning in de gevraagde taal, met de standaardtaal als
     * terugval. Zonder taal krijg je de tekst in de standaardtaal.
     */
    public function text(string $field, ?string $code = null): ?string
    {
        $inTaal = fn (callable $taal) => $this->translations
            ->first(fn (AccommodationTranslation $translation) => $taal($translation->language))
            ?->{$field};

        return ($code === null ? null : $inTaal(fn (Language $language) => $language->code === $code))
            ?? $inTaal(fn (Language $language) => $language->is_default);
    }

    /**
     * De beschrijving, opgedeeld in alinea's op de lege regels.
     *
     * @return list<string>
     */
    public function paragraphs(?string $code = null): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\R\s*\R/', $this->text('description', $code) ?? '')),
            'filled',
        ));
    }

    /**
     * De huisregels, één regel per regel tekst.
     *
     * @return list<string>
     */
    public function houseRules(?string $code = null): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\R/', $this->text('house_rules', $code) ?? '')),
            'filled',
        ));
    }

    protected function checkInTime(): Attribute
    {
        return $this->time();
    }

    protected function checkOutTime(): Attribute
    {
        return $this->time();
    }

    /**
     * Tijden worden als 15:00:00 opgeslagen en als 15:00 teruggegeven, zodat
     * ze direct in een tijdveld passen.
     */
    private function time(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : substr($value, 0, 5),
            set: fn (?string $value) => $value === null ? null : substr($value, 0, 5).':00',
        );
    }
}
