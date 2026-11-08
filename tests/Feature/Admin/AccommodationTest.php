<?php

use App\Models\Accommodation;
use App\Models\Facility;
use App\Models\User;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);
});

/**
 * The current accommodation details, as the form submits them.
 *
 * @return array<string, mixed>
 */
function accommodationForm(): array
{
    $accommodation = Accommodation::current();
    $data = $accommodation->only([
        'name', 'max_guests', 'bedrooms', 'bathrooms', 'surface_area_m2', 'check_in_time', 'check_out_time',
        'street', 'house_number', 'postal_code', 'city', 'country_code',
    ]);
    $data['pets_allowed'] = (int) $accommodation->pets_allowed;

    foreach ($accommodation->translations as $translation) {
        foreach (['short_description', 'description', 'house_rules'] as $field) {
            $data['translations'][$field][$translation->language->code] = $translation->{$field};
        }
    }

    foreach (Facility::with('translations.language')->get() as $facility) {
        $data['facilities'][$facility->id] = [
            'selected' => (int) $accommodation->facilities->contains($facility),
            'icon' => $facility->icon,
            'name' => $facility->translations->mapWithKeys(fn ($translation) => [$translation->language->code => $translation->name])->all(),
        ];
    }

    return $data;
}

function facility(string $code): Facility
{
    return Facility::where('code', $code)->firstOrFail();
}

it('redirects guests to the login page', function () {
    $this->get(route('admin.accommodation'))->assertRedirect(route('login'));
    $this->put(route('admin.accommodation.update'))->assertRedirect(route('login'));
});

it('shows the current accommodation details in the form', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.accommodation'))
        ->assertOk()
        ->assertSee('value="The Caribbean Palm Tree"', false)
        ->assertSee('name="max_guests"', false)
        ->assertSee('value="15:00"', false)
        ->assertSee('De woning ligt rustig en centraal op Harmonie')
        ->assertSee('Inside and outside flow seamlessly together')
        ->assertSee('Privézwembad')
        ->assertSee('Nieuwe voorziening toevoegen')
        ->assertSee(route('property'));
});

it('saves the changed details', function () {
    $data = accommodationForm();
    $data['max_guests'] = 8;
    $data['bedrooms'] = 4;
    $data['bathrooms'] = 3;
    $data['surface_area_m2'] = 180;
    $data['pets_allowed'] = 1;
    $data['check_in_time'] = '16:00';
    $data['translations']['description']['nl'] = "Eerste alinea.\n\nTweede alinea.";

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertRedirect(route('admin.accommodation'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'De woninggegevens zijn opgeslagen en staan direct op de website.');

    $accommodation = Accommodation::current();

    expect($accommodation)
        ->max_guests->toBe(8)
        ->bedrooms->toBe(4)
        ->bathrooms->toBe(3)
        ->surface_area_m2->toBe(180)
        ->pets_allowed->toBeTrue()
        ->check_in_time->toBe('16:00')
        ->and($accommodation->paragraphs())->toBe(['Eerste alinea.', 'Tweede alinea.'])
        ->and($accommodation->text('description', 'en'))->toStartWith('The house is quietly');
});

it('reports when nothing was changed', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), accommodationForm())
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Er was niets gewijzigd.');
});

it('rejects negative and invalid numbers', function () {
    $data = accommodationForm();
    $data['max_guests'] = 0;
    $data['bedrooms'] = -1;
    $data['bathrooms'] = 'twee';
    $data['surface_area_m2'] = -20;

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors([
            'max_guests' => 'Dit getal mag niet lager zijn dan 1.',
            'bedrooms' => 'Dit getal mag niet lager zijn dan 0.',
            'bathrooms' => 'Vul een heel getal in.',
            'surface_area_m2' => 'Dit getal mag niet lager zijn dan 1.',
        ]);

    expect(Accommodation::current())
        ->max_guests->toBe(6)
        ->bedrooms->toBe(3)
        ->bathrooms->toBe(2);
});

it('rejects numbers that are too large', function () {
    $data = accommodationForm();
    $data['max_guests'] = 51;

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors(['max_guests' => 'Dit getal mag niet hoger zijn dan 50.']);
});

it('keeps the required fields filled in', function () {
    $data = accommodationForm();
    $data['name'] = '';
    $data['max_guests'] = '';
    $data['translations']['description']['nl'] = '';
    unset($data['pets_allowed']);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors(['name', 'max_guests', 'pets_allowed', 'translations.description.nl']);

    expect(Accommodation::current()->name)->toBe('The Caribbean Palm Tree');
});

it('rejects an invalid time, country code and icon', function () {
    $data = accommodationForm();
    $data['check_out_time'] = '25:00';
    $data['country_code'] = 'Curaçao';
    $data['facilities'][facility('wifi')->id]['icon'] = 'does-not-exist';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors(['check_out_time', 'country_code', 'facilities.'.facility('wifi')->id.'.icon']);
});

it('converts a country code to uppercase', function () {
    $data = accommodationForm();
    $data['country_code'] = 'nl';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors();

    expect(Accommodation::current()->country_code)->toBe('NL');
});

