<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'max_guests', 'bedrooms', 'bathrooms', 'surface_area_m2', 'pets_allowed',
    'street', 'house_number', 'postal_code', 'city', 'country_code', 'latitude', 'longitude',
    'check_in_time', 'check_out_time', 'is_active',
])]
class Accommodation extends Model
{
    protected function casts(): array
    {
        return [
            'pets_allowed' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
