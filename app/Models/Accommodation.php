<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        );
    }

    // Vertaling in een bepaalde taal bijvoobeeld $accommodation->translate('nl')
    public function translate(string $code): ?AccommodationTranslation
    {
        return $this->translations()
            ->whereHas('language', fn($q) => $q->where('code', $code))
            ->first();
    }
}
