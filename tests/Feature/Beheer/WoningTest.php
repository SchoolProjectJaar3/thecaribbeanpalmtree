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
 * De huidige woninggegevens, zoals het formulier ze verstuurt.
 *
 * @return array<string, mixed>
 */
function woningFormulier(): array
{
    $woning = Accommodation::current();
    $data = $woning->only([
        'name', 'max_guests', 'bedrooms', 'bathrooms', 'surface_area_m2', 'check_in_time', 'check_out_time',
        'street', 'house_number', 'postal_code', 'city', 'country_code',
    ]);
    $data['pets_allowed'] = (int) $woning->pets_allowed;

    foreach ($woning->translations as $vertaling) {
        foreach (['short_description', 'description', 'house_rules'] as $veld) {
            $data['vertalingen'][$veld][$vertaling->language->code] = $vertaling->{$veld};
        }
    }

    foreach (Facility::with('translations.language')->get() as $voorziening) {
        $data['voorzieningen'][$voorziening->id] = [
            'aanwezig' => (int) $woning->facilities->contains($voorziening),
            'icon' => $voorziening->icon,
            'naam' => $voorziening->translations->mapWithKeys(fn ($vertaling) => [$vertaling->language->code => $vertaling->name])->all(),
        ];
    }

    return $data;
}

function voorziening(string $code): Facility
{
    return Facility::where('code', $code)->firstOrFail();
}

it('stuurt gasten door naar de login', function () {
    $this->get(route('beheer.woning'))->assertRedirect(route('login'));
    $this->put(route('beheer.woning.update'))->assertRedirect(route('login'));
});

it('toont de huidige woninggegevens in het formulier', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('beheer.woning'))
        ->assertOk()
        ->assertSee('value="The Caribbean Palm Tree"', false)
        ->assertSee('name="max_guests"', false)
        ->assertSee('value="15:00"', false)
        ->assertSee('De woning ligt rustig en centraal op Harmonie')
        ->assertSee('Inside and outside flow seamlessly together')
        ->assertSee('Privézwembad')
        ->assertSee('Nieuwe voorziening toevoegen')
        ->assertSee(route('het-huis'));
});

it('slaat de gewijzigde gegevens op', function () {
    $data = woningFormulier();
    $data['max_guests'] = 8;
    $data['bedrooms'] = 4;
    $data['bathrooms'] = 3;
    $data['surface_area_m2'] = 180;
    $data['pets_allowed'] = 1;
    $data['check_in_time'] = '16:00';
    $data['vertalingen']['description']['nl'] = "Eerste alinea.\n\nTweede alinea.";

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertRedirect(route('beheer.woning'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'De woninggegevens zijn opgeslagen en staan direct op de website.');

    $woning = Accommodation::current();

    expect($woning)
        ->max_guests->toBe(8)
        ->bedrooms->toBe(4)
        ->bathrooms->toBe(3)
        ->surface_area_m2->toBe(180)
        ->pets_allowed->toBeTrue()
        ->check_in_time->toBe('16:00')
        ->and($woning->paragraphs())->toBe(['Eerste alinea.', 'Tweede alinea.'])
        ->and($woning->text('description', 'en'))->toStartWith('The house is quietly');
});

it('meldt het als er niets is gewijzigd', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), woningFormulier())
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Er was niets gewijzigd.');
});

