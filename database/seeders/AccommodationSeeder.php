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
            'max_guests' => 4,      // testvalue
            'bedrooms' => 2,        // testvalue
            'bathrooms' => 1,       // testvalue
            'pets_allowed' => false,
            'city' => 'Harmonie',
            'country_code' => 'CW',
            'is_active' => true,
        ]);

        $accommodation->translations()->updateOrCreate(['language_id' => $nl->id], [
            'short_description' => 'Luxe vakantiewoning op Curaçao, rustig en centraal gelegen.',
            'description' => 'Testbeschrijving in het Nederlands.',
        ]);
        $accommodation->translations()->updateOrCreate(['language_id' => $en->id], [
            'short_description' => 'Luxury holiday home on Curaçao, quiet and central.',
            'description' => 'Test description in English.',
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
