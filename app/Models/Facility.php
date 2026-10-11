<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'icon',
        'sort_order',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(FacilityTranslation::class);
    }

    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(
            Accommodation::class,
            'accommodation_facilities',
            'facility_id',
            'accommodation_id'
        );
    }

    public function translate(string $code): ?FacilityTranslation
    {
        return $this->translations()
            ->whereHas('language', fn($q) => $q->where('code', $code))
            ->first();
    }
}