it('weigert negatieve en ongeldige aantallen', function () {
    $data = woningFormulier();
    $data['max_guests'] = 0;
    $data['bedrooms'] = -1;
    $data['bathrooms'] = 'twee';
    $data['surface_area_m2'] = -20;

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
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

it('weigert te grote aantallen', function () {
    $data = woningFormulier();
    $data['max_guests'] = 51;

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors(['max_guests' => 'Dit getal mag niet hoger zijn dan 50.']);
});

it('houdt de verplichte velden ingevuld', function () {
    $data = woningFormulier();
    $data['name'] = '';
    $data['max_guests'] = '';
    $data['vertalingen']['description']['nl'] = '';
    unset($data['pets_allowed']);

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors(['name', 'max_guests', 'pets_allowed', 'vertalingen.description.nl']);

    expect(Accommodation::current()->name)->toBe('The Caribbean Palm Tree');
});

it('weigert een ongeldige tijd, landcode en icoon', function () {
    $data = woningFormulier();
    $data['check_out_time'] = '25:00';
    $data['country_code'] = 'Curaçao';
    $data['voorzieningen'][voorziening('wifi')->id]['icon'] = 'bestaat-niet';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors(['check_out_time', 'country_code', 'voorzieningen.'.voorziening('wifi')->id.'.icon']);
});

it('zet een landcode om naar hoofdletters', function () {
    $data = woningFormulier();
    $data['country_code'] = 'nl';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors();

    expect(Accommodation::current()->country_code)->toBe('NL');
});

it('valt terug op de standaardtaal als een vertaling leeg wordt gelaten', function () {
    $data = woningFormulier();
    $data['vertalingen']['short_description']['en'] = '';
    $data['vertalingen']['description']['en'] = '';
    $data['vertalingen']['house_rules']['en'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors();

    $woning = Accommodation::current();

    expect($woning->translations)->toHaveCount(1)
        ->and($woning->text('description', 'en'))->toStartWith('De woning ligt rustig');
});

it('zet voorzieningen aan en uit en past namen en iconen aan', function () {
    $wifi = voorziening('wifi');
    $tv = voorziening('tv');

    $data = woningFormulier();
    $data['voorzieningen'][$tv->id]['aanwezig'] = 0;
    $data['voorzieningen'][$wifi->id]['naam']['nl'] = 'Glasvezelinternet';
    $data['voorzieningen'][$wifi->id]['naam']['en'] = '';
    $data['voorzieningen'][$wifi->id]['icon'] = 'water';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'De woninggegevens zijn opgeslagen en staan direct op de website.');

    $woning = Accommodation::current();
    $wifi = $woning->facilities->firstWhere('code', 'wifi');

    expect($woning->facilities->pluck('code'))->not->toContain('tv')
        ->and($wifi->name())->toBe('Glasvezelinternet')
        ->and($wifi->name('en'))->toBe('Glasvezelinternet')
        ->and($wifi->icon)->toBe('water')
        ->and(Facility::where('code', 'tv')->exists())->toBeTrue();
});

it('verplicht de naam van een voorziening in de standaardtaal', function () {
    $id = voorziening('wifi')->id;
    $data = woningFormulier();
    $data['voorzieningen'][$id]['naam']['nl'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors("voorzieningen.{$id}.naam.nl");
});

it('voegt een nieuwe voorziening toe en zet die bij de woning', function () {
    $data = woningFormulier();
    $data['nieuwe_voorziening'] = ['naam' => ['nl' => 'Buitendouche', 'en' => 'Outdoor shower'], 'icon' => 'water'];

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors();

    $nieuw = Accommodation::current()->facilities->firstWhere('code', 'buitendouche');

    expect($nieuw)->not->toBeNull()
        ->and($nieuw->name('en'))->toBe('Outdoor shower')
        ->and($nieuw->icon)->toBe('water')
        ->and($nieuw->sort_order)->toBe(Facility::max('sort_order'));
});

it('weigert een nieuwe voorziening die al bestaat of geen Nederlandse naam heeft', function () {
    $data = woningFormulier();
    $data['nieuwe_voorziening'] = ['naam' => ['nl' => 'Barbecue', 'en' => '']];

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors(['nieuwe_voorziening.naam.nl' => 'Deze voorziening bestaat al.']);

    $data['nieuwe_voorziening'] = ['naam' => ['nl' => '', 'en' => 'Hammock']];

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasErrors('nieuwe_voorziening.naam.nl');

    expect(Facility::count())->toBe(12);
});

it('toont de wijzigingen direct op de publieke website', function () {
    $data = woningFormulier();
    $data['max_guests'] = 8;
    $data['bedrooms'] = 4;
    $data['pets_allowed'] = 1;
    $data['city'] = 'Willemstad';
    $data['check_out_time'] = '10:30';
    $data['vertalingen']['description']['nl'] = 'Een gloednieuwe beschrijving van de woning.';
    $data['vertalingen']['house_rules']['nl'] = "Schoenen uit in huis.\nGeen glas bij het zwembad.";
    $data['voorzieningen'][voorziening('dishwasher')->id]['aanwezig'] = 0;
    $data['nieuwe_voorziening'] = ['naam' => ['nl' => 'Hangmat', 'en' => 'Hammock'], 'icon' => 'palm'];

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors();

    $this->get(route('het-huis'))
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

    $this->get(route('privacy-voorwaarden'))
        ->assertSee('De woning biedt plaats aan maximaal 8 personen.')
        ->assertSee('Inchecken kan vanaf 15.00 uur, uitchecken kan tot 10.30 uur.')
        ->assertSee('Huisdieren zijn welkom.')
        ->assertSee('Geen glas bij het zwembad.')
        ->assertDontSee('Ga zuinig om met water en energie.');

    $this->get(route('reserveren'))
        ->assertSee('<option value="8">8 personen</option>', false)
        ->assertSee('De woning biedt plaats aan maximaal 8 personen.');

    $this->post(route('reserveren.verstuur'), ['personen' => 9])
        ->assertSessionHasErrors(['personen' => 'De woning biedt plaats aan maximaal 8 personen.']);

    $this->post(route('reserveren.verstuur'), ['personen' => 8])
        ->assertSessionDoesntHaveErrors('personen');
});

it('legt een woning vast als die er nog niet is', function () {
    Accommodation::query()->delete();

    $this->actingAs(User::factory()->create())
        ->get(route('beheer.woning'))
        ->assertOk();

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), [
            'name' => 'Villa Palmera',
            'max_guests' => 4,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'pets_allowed' => 0,
            'vertalingen' => [
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

it('overschrijft aanpassingen niet als de seeder opnieuw draait', function () {
    $data = woningFormulier();
    $data['max_guests'] = 2;
    $data['vertalingen']['description']['nl'] = 'Aangepast in het beheer.';
    $data['voorzieningen'][voorziening('bbq')->id]['aanwezig'] = 0;

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.woning.update'), $data)
        ->assertSessionHasNoErrors();

    $this->seed(AccommodationSeeder::class);

    $woning = Accommodation::current();

    expect($woning->max_guests)->toBe(2)
        ->and($woning->text('description'))->toBe('Aangepast in het beheer.')
        ->and($woning->facilities->pluck('code'))->not->toContain('bbq')
        ->and(Accommodation::count())->toBe(1);
});
