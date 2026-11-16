<?php

namespace Database\Seeders;

use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use App\Enums\ReservationStatus;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $accommodationId = DB::table('accommodations')
            ->where('slug', 'the-caribbean-palm-tree')
            ->value('id');

        if (!$accommodationId) {
            throw new RuntimeException(
                'Accommodatie niet gevonden. Voer eerst AccommodationSeeder uit.'
            );
        }

        $guestOne = Guest::updateOrCreate(
            ['email' => 'demo.gast1@example.test'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Gast Een',
            ]
        );

        $guestTwo = Guest::updateOrCreate(
            ['email' => 'demo.gast2@example.test'],
            [
                'first_name' => 'Demo',
                'last_name' => 'Gast Twee',
            ]
        );

        $tarieven = config('woning.tarieven');

        if (!$tarieven) {
            throw new RuntimeException(
                'Tarieven niet gevonden in config/woning.php.'
            );
        }

        Reservation::updateOrCreate(
            ['reservation_number' => 'TEST-2026-001'],
            [
                'accommodation_id' => $accommodationId,
                'guest_id' => $guestOne->id,
                'arrival_date' => '2026-11-18',
                'departure_date' => '2026-11-23',
                'adults' => 2,
                'children' => 0,
                'pets' => 0,
                'status' => ReservationStatus::Confirmed,
                'total_amount' => $tarieven['prijs_laag'] * 5,
                'internal_note' => 'TESTGEGEVENS - fictieve reservering.',
            ]
        );

        Reservation::updateOrCreate(
            ['reservation_number' => 'TEST-2027-001'],
            [
                'accommodation_id' => $accommodationId,
                'guest_id' => $guestTwo->id,
                'arrival_date' => '2027-01-10',
                'departure_date' => '2027-01-17',
                'adults' => 2,
                'children' => 1,
                'pets' => 0,
                'status' => ReservationStatus::Request,
                'total_amount' => $tarieven['prijs_hoog'] * 7,
                'internal_note' => 'TESTGEGEVENS - fictieve reservering.',
            ]
        );
    }
}
