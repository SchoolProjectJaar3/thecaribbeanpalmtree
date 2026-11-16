<?php

namespace Database\Seeders;

use App\Models\BlockedPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BlockedPeriodSeeder extends Seeder
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

        BlockedPeriod::updateOrCreate(
            [
                'accommodation_id' => $accommodationId,
                'start_date' => '2027-02-01',
                'end_date' => '2027-02-05',
            ],
            [
                'reason' => 'maintenance',
                'note' => 'TESTGEGEVENS - onderhoud aan de accommodatie.',
                'created_by' => null,
            ]
        );

        BlockedPeriod::updateOrCreate(
            [
                'accommodation_id' => $accommodationId,
                'start_date' => '2027-03-10',
                'end_date' => '2027-03-14',
            ],
            [
                'reason' => 'owner_use',
                'note' => 'TESTGEGEVENS - eigen gebruik.',
                'created_by' => null,
            ]
        );
    }
}
