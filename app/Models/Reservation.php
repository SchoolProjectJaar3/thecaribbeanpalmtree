<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_number',
        'accommodation_id',
        'guest_id',
        'arrival_date',
        'departure_date',
        'adults',
        'children',
        'pets',
        'status',
        'total_amount',
        'guest_note',
        'internal_note',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'cancelled_at' => 'datetime',
            'status' => ReservationStatus::class,
            'total_amount' => 'decimal:2',
            'adults' => 'integer',
            'children' => 'integer',
            'pets' => 'integer',
        ];
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
