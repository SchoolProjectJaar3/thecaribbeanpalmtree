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

function pageText(string $page, string $section, string $field, string $language): ?CmsContent
{
    return CmsContent::where('key', $field)
        ->whereHas('section', fn ($query) => $query->where('key', $section)->whereHas('page', fn ($query) => $query->where('key', $page)))
        ->whereHas('language', fn ($query) => $query->where('code', $language))
        ->first();
}

/**
 * The current texts of a page, as the form submits them.
 *
 * @return array{page: array<string, array<string, ?string>>, texts: array<string, array<string, array<string, ?string>>>}
 */
function textForm(string $key): array
{
    $page = CmsPage::byKey($key);
    $data = [];

    foreach ($page->translations as $translation) {
        foreach (['title', 'meta_title', 'meta_description'] as $field) {
            $data['page'][$field][$translation->language->code] = $translation->{$field};
        }
    }

    foreach ($page->sections as $section) {
        foreach ($section->contents as $content) {
            $data['texts'][$section->key][$content->key][$content->language->code] = $content->content;
        }
    }

    return $data;
}

/**
 * The keys a section has according to config/cms.php.
 *
 * @return list<string>
 */
function expectedFields(array $section): array
{
    $fields = array_keys($section['fields'] ?? []);

    for ($number = 1; $number <= ($section['items']['count'] ?? 0); $number++) {
        foreach (array_keys($section['items']['fields']) as $field) {
            $fields[] = "item_{$number}_{$field}";
        }
    }

    sort($fields);

    return $fields;
}

it('redirects guests to the login page', function () {
    $this->get(route('admin.texts'))->assertRedirect(route('login'));
    $this->get(route('admin.texts.edit', 'home'))->assertRedirect(route('login'));
    $this->put(route('admin.texts.update', 'home'))->assertRedirect(route('login'));
    $this->get(route('admin.texts.general'))->assertRedirect(route('login'));
    $this->put(route('admin.texts.general.update'))->assertRedirect(route('login'));
});

it('shows an overview of all pages and the general texts', function () {
    $response = $this->actingAs(User::factory()->create())
        ->get(route('admin.texts'))
        ->assertOk()
        ->assertSee('Algemene teksten')
        ->assertSee(route('admin.texts.general'));

    foreach (array_keys(config('cms.pages')) as $page) {
        $response->assertSee(route('admin.texts.edit', $page));
    }

    $response->assertSee('Het huis')
        ->assertSee('Privacy en voorwaarden')
        ->assertSee('Volledig vertaald');
});

it('shows the number of missing translations in the overview', function () {
    pageText('home', 'hero', 'button', 'en')->delete();

    $this->actingAs(User::factory()->create())
        ->get(route('admin.texts'))
        ->assertSee('1 tekst ontbreekt');
});

it('shows all texts of a page in both languages', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.texts.edit', 'home'))
        ->assertOk()
        ->assertSee('Teksten: Home')
        ->assertSee('Bekijk beschikbaarheid')
        ->assertSee('Check availability')
        ->assertSee('Plek 4')
        ->assertSee('name="texts[hero][intro][en]"', false)
        ->assertSee(route('home'));
});

it('returns a 404 for an unknown page', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/texts/does-not-exist')
        ->assertNotFound();
});

it('saves a changed text for the right page, section and language', function () {
    $user = User::factory()->create();
    $data = textForm('home');
    $data['texts']['hero']['button']['nl'] = 'Boek jouw verblijf';

    $this->actingAs($user)
        ->put(route('admin.texts.update', 'home'), $data)
        ->assertRedirect(route('admin.texts.edit', 'home'))
        ->assertSessionHas('status', 'De teksten van Home zijn opgeslagen.');

    expect(pageText('home', 'hero', 'button', 'nl'))
        ->content->toBe('Boek jouw verblijf')
        ->updated_by->toBe($user->id)
        ->and(pageText('home', 'hero', 'button', 'en'))
        ->content->toBe('Check availability')
        ->updated_by->toBeNull()
        ->and(pageText('property', 'description', 'button', 'nl')->content)->toBe('Bekijk beschikbaarheid')
        ->and(CmsPage::firstWhere('key', 'home')->updated_by)->toBe($user->id);
});

it('reports when nothing was changed', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'home'), textForm('home'))
        ->assertSessionHas('status', 'Er was niets gewijzigd.');

    expect(CmsContent::whereNotNull('updated_by')->exists())->toBeFalse()
        ->and(CmsPage::firstWhere('key', 'home')->updated_by)->toBeNull();
});

