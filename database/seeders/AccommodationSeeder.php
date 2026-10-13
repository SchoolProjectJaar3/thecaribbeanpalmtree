<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Facility;
use App\Models\Language;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nl = Language::where('code', 'nl')->firstOrFail();
        $en = Language::where('code', 'en')->firstOrFail();

        $accommodation = Accommodation::updateOrCreate(['slug' => 'the-caribbean-palm-tree'], [
        'name' => 'The Caribbean Palm Tree',
        'max_guests' => 6,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'pets_allowed' => false,
        'city' => 'Harmonie',
        'country_code' => 'CW',
        'is_active' => true,
        ]);

        $accommodation->translations()->updateOrCreate(['language_id' => $nl->id], [
            'short_description' => 'Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen. Hier kom je na een dag op het strand helemaal tot rust.',
            'description' => "De woning ligt rustig en centraal op Harmonie, op korte rijafstand van de mooiste stranden, van Willemstad en van de luchthaven. Binnen en buiten lopen vloeiend in elkaar over: de openslaande deuren van de woonkamer komen uit op het terras met het privézwembad.\n\nDe drie slaapkamers hebben airconditioning en de keuken is volledig uitgerust. Zo heb je alles wat je nodig hebt, of je nu een week of een maand blijft.",
        ]);
        $accommodation->translations()->updateOrCreate(['language_id' => $en->id], [
            'short_description' => 'A spacious, bright home with its own pool and a garden full of tropical greenery. After a day at the beach, this is where you come to unwind.',
            'description' => "The home is quiet and central in Harmonie, a short drive from the most beautiful beaches, Willemstad and the airport. Inside and outside flow into each other: the French doors of the living room open onto the terrace with the private pool.\n\nThe three bedrooms have air conditioning and the kitchen is fully equipped. You have everything you need, whether you stay for a week or a month.",
        ]);

        $facilities = [
            ['wifi',             1, 'Wifi',              'Wifi'],
            ['pool',             2, 'Privézwembad',      'Private pool'],
            ['air_conditioning', 3, 'Airconditioning',   'Air conditioning'],
            ['tv',               4, 'Smart-tv',          'Smart TV'],
            ['kitchen',          5, 'Volledige keuken',  'Fully equipped kitchen'],
            ['dishwasher',       6, 'Vaatwasser',        'Dishwasher'],
            ['washing_machine',  7, 'Wasmachine',        'Washing machine'],
            ['bbq',              8, 'Barbecue',          'Barbecue'],
            ['parking',          9, 'Parkeerplaats',     'Parking'],
        ];

        $ids = [];
        foreach ($facilities as [$code, $order, $nameNl, $nameEn]) {
            $facility = Facility::updateOrCreate(['code' => $code], ['sort_order' => $order]);
            $facility->translations()->updateOrCreate(['language_id' => $nl->id], ['name' => $nameNl]);
            $facility->translations()->updateOrCreate(['language_id' => $en->id], ['name' => $nameEn]);
            $ids[] = $facility->id;
        }

        $accommodation->facilities()->syncWithoutDetaching($ids);
    }
}
