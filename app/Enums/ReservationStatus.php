<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Request = 'request';
    case Confirmed = 'confirmed';
    case DepositPaid = 'deposit_paid';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Request => 'Aanvraag',
            self::Confirmed => 'Bevestigd',
            self::DepositPaid => 'Aanbetaald',
            self::Paid => 'Betaald',
            self::Cancelled => 'Geannuleerd',
            self::Completed => 'Afgerond',
        };
    }

    /**
     * Statuses in which the accommodation is actually occupied.
     *
     * @return array<int, self>
     */
    public static function booked(): array
    {
        return [self::Confirmed, self::DepositPaid, self::Paid];
    }
}
