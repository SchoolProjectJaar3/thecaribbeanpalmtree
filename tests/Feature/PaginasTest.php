<?php

use App\Models\CmsContent;
use App\Models\CmsPage;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\CmsSeeder;
use Database\Seeders\LanguageSeeder;

dataset('paginas', [
    'home' => ['/', 'The Caribbean Palm Tree'],
    'het huis' => ['/het-huis', 'Alle comfort voor een zorgeloze vakantie'],
    'fotos' => ['/fotos', 'Een kijkje in de woning'],
    'beschikbaarheid' => ['/beschikbaarheid', 'Wanneer kom jij?'],
    'tarieven' => ['/tarieven', 'Duidelijke prijzen, geen verborgen kosten'],
    'omgeving' => ['/omgeving', 'Reistijden vanaf de woning'],
    'reviews' => ['/reviews', 'Wat onze gasten zeggen'],
    'faq' => ['/faq', 'Is een auto noodzakelijk?'],
    'over de eigenaar' => ['/over-de-eigenaar', 'Welkom bij Wilma'],
    'praktische informatie' => ['/praktische-informatie', 'In- en uitchecken'],
    'contact' => ['/contact', 'Stuur een bericht'],
    'reserveren' => ['/reserveren', 'Vraag je verblijf aan'],
    'privacy en voorwaarden' => ['/privacy-voorwaarden', 'Boekingsvoorwaarden'],
]);

it('toont de publieke pagina', function (string $url, string $tekst) {
    $this->get($url)
        ->assertOk()
        ->assertSee($tekst, false);
})->with('paginas');

it('verstuurt het contactformulier', function () {
    $this->post('/contact', [
        'naam' => 'Jan Jansen',
        'email' => 'jan@example.com',
        'bericht' => 'Is de woning ook beschikbaar in december?',
    ])->assertRedirect(route('contact') . '#bevestiging')
        ->assertSessionHas('status');
});

it('valideert het contactformulier', function () {
    $this->post('/contact', ['naam' => '', 'email' => 'geen-email', 'bericht' => ''])
        ->assertSessionHasErrors(['naam', 'email', 'bericht']);
});

it('wijst het contactformulier af als het spamveld is ingevuld', function () {
    $this->post('/contact', [
        'naam' => 'Bot',
        'email' => 'bot@example.com',
        'bericht' => 'Koop nu',
        'website' => 'https://spam.example.com',
    ])->assertSessionHasErrors('website');
});

it('verstuurt een reserveringsaanvraag', function () {
    $this->post('/reserveren', [
        'aankomst' => now()->addDays(30)->toDateString(),
        'vertrek' => now()->addDays(37)->toDateString(),
        'personen' => 4,
        'naam' => 'Jan Jansen',
        'email' => 'jan@example.com',
        'akkoord' => '1',
    ])->assertRedirect(route('reserveren') . '#bevestiging')
        ->assertSessionHas('status');
});

it('valideert de reserveringsaanvraag', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);

    $this->post('/reserveren', [
        'aankomst' => now()->addDays(10)->toDateString(),
        'vertrek' => now()->addDays(5)->toDateString(),
        'personen' => 9,
        'naam' => 'Jan Jansen',
        'email' => 'jan@example.com',
    ])->assertSessionHasErrors(['vertrek', 'personen', 'akkoord']);
});

it('vult het reserveringsformulier in met de gekozen data', function () {
    $this->get('/reserveren?aankomst=2030-01-10&vertrek=2030-01-17')
        ->assertOk();
});

it('toont de woninggegevens uit de database', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);

    $this->get('/het-huis')
        ->assertOk()
        ->assertSee('6 gasten')
        ->assertSee('3 kamers')
        ->assertSee('2 badkamers')
        ->assertSee('Niet toegestaan')
        ->assertSee('De woning ligt rustig en centraal op Harmonie')
        ->assertSee('Vaatwasser');

    $this->get('/')
        ->assertOk()
        ->assertSee('6 gasten')
        ->assertSee('Een ruime, lichte woning met een eigen zwembad');

    $this->get('/privacy-voorwaarden')
        ->assertOk()
        ->assertSee('De woning biedt plaats aan maximaal 6 personen.')
        ->assertSee('Inchecken kan vanaf 15.00 uur, uitchecken kan tot 11.00 uur.')
        ->assertSee('Huisdieren zijn niet toegestaan.')
        ->assertSee('Ga zuinig om met water en energie.');
});

function cmsTekst(string $pagina, string $sectie, string $veld, string $taal): ?CmsContent
{
    return CmsPage::byKey($pagina)->sections->firstWhere('key', $sectie)->contents
        ->first(fn ($content) => $content->key === $veld && $content->language->code === $taal);
}

it('toont de teksten uit het CMS op de homepagina', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    cmsTekst('home', 'hero', 'title', 'nl')->update(['content' => 'Welkom op Curaçao']);
    cmsTekst('home', 'hero', 'booking_button', 'nl')->update(['content' => 'Reserveer direct']);
    cmsTekst('home', 'surroundings', 'text', 'nl')->update(['content' => 'Alles ligt dichtbij.']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Welkom op Curaçao')
        ->assertSee('Reserveer direct')
        ->assertSee('Alles ligt dichtbij.')
        ->assertSee('Vakantiewoning op Curaçao');
});

it('toont de teksten uit het CMS op het huis', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class, CmsSeeder::class]);

    cmsTekst('property', 'hero', 'intro', 'nl')->update(['content' => 'Een nieuwe introductie.']);
    cmsTekst('property', 'amenities', 'title', 'nl')->update(['content' => 'Van alle gemakken voorzien']);

    $this->get('/het-huis')
        ->assertOk()
        ->assertSee('Een nieuwe introductie.')
        ->assertSee('Van alle gemakken voorzien')
        ->assertSee('Een rondleiding door de woning')
        ->assertSee('Vaatwasser');
});

it('toont de Engelse teksten uit het CMS', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    app()->setLocale('en');

    $this->get('/')
        ->assertOk()
        ->assertSee('Holiday home on Curaçao')
        ->assertSee('Book now');

    $this->get('/het-huis')
        ->assertOk()
        ->assertSee('A tour of the house');
});

it('valt terug op een vaste tekst als een CMS-tekst ontbreekt', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    cmsTekst('home', 'hero', 'booking_button', 'en')->delete();
    cmsTekst('home', 'hero', 'eyebrow', 'en')->delete();
    cmsTekst('home', 'hero', 'eyebrow', 'nl')->delete();

    app()->setLocale('en');

    $this->get('/')
        ->assertOk()
        ->assertSee('Boek nu')
        ->assertSee('Vakantiewoning op Curaçao');
});
