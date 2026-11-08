<?php

use App\Models\CmsContent;
use App\Models\CmsPage;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\CmsSeeder;
use Database\Seeders\LanguageSeeder;

dataset('pages', [
    'home' => ['/', 'The Caribbean Palm Tree'],
    'property' => ['/the-house', 'Alle comfort voor een zorgeloze vakantie'],
    'photos' => ['/photos', 'Een kijkje in de woning'],
    'availability' => ['/availability', 'Wanneer kom jij?'],
    'rates' => ['/rates', 'Duidelijke prijzen, geen verborgen kosten'],
    'surroundings' => ['/surroundings', 'Reistijden vanaf de woning'],
    'reviews' => ['/reviews', 'Wat onze gasten zeggen'],
    'faq' => ['/faq', 'Is een auto noodzakelijk?'],
    'owner' => ['/about-the-owner', 'Welkom bij Wilma'],
    'practical information' => ['/practical-information', 'In- en uitchecken'],
    'contact' => ['/contact', 'Stuur een bericht'],
    'booking' => ['/book', 'Vraag je verblijf aan'],
    'privacy' => ['/privacy-terms', 'Boekingsvoorwaarden'],
]);

it('shows the public page', function (string $url, string $text) {
    $this->get($url)
        ->assertOk()
        ->assertSee($text, false);
})->with('pages');

it('sends the contact form', function () {
    $this->post('/contact', [
        'name' => 'Jan Jansen',
        'email' => 'jan@example.com',
        'message' => 'Is de woning ook beschikbaar in december?',
    ])->assertRedirect(route('contact') . '#confirmation')
        ->assertSessionHas('status');
});

it('validates the contact form', function () {
    $this->post('/contact', ['name' => '', 'email' => 'not-an-email', 'message' => ''])
        ->assertSessionHasErrors(['name', 'email', 'message']);
});

it('rejects the contact form when the spam field is filled in', function () {
    $this->post('/contact', [
        'name' => 'Bot',
        'email' => 'bot@example.com',
        'message' => 'Koop nu',
        'website' => 'https://spam.example.com',
    ])->assertSessionHasErrors('website');
});

it('sends a booking request', function () {
    $this->post('/book', [
        'arrival' => now()->addDays(30)->toDateString(),
        'departure' => now()->addDays(37)->toDateString(),
        'guests' => 4,
        'name' => 'Jan Jansen',
        'email' => 'jan@example.com',
        'terms' => '1',
    ])->assertRedirect(route('booking') . '#confirmation')
        ->assertSessionHas('status');
});

it('validates the booking request', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);

    $this->post('/book', [
        'arrival' => now()->addDays(10)->toDateString(),
        'departure' => now()->addDays(5)->toDateString(),
        'guests' => 9,
        'name' => 'Jan Jansen',
        'email' => 'jan@example.com',
    ])->assertSessionHasErrors(['departure', 'guests', 'terms']);
});

it('fills in the booking form with the selected dates', function () {
    $this->get('/book?arrival=2030-01-10&departure=2030-01-17')
        ->assertOk();
});

it('shows the accommodation details from the database', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);

    $this->get('/the-house')
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

    $this->get('/privacy-terms')
        ->assertOk()
        ->assertSee('De woning biedt plaats aan maximaal 6 personen.')
        ->assertSee('Inchecken kan vanaf 15.00 uur, uitchecken kan tot 11.00 uur.')
        ->assertSee('Huisdieren zijn niet toegestaan.')
        ->assertSee('Ga zuinig om met water en energie.');
});

function cmsText(string $page, string $section, string $field, string $language): ?CmsContent
{
    return CmsPage::byKey($page)->sections->firstWhere('key', $section)->contents
        ->first(fn ($content) => $content->key === $field && $content->language->code === $language);
}

it('shows the texts from the CMS on the homepage', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    cmsText('home', 'hero', 'title', 'nl')->update(['content' => 'Welkom op Curaçao']);
    cmsText('home', 'hero', 'booking_button', 'nl')->update(['content' => 'Reserveer direct']);
    cmsText('home', 'surroundings', 'text', 'nl')->update(['content' => 'Alles ligt dichtbij.']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Welkom op Curaçao')
        ->assertSee('Reserveer direct')
        ->assertSee('Alles ligt dichtbij.')
        ->assertSee('Vakantiewoning op Curaçao');
});

it('shows the texts from the CMS on the property page', function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class, CmsSeeder::class]);

    cmsText('property', 'hero', 'intro', 'nl')->update(['content' => 'Een nieuwe introductie.']);
    cmsText('property', 'amenities', 'title', 'nl')->update(['content' => 'Van alle gemakken voorzien']);

    $this->get('/the-house')
        ->assertOk()
        ->assertSee('Een nieuwe introductie.')
        ->assertSee('Van alle gemakken voorzien')
        ->assertSee('Een rondleiding door de woning')
        ->assertSee('Vaatwasser');
});

it('shows the English texts from the CMS', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    app()->setLocale('en');

    $this->get('/')
        ->assertOk()
        ->assertSee('Holiday home on Curaçao')
        ->assertSee('Book now');

    $this->get('/the-house')
        ->assertOk()
        ->assertSee('A tour of the house');
});

it('falls back to a fixed text when a CMS text is missing', function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);

    cmsText('home', 'hero', 'booking_button', 'en')->delete();
    cmsText('home', 'hero', 'eyebrow', 'en')->delete();
    cmsText('home', 'hero', 'eyebrow', 'nl')->delete();

    app()->setLocale('en');

    $this->get('/')
        ->assertOk()
        ->assertSee('Boek nu')
        ->assertSee('Vakantiewoning op Curaçao');
});