it('falls back to the default language when a translation is left empty', function () {
    $data = accommodationForm();
    $data['translations']['short_description']['en'] = '';
    $data['translations']['description']['en'] = '';
    $data['translations']['house_rules']['en'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors();

    $accommodation = Accommodation::current();

    expect($accommodation->translations)->toHaveCount(1)
        ->and($accommodation->text('description', 'en'))->toStartWith('De woning ligt rustig');
});

it('toggles facilities and changes names and icons', function () {
    $wifi = facility('wifi');
    $tv = facility('tv');

    $data = accommodationForm();
    $data['facilities'][$tv->id]['selected'] = 0;
    $data['facilities'][$wifi->id]['name']['nl'] = 'Glasvezelinternet';
    $data['facilities'][$wifi->id]['name']['en'] = '';
    $data['facilities'][$wifi->id]['icon'] = 'water';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'De woninggegevens zijn opgeslagen en staan direct op de website.');

    $accommodation = Accommodation::current();
    $wifi = $accommodation->facilities->firstWhere('code', 'wifi');

    expect($accommodation->facilities->pluck('code'))->not->toContain('tv')
        ->and($wifi->name())->toBe('Glasvezelinternet')
        ->and($wifi->name('en'))->toBe('Glasvezelinternet')
        ->and($wifi->icon)->toBe('water')
        ->and(Facility::where('code', 'tv')->exists())->toBeTrue();
});

it('requires the name of a facility in the default language', function () {
    $id = facility('wifi')->id;
    $data = accommodationForm();
    $data['facilities'][$id]['name']['nl'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors("facilities.{$id}.name.nl");
});

it('adds a new facility and attaches it to the accommodation', function () {
    $data = accommodationForm();
    $data['new_facility'] = ['name' => ['nl' => 'Buitendouche', 'en' => 'Outdoor shower'], 'icon' => 'water'];

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors();

    $new = Accommodation::current()->facilities->firstWhere('code', 'buitendouche');

    expect($new)->not->toBeNull()
        ->and($new->name('en'))->toBe('Outdoor shower')
        ->and($new->icon)->toBe('water')
        ->and($new->sort_order)->toBe(Facility::max('sort_order'));
});

it('rejects a new facility that already exists or has no Dutch name', function () {
    $data = accommodationForm();
    $data['new_facility'] = ['name' => ['nl' => 'Barbecue', 'en' => '']];

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors(['new_facility.name.nl' => 'Deze voorziening bestaat al.']);

    $data['new_facility'] = ['name' => ['nl' => '', 'en' => 'Hammock']];

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasErrors('new_facility.name.nl');

    expect(Facility::count())->toBe(12);
});

it('shows the changes directly on the public website', function () {
    $data = accommodationForm();
    $data['max_guests'] = 8;
    $data['bedrooms'] = 4;
    $data['pets_allowed'] = 1;
    $data['city'] = 'Willemstad';
    $data['check_out_time'] = '10:30';
    $data['translations']['description']['nl'] = 'Een gloednieuwe beschrijving van de woning.';
    $data['translations']['house_rules']['nl'] = "Schoenen uit in huis.\nGeen glas bij het zwembad.";
    $data['facilities'][facility('dishwasher')->id]['selected'] = 0;
    $data['new_facility'] = ['name' => ['nl' => 'Hangmat', 'en' => 'Hammock'], 'icon' => 'palm'];

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors();

    $this->get(route('property'))
        ->assertOk()
        ->assertSee('8 gasten')
        ->assertSee('4 kamers')
        ->assertDontSee('Niet toegestaan')
        ->assertSee('Willemstad')
        ->assertSee('Een gloednieuwe beschrijving van de woning.')
        ->assertSee('Hangmat')
        ->assertDontSee('Vaatwasser')
        ->assertDontSee('De woning ligt rustig en centraal op Harmonie');

    $this->get(route('home'))
        ->assertSee('8 gasten')
        ->assertSee('Wasmachine')
        ->assertDontSee('Vaatwasser');

    $this->get(route('privacy'))
        ->assertSee('De woning biedt plaats aan maximaal 8 personen.')
        ->assertSee('Inchecken kan vanaf 15.00 uur, uitchecken kan tot 10.30 uur.')
        ->assertSee('Huisdieren zijn welkom.')
        ->assertSee('Geen glas bij het zwembad.')
        ->assertDontSee('Ga zuinig om met water en energie.');

    $this->get(route('booking'))
        ->assertSee('<option value="8">8 personen</option>', false)
        ->assertSee('De woning biedt plaats aan maximaal 8 personen.');

    $this->post(route('booking.send'), ['guests' => 9])
        ->assertSessionHasErrors(['guests' => 'De woning biedt plaats aan maximaal 8 personen.']);

    $this->post(route('booking.send'), ['guests' => 8])
        ->assertSessionDoesntHaveErrors('guests');
});

it('creates the accommodation if it does not exist yet', function () {
    Accommodation::query()->delete();

    $this->actingAs(User::factory()->create())
        ->get(route('admin.accommodation'))
        ->assertOk();

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), [
            'name' => 'Villa Palmera',
            'max_guests' => 4,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'pets_allowed' => 0,
            'translations' => [
                'short_description' => ['nl' => 'Kort.'],
                'description' => ['nl' => 'Lang.'],
            ],
        ])
        ->assertSessionHasNoErrors();

    expect(Accommodation::current())
        ->slug->toBe('villa-palmera')
        ->is_active->toBeTrue()
        ->max_guests->toBe(4);
});

it('does not overwrite changes when the seeder runs again', function () {
    $data = accommodationForm();
    $data['max_guests'] = 2;
    $data['translations']['description']['nl'] = 'Aangepast in het beheer.';
    $data['facilities'][facility('bbq')->id]['selected'] = 0;

    $this->actingAs(User::factory()->create())
        ->put(route('admin.accommodation.update'), $data)
        ->assertSessionHasNoErrors();

    $this->seed(AccommodationSeeder::class);

    $accommodation = Accommodation::current();

    expect($accommodation->max_guests)->toBe(2)
        ->and($accommodation->text('description'))->toBe('Aangepast in het beheer.')
        ->and($accommodation->facilities->pluck('code'))->not->toContain('bbq')
        ->and(Accommodation::count())->toBe(1);
});
