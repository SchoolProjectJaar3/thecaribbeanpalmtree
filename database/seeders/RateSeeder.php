<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use App\Models\Rate;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        // Bestaande accommodatie ophalen.
        $accommodationId = DB::table('accommodations')
            ->where('slug', 'the-caribbean-palm-tree')
            ->value('id');

        if (!$accommodationId) {
            throw new RuntimeException(
                'Accommodatie niet gevonden. Voer eerst AccommodationSeeder uit.'
            );
        }

        // Seizoenen ophalen.
        $lowSeasonId = DB::table('seasons')
            ->where('name', 'Laagseizoen')
            ->value('id');

        $highSeasonId = DB::table('seasons')
            ->where('name', 'Hoogseizoen')
            ->value('id');

        if (!$lowSeasonId || !$highSeasonId) {
            throw new RuntimeException(
                'Seizoenen niet gevonden. Voer eerst SeasonSeeder uit.'
            );
        }

        // DEMO-TARIEVEN: vervangen door definitieve prijzen van Wilma.
        $rates = [
            [
                'season_id' => $lowSeasonId,
                'start_date' => '2026-05-01',
                'end_date' => '2026-11-30',
                'price_per_night' => 145,
                'min_nights' => 5,
            ],
            [
                'season_id' => $highSeasonId,
                'start_date' => '2026-12-01',
                'end_date' => '2027-04-30',
                'price_per_night' => 195,
                'min_nights' => 7,
            ],
        ];

        foreach ($rates as $rate) {
        Rate::updateOrCreate(
        [
            'accommodation_id' => $accommodationId,
            'season_id' => $rate['season_id'],
            'start_date' => $rate['start_date'],
            'end_date' => $rate['end_date'],
        ],
        [
            'price_per_night' => $rate['price_per_night'],
            'min_nights' => $rate['min_nights'],
            'arrival_day' => null,
        ]
        );
        }       
}
}