<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $fillable = [
        'accommodation_id',
        'season_id',
        'start_date',
        'end_date',
        'price_per_night',
        'min_nights',
        'arrival_day',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'price_per_night' => 'decimal:2',
            'min_nights' => 'integer',
            'arrival_day' => 'integer',
        ];
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