it('requires the text in the default language', function () {
    $data = textForm('home');
    $data['texts']['hero']['button']['nl'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'home'), $data)
        ->assertSessionHasErrors('texts.hero.button.nl');

    expect(pageText('home', 'hero', 'button', 'nl')->content)->toBe('Bekijk beschikbaarheid');
});

it('rejects a text that is too long', function () {
    $data = textForm('home');
    $data['texts']['hero']['intro']['en'] = str_repeat('a', 5001);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'home'), $data)
        ->assertSessionHasErrors('texts.hero.intro.en');
});

it('falls back to the default language when a translation is left empty', function () {
    $data = textForm('home');
    $data['texts']['hero']['button']['en'] = '';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'home'), $data)
        ->assertSessionHasNoErrors();

    expect(pageText('home', 'hero', 'button', 'en'))->toBeNull()
        ->and(CmsPage::byKey('home')->text('hero', 'button', code: 'en'))->toBe('Bekijk beschikbaarheid');
});

it('saves the name and the search engine texts of the page', function () {
    $data = textForm('property');
    $data['page']['meta_title']['en'] = 'The house on Curaçao';

    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'property'), $data)
        ->assertSessionHasNoErrors();

    expect(CmsPage::byKey('property')->translation('en')->meta_title)->toBe('The house on Curaçao');
});

it('rejects a search engine title that is too long', function () {
    $data = textForm('property');
    $data['page']['meta_title']['nl'] = str_repeat('a', 71);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.texts.update', 'property'), $data)
        ->assertSessionHasErrors('page.meta_title.nl');
});

it('fetches texts per language, with filled in placeholders and a fallback to the default language', function () {
    $page = CmsPage::byKey('rates');

    expect($page->text('discounts', 'percentage', ['procent' => 10], 'nl'))->toBe('10% korting')
        ->and($page->text('discounts', 'percentage', ['procent' => 10], 'en'))->toBe('10% discount')
        ->and($page->text('discounts', 'title', code: 'de'))->toBe('Langer blijven loont')
        ->and($page->text('discounts', 'does_not_exist'))->toBeNull()
        ->and($page->translation('en')->title)->toBe('Rates');
});

it('shows and saves the general texts', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.texts.general'))
        ->assertOk()
        ->assertSee('Snelle Links')
        ->assertSee('Quick links');

    $data = ['texts' => []];

    foreach (CmsTranslation::with('language')->get() as $translation) {
        [$group, $field] = explode('.', $translation->key, 2);
        $data['texts'][$group][$field][$translation->language->code] = $translation->value;
    }

    $data['texts']['footer']['links_title']['nl'] = 'Snelle links';
    $data['texts']['footer']['links_title']['en'] = '';

    $this->actingAs($user)
        ->put(route('admin.texts.general.update'), $data)
        ->assertRedirect(route('admin.texts.general'))
        ->assertSessionHas('status', 'De algemene teksten zijn opgeslagen.');

    expect(CmsTranslation::texts('nl')['footer.links_title'])->toBe('Snelle links')
        ->and(CmsTranslation::texts('en')['footer.links_title'])->toBe('Snelle links')
        ->and(CmsTranslation::texts('en')['footer.terms_link'])->toBe('Terms and conditions');
});

it('seeds every text from the configuration in Dutch and English', function () {
    foreach (config('cms.pages') as $key => $definition) {
        $page = CmsPage::byKey($key);

        expect($page->translations->pluck('language.code')->sort()->values()->all())->toBe(['en', 'nl']);

        foreach ($definition['sections'] as $sectionKey => $section) {
            foreach (['nl', 'en'] as $language) {
                $stored = $page->sections->firstWhere('key', $sectionKey)->contents
                    ->filter(fn (CmsContent $content) => $content->language->code === $language && filled($content->content))
                    ->pluck('key')
                    ->sort()
                    ->values()
                    ->all();

                expect($stored)->toBe(expectedFields($section), "{$key}.{$sectionKey} ({$language})");
            }
        }
    }

    foreach (['nl', 'en'] as $language) {
        $expected = collect(config('cms.general'))
            ->flatMap(fn (array $group, string $groupKey) => array_map(fn (string $field) => "{$groupKey}.{$field}", expectedFields($group)))
            ->sort()
            ->values()
            ->all();

        expect(CmsTranslation::texts($language)->keys()->sort()->values()->all())->toBe($expected);
    }
});

it('does not overwrite changed texts when the seeder runs again', function () {
    pageText('home', 'hero', 'title', 'nl')->update(['content' => 'Welkom op Curaçao']);

    $this->seed(CmsSeeder::class);

    expect(pageText('home', 'hero', 'title', 'nl')->content)->toBe('Welkom op Curaçao')
        ->and(CmsContent::count())->toBe(728);
});
