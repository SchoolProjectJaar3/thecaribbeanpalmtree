<?php

use App\Models\User;

it('stuurt niet-ingelogde bezoekers door naar de login', function (string $pad) {
    $this->get($pad)->assertRedirect(route('login'));
})->with([
    '/beheer',
    '/beheer/reserveringen',
    '/beheer/beschikbaarheid',
    '/beheer/tarieven',
    '/beheer/fotos',
    '/beheer/teksten',
    '/beheer/woning',
]);

it('geeft een ingelogde beheerder toegang tot het dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/beheer')->assertOk();
});

it('logt een uitgeschakelde beheerder met een lopende sessie uit', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->actingAs($user)->get('/beheer')->assertRedirect(route('login'));

    $this->assertGuest();
});

it('kan uitloggen', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});

it('blokkeert inloggen na 5 foute pogingen', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $poging) {
        $this->post('/login', ['email' => $user->email, 'password' => 'fout-wachtwoord']);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});
