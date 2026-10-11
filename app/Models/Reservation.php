<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'reservation_number', 'accommodation_id', 'guest_id', 'arrival_date', 'departure_date',
    'adults', 'children', 'pets', 'status', 'total_amount', 'guest_note', 'internal_note',
    'cancelled_at', 'cancellation_reason',
])]
class Reservation extends Model
{
    protected function casts(): array
    {
        return [
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'status' => ReservationStatus::class,
            'total_amount' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function scopeBooked(Builder $query): void
    {
        $query->whereIn('status', ReservationStatus::booked());
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class)->withTrashed();
    }
}
