<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Accommodation;
use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Stores a booking request from the booking form as a guest and a
 * reservation with status "request". The owner approves or declines it later.
 */
class BookingService
{
    public function __construct(private readonly PriceCalculator $calculator) {}

    /**
     * @param  array{arrival: string, departure: string, guests: int|string, first_name: string, last_name: string, email: string, phone?: string|null, comments?: string|null}  $data
     *
     * @throws ValidationException when the stay is not possible
     */
    public function request(Accommodation $accommodation, array $data): Reservation
    {
        $arrival = Carbon::parse($data['arrival'])->startOfDay();
        $departure = Carbon::parse($data['departure'])->startOfDay();
        $guests = (int) $data['guests'];

        // Everything in one transaction: when a check fails nothing is stored,
        // and the availability check and the insert happen together.
        return DB::transaction(function () use ($accommodation, $data, $arrival, $departure, $guests) {
            $this->ensureAvailable($accommodation, $arrival, $departure);

            $quote = $this->calculator->quote($accommodation, $arrival, $departure, $guests);

            if (! $quote['complete']) {
                $this->fail('arrival', 'Voor (een deel van) deze periode zijn nog geen tarieven bekend. Neem contact met ons op.');
            }

            if (! $quote['meets_minimum']) {
                $this->fail('departure', "De minimale verblijfsduur voor deze periode is {$quote['min_nights']} nachten.");
            }

            // A returning guest is recognised by their email address.
            $guest = Guest::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'] ?? null,
                ],
            );

            return Reservation::create([
                'reservation_number' => $this->nextReservationNumber(),
                'accommodation_id' => $accommodation->id,
                'guest_id' => $guest->id,
                'arrival_date' => $arrival,
                'departure_date' => $departure,
                'adults' => $guests,
                'status' => ReservationStatus::Request,
                'total_amount' => $quote['total'],
                'guest_note' => $data['comments'] ?? null,
            ]);
        });
    }

    /**
     * The departure day of one stay may be the arrival day of the next, so a
     * reservation only overlaps when it starts before our departure and ends
     * after our arrival. A blocked period includes its end date.
     */
    private function ensureAvailable(Accommodation $accommodation, Carbon $arrival, Carbon $departure): void
    {
        $reserved = $accommodation->reservations()
            ->whereIn('status', ReservationStatus::booked())
            ->whereDate('arrival_date', '<', $departure)
            ->whereDate('departure_date', '>', $arrival)
            ->exists();

        $blocked = $accommodation->blockedPeriods()
            ->whereDate('start_date', '<', $departure)
            ->whereDate('end_date', '>=', $arrival)
            ->exists();

        if ($reserved || $blocked) {
            $this->fail('arrival', 'De woning is in (een deel van) deze periode niet beschikbaar. Kies andere data of bekijk de beschikbaarheid.');
        }
    }

    /**
     * Readable number per year, e.g. R2026-0001, R2026-0002, ...
     * The numbers are zero-padded, so sorting them as text gives the latest.
     */
    private function nextReservationNumber(): string
    {
        $prefix = 'R' . now()->year . '-';

        $latest = Reservation::where('reservation_number', 'like', $prefix . '%')
            ->orderByDesc('reservation_number')
            ->value('reservation_number');

        $next = $latest ? (int) substr($latest, strlen($prefix)) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    private function fail(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }
}
