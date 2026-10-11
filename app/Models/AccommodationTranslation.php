<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'accommodation_id',
        'language_id',
        'short_description',
        'description',
        'house_rules',
    ];

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
