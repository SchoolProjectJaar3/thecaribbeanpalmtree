<?php

use App\Models\CmsContent;
use App\Models\CmsPage;
use App\Models\User;
use Database\Seeders\CmsSeeder;
use Database\Seeders\LanguageSeeder;

beforeEach(function () {
    $this->seed(LanguageSeeder::class);
    $this->seed(CmsSeeder::class);
});

function heroButton(string $language): CmsContent
{
    return CmsContent::where('key', 'button_text')
        ->whereHas('language', fn($q) => $q->where('code', $language))
        ->firstOrFail();
}

it('stuurt gasten door naar de login', function () {
    $this->get(route('beheer.teksten'))->assertRedirect(route('login'));
    $this->put(route('beheer.teksten.update'), [])->assertRedirect(route('login'));
});

it('toont de teksten in beide talen', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('beheer.teksten'))
        ->assertOk()
        ->assertSee('Bekijk beschikbaarheid')
        ->assertSee('Check availability');
});

it('haalt teksten per taal op, met terugval op de standaardtaal', function () {
    $page = CmsPage::byKey('home');

    expect($page->text('hero', 'button_text', 'nl'))->toBe('Bekijk beschikbaarheid')
        ->and($page->text('hero', 'button_text', 'en'))->toBe('Check availability')
        ->and($page->text('hero', 'button_text', 'de'))->toBe('Bekijk beschikbaarheid')
        ->and($page->text('hero', 'does_not_exist'))->toBeNull();
});

it('slaat aangepaste teksten op', function () {
    $user = User::factory()->create();
    $button = heroButton('nl');

    $this->actingAs($user)
        ->put(route('beheer.teksten.update'), ['content' => [$button->id => 'Boek jouw verblijf']])
        ->assertRedirect(route('beheer.teksten'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('cms_contents', [
        'id' => $button->id,
        'content' => 'Boek jouw verblijf',
        'updated_by' => $user->id,
    ]);
});

it('weigert een lege tekst', function () {
    $button = heroButton('nl');

    $this->actingAs(User::factory()->create())
        ->put(route('beheer.teksten.update'), ['content' => [$button->id => '']])
        ->assertSessionHasErrors('content.' . $button->id);

    $this->assertDatabaseHas('cms_contents', ['id' => $button->id, 'content' => 'Bekijk beschikbaarheid']);
});
