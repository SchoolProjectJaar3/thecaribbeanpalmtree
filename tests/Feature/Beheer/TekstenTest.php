<?php

use App\Models\CmsContent;
use App\Models\CmsPage;
use App\Models\CmsTranslation;
use App\Models\User;
use Database\Seeders\CmsSeeder;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed([LanguageSeeder::class, CmsSeeder::class]);
});

function tekst(string $pagina, string $sectie, string $veld, string $taal): ?CmsContent
{
    return CmsContent::where('key', $veld)
        ->whereHas('section', fn ($query) => $query->where('key', $sectie)->whereHas('page', fn ($query) => $query->where('key', $pagina)))
        ->whereHas('language', fn ($query) => $query->where('code', $taal))
        ->first();
}

/**
 * De huidige teksten van een pagina, zoals het formulier ze verstuurt.
 *
 * @return array{pagina: array<string, array<string, ?string>>, teksten: array<string, array<string, array<string, ?string>>>}
 */
function formulier(string $pagina): array
{
    $page = CmsPage::byKey($pagina);
    $data = [];

    foreach ($page->translations as $vertaling) {
        foreach (['title', 'meta_title', 'meta_description'] as $veld) {
            $data['pagina'][$veld][$vertaling->language->code] = $vertaling->{$veld};
        }
    }

    foreach ($page->sections as $sectie) {
        foreach ($sectie->contents as $content) {
            $data['teksten'][$sectie->key][$content->key][$content->language->code] = $content->content;
        }
    }

    return $data;
}

/**
 * De sleutels die een sectie volgens config/cms.php heeft.
 *
 * @return list<string>
 */
function verwachteVelden(array $sectie): array
{
    $velden = array_keys($sectie['velden'] ?? []);

    for ($nummer = 1; $nummer <= ($sectie['items']['aantal'] ?? 0); $nummer++) {
        foreach (array_keys($sectie['items']['velden']) as $veld) {
            $velden[] = "item_{$nummer}_{$veld}";
        }
    }

    sort($velden);

    return $velden;
}

it('stuurt gasten door naar de login', function () {
    $this->get(route('beheer.teksten'))->assertRedirect(route('login'));
    $this->get(route('beheer.teksten.edit', 'home'))->assertRedirect(route('login'));
    $this->put(route('beheer.teksten.update', 'home'))->assertRedirect(route('login'));
    $this->get(route('beheer.teksten.algemeen'))->assertRedirect(route('login'));
    $this->put(route('beheer.teksten.algemeen.update'))->assertRedirect(route('login'));
});

it('toont een overzicht van alle pagina\'s en de algemene teksten', function () {
    $response = $this->actingAs(User::factory()->create())
        ->get(route('beheer.teksten'))
        ->assertOk()
        ->assertSee('Algemene teksten')
        ->assertSee(route('beheer.teksten.algemeen'));

    foreach (array_keys(config('cms.paginas')) as $pagina) {
        $response->assertSee(route('beheer.teksten.edit', $pagina));
    }

    $response->assertSee('Het huis')
        ->assertSee('Privacy en voorwaarden')
        ->assertSee('Volledig vertaald');
});

it('toont het aantal ontbrekende vertalingen in het overzicht', function () {
    tekst('home', 'hero', 'button', 'en')->delete();

    $this->actingAs(User::factory()->create())
        ->get(route('beheer.teksten'))
        ->assertSee('1 tekst ontbreekt');
});

it('toont alle teksten van een pagina in beide talen', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('beheer.teksten.edit', 'home'))
        ->assertOk()
        ->assertSee('Teksten: Home')
        ->assertSee('Bekijk beschikbaarheid')
        ->assertSee('Check availability')
        ->assertSee('Plek 4')
        ->assertSee('name="teksten[hero][intro][en]"', false)
        ->assertSee(route('home'));
});

it('geeft een 404 voor een onbekende pagina', function () {
    $this->actingAs(User::factory()->create())
        ->get('/beheer/teksten/bestaat-niet')
        ->assertNotFound();
});

it('slaat een aangepaste tekst op bij de juiste pagina, sectie en taal', function () {
    $user = User::factory()->create();
    $data = formulier('home');
    $data['teksten']['hero']['button']['nl'] = 'Boek jouw verblijf';

    $this->actingAs($user)
        ->put(route('beheer.teksten.update', 'home'), $data)
        ->assertRedirect(route('beheer.teksten.edit', 'home'))
        ->assertSessionHas('status', 'De teksten van Home zijn opgeslagen.');

    expect(tekst('home', 'hero', 'button', 'nl'))
        ->content->toBe('Boek jouw verblijf')
        ->updated_by->toBe($user->id)
        ->and(tekst('home', 'hero', 'button', 'en'))
        ->content->toBe('Check availability')
        ->updated_by->toBeNull()
        ->and(tekst('property', 'description', 'button', 'nl')->content)->toBe('Bekijk beschikbaarheid')
        ->and(CmsPage::firstWhere('key', 'home')->updated_by)->toBe($user->id);
});

it('meldt het als er niets is gewijzigd', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'home'), formulier('home'))
        ->assertSessionHas('status', 'Er was niets gewijzigd.');

    expect(CmsContent::whereNotNull('updated_by')->exists())->toBeFalse()
        ->and(CmsPage::firstWhere('key', 'home')->updated_by)->toBeNull();
});

