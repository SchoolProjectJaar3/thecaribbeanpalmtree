<?php

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
