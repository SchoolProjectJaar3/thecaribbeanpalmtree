<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    /**
     * Let op: reservation_number, status en total_amount worden door de
     * applicatie bepaald en nooit rechtstreeks uit een formulier overgenomen.
     */
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
            'adults' => 'integer',
            'children' => 'integer',
            'pets' => 'integer',
            'status' => ReservationStatus::class,
            'total_amount' => 'decimal:2',
            'cancelled_at' => 'datetime',
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
