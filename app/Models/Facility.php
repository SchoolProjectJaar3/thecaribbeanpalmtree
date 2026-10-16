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

    /**
     * De naam in de gevraagde taal, met de standaardtaal als terugval. Zonder
     * taal krijg je de naam in de standaardtaal.
     */
    public function name(?string $code = null): string
    {
        $translation = ($code === null ? null : $this->translations->first(fn (FacilityTranslation $translation) => $translation->language->code === $code))
            ?? $this->translations->first(fn (FacilityTranslation $translation) => $translation->language->is_default);

        return $translation->name ?? $this->code;
    }
}
