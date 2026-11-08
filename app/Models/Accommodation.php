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
     * The accommodation shown on the website, with its translations and facilities.
     */
    public static function current(): ?self
    {
        return static::with(['translations.language', 'facilities.translations.language'])
            ->where('is_active', true)
            ->orderBy('id')
            ->first();
    }

    /**
     * A text of the accommodation in the requested language, falling back to
     * the default language. Without a language you get the default language.
     */
    public function text(string $field, ?string $code = null): ?string
    {
        $inLanguage = fn (callable $matches) => $this->translations
            ->first(fn (AccommodationTranslation $translation) => $matches($translation->language))
            ?->{$field};

        return ($code === null ? null : $inLanguage(fn (Language $language) => $language->code === $code))
            ?? $inLanguage(fn (Language $language) => $language->is_default);
    }

    /**
     * The description, split into paragraphs on the blank lines.
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
     * The house rules, one rule per line of text.
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
     * Times are stored as 15:00:00 and returned as 15:00, so they fit
     * directly into a time input.
     */
    private function time(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : substr($value, 0, 5),
            set: fn (?string $value) => $value === null ? null : substr($value, 0, 5).':00',
        );
    }
}
