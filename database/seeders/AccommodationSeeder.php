<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Facility;
use App\Models\Language;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Vul de woning, de teksten en de voorzieningen. Bestaande gegevens worden
     * niet overschreven, zodat aanpassingen uit het beheer behouden blijven.
     */
    public function run(): void
    {
        $nl = Language::where('code', 'nl')->firstOrFail();
        $en = Language::where('code', 'en')->firstOrFail();

        $accommodation = Accommodation::firstOrCreate(['slug' => 'the-caribbean-palm-tree'], [
            'name' => 'The Caribbean Palm Tree',
            'max_guests' => 6,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'pets_allowed' => false,
            'city' => 'Harmonie',
            'country_code' => 'CW',
            'check_in_time' => '15:00',
            'check_out_time' => '11:00',
            'is_active' => true,
        ]);

        $accommodation->translations()->firstOrCreate(['language_id' => $nl->id], [
            'short_description' => 'Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen. Hier kom je na een dag op het strand helemaal tot rust.',
            'description' => implode("\n\n", [
                'De woning ligt rustig en centraal op Harmonie, op korte rijafstand van de mooiste stranden, van Willemstad en van de luchthaven. Binnen en buiten lopen vloeiend in elkaar over: de openslaande deuren van de woonkamer komen uit op het terras met het privézwembad.',
                'De drie slaapkamers hebben airconditioning en de keuken is volledig uitgerust. Zo heb je alles wat je nodig hebt, of je nu een week of een maand blijft.',
            ]),
            'house_rules' => implode("\n", [
                'Roken is binnen niet toegestaan; op het terras mag dat wel.',
                'Feesten en evenementen zijn niet toegestaan. Houd na 22.00 uur rekening met de buren.',
                'Ga zuinig om met water en energie.',
                'Laat de woning bij vertrek netjes en afgesloten achter.',
            ]),
        ]);
        $accommodation->translations()->firstOrCreate(['language_id' => $en->id], [
            'short_description' => 'A spacious, bright home with a private pool and a garden full of tropical greenery. The perfect place to unwind after a day at the beach.',
            'description' => implode("\n\n", [
                'The house is quietly yet centrally located in Harmonie, a short drive from the most beautiful beaches, Willemstad and the airport. Inside and outside flow seamlessly together: the French doors of the living room open onto the terrace with the private pool.',
                'All three bedrooms have air conditioning and the kitchen is fully equipped. Everything you need, whether you stay for a week or a month.',
            ]),
            'house_rules' => implode("\n", [
                'Smoking is not allowed indoors; it is allowed on the terrace.',
                'Parties and events are not allowed. Please be considerate of the neighbours after 10 pm.',
                'Please use water and energy sparingly.',
                'Leave the house clean and locked when you depart.',
            ]),
        ]);

        $facilities = [
            ['wifi',             'wifi',       'Wifi',              'Wifi'],
            ['pool',             'pool',       'Privézwembad',      'Private pool'],
            ['air_conditioning', 'snowflake',  'Airconditioning',   'Air conditioning'],
            ['tv',               'tv',         'Smart-tv',          'Smart TV'],
            ['kitchen',          'kitchen',    'Volledige keuken',  'Fully equipped kitchen'],
            ['dishwasher',       'dishwasher', 'Vaatwasser',        'Dishwasher'],
            ['washing_machine',  'washing',    'Wasmachine',        'Washing machine'],
            ['bbq',              'bbq',        'Barbecue',          'Barbecue'],
            ['parking',          'parking',    'Parkeerplaats',     'Parking'],
            ['crib',             'crib',       'Kinderbed',         'Baby cot'],
            ['terrace',          'sun',        'Overdekt terras',   'Covered terrace'],
            ['garden',           'palm',       'Tropische tuin',    'Tropical garden'],
        ];

        $ids = [];
        foreach ($facilities as $order => [$code, $icon, $nameNl, $nameEn]) {
            $facility = Facility::firstOrCreate(['code' => $code], ['icon' => $icon, 'sort_order' => $order + 1]);
            $facility->icon ??= $icon;
            $facility->save();

            $facility->translations()->firstOrCreate(['language_id' => $nl->id], ['name' => $nameNl]);
            $facility->translations()->firstOrCreate(['language_id' => $en->id], ['name' => $nameEn]);
            $ids[] = $facility->id;
        }

        if ($accommodation->wasRecentlyCreated) {
            $accommodation->facilities()->attach($ids);
        }
    }
}