it('verplicht de tekst in de standaardtaal', function () {
    $data = formulier('home');
    $data['teksten']['hero']['button']['nl'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'home'), $data)
        ->assertSessionHasErrors('teksten.hero.button.nl');

    expect(tekst('home', 'hero', 'button', 'nl')->content)->toBe('Bekijk beschikbaarheid');
});

it('weigert een te lange tekst', function () {
    $data = formulier('home');
    $data['teksten']['hero']['intro']['en'] = str_repeat('a', 5001);

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'home'), $data)
        ->assertSessionHasErrors('teksten.hero.intro.en');
});

it('valt terug op de standaardtaal als een vertaling leeg wordt gelaten', function () {
    $data = formulier('home');
    $data['teksten']['hero']['button']['en'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'home'), $data)
        ->assertSessionHasNoErrors();

    expect(tekst('home', 'hero', 'button', 'en'))->toBeNull()
        ->and(CmsPage::byKey('home')->text('hero', 'button', code: 'en'))->toBe('Bekijk beschikbaarheid');
});

it('slaat de naam en de zoekmachineteksten van de pagina op', function () {
    $data = formulier('property');
    $data['pagina']['meta_title']['en'] = 'The house on Curaçao';

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'property'), $data)
        ->assertSessionHasNoErrors();

    expect(CmsPage::byKey('property')->translation('en')->meta_title)->toBe('The house on Curaçao');
});

it('weigert een te lange zoekmachinetitel', function () {
    $data = formulier('property');
    $data['pagina']['meta_title']['nl'] = str_repeat('a', 71);

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update', 'property'), $data)
        ->assertSessionHasErrors('pagina.meta_title.nl');
});

it('haalt teksten per taal op, met ingevulde placeholders en terugval op de standaardtaal', function () {
    $page = CmsPage::byKey('rates');

    expect($page->text('discounts', 'percentage', ['procent' => 10], 'nl'))->toBe('10% korting')
        ->and($page->text('discounts', 'percentage', ['procent' => 10], 'en'))->toBe('10% discount')
        ->and($page->text('discounts', 'title', code: 'de'))->toBe('Langer blijven loont')
        ->and($page->text('discounts', 'bestaat_niet'))->toBeNull()
        ->and($page->translation('en')->title)->toBe('Rates');
});

it('toont en bewaart de algemene teksten', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('beheer.teksten.algemeen'))
        ->assertOk()
        ->assertSee('Snelle Links')
        ->assertSee('Quick links');

    $data = ['teksten' => []];

    foreach (CmsTranslation::with('language')->get() as $vertaling) {
        [$groep, $veld] = explode('.', $vertaling->key, 2);
        $data['teksten'][$groep][$veld][$vertaling->language->code] = $vertaling->value;
    }

    $data['teksten']['footer']['links_title']['nl'] = 'Snelle links';
    $data['teksten']['footer']['links_title']['en'] = '';

    $this->actingAs($user)
        ->put(route('beheer.teksten.algemeen.update'), $data)
        ->assertRedirect(route('beheer.teksten.algemeen'))
        ->assertSessionHas('status', 'De algemene teksten zijn opgeslagen.');

    expect(CmsTranslation::texts('nl')['footer.links_title'])->toBe('Snelle links')
        ->and(CmsTranslation::texts('en')['footer.links_title'])->toBe('Snelle links')
        ->and(CmsTranslation::texts('en')['footer.terms_link'])->toBe('Terms and conditions');
});

it('vult elke tekst uit de configuratie in het Nederlands en het Engels', function () {
    foreach (config('cms.paginas') as $pagina => $definitie) {
        $page = CmsPage::byKey($pagina);

        expect($page->translations->pluck('language.code')->sort()->values()->all())->toBe(['en', 'nl']);

        foreach ($definitie['secties'] as $sectie => $velden) {
            foreach (['nl', 'en'] as $taal) {
                $opgeslagen = $page->sections->firstWhere('key', $sectie)->contents
                    ->filter(fn (CmsContent $content) => $content->language->code === $taal && filled($content->content))
                    ->pluck('key')
                    ->sort()
                    ->values()
                    ->all();

                expect($opgeslagen)->toBe(verwachteVelden($velden), "{$pagina}.{$sectie} ({$taal})");
            }
        }
    }

    foreach (['nl', 'en'] as $taal) {
        $verwacht = collect(config('cms.algemeen'))
            ->flatMap(fn (array $groep, string $sleutel) => array_map(fn (string $veld) => "{$sleutel}.{$veld}", verwachteVelden($groep)))
            ->sort()
            ->values()
            ->all();

        expect(CmsTranslation::texts($taal)->keys()->sort()->values()->all())->toBe($verwacht);
    }
});

it('overschrijft aangepaste teksten niet als de seeder opnieuw draait', function () {
    tekst('home', 'hero', 'title', 'nl')->update(['content' => 'Welkom op Curaçao']);

    $this->seed(CmsSeeder::class);

    expect(tekst('home', 'hero', 'title', 'nl')->content)->toBe('Welkom op Curaçao')
        ->and(CmsContent::count())->toBe(728);
});
