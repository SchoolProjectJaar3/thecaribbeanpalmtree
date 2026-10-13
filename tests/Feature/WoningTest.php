<?php

use App\Models\Accommodation;
use App\Models\Facility;
use App\Models\Language;

function maakTestWoning(): void
{
    $nl = Language::create(['code' => 'nl', 'name' => 'Nederlands', 'is_default' => true]);

    $accommodation = Accommodation::create([
        'name' => 'Testwoning', 'slug' => 'testwoning',
        'max_guests' => 11, 'bedrooms' => 5, 'bathrooms' => 2, 'city' => 'Teststad',
    ]);
    $accommodation->translations()->create([
        'language_id' => $nl->id,
        'short_description' => 'Unieke testomschrijving',
    ]);

    $facility = Facility::create(['code' => 'wifi']);
    $facility->translations()->create(['language_id' => $nl->id, 'name' => 'Uniek testwifi']);
    $accommodation->facilities()->attach($facility);
}

it('toont woninggegevens uit de database op de homepagina', function () {
    maakTestWoning();

    $this->get('/')
        ->assertOk()
        ->assertSee('Max. 11')
        ->assertSee('Teststad')
        ->assertSee('Unieke testomschrijving')
        ->assertSee('Uniek testwifi');
});

it('toont dezelfde woninggegevens op Het huis', function () {
    maakTestWoning();

    $this->get('/het-huis')
        ->assertOk()
        ->assertSee('Max. 11')
        ->assertSee('Teststad');
});