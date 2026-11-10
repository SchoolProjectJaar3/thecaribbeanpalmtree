<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        // DEMO-GEGEVENS: vervangen door definitieve gegevens van Wilma.

        Season::updateOrCreate(
            ['name' => 'Laagseizoen'],
            ['color' => '#4A90E2']
        );

        Season::updateOrCreate(
            ['name' => 'Hoogseizoen'],
            ['color' => '#F5A623']
        );

        
    }
}

?>