<?php

use App\Models\User;

it('redirects visitors who are not logged in to the login page', function (string $path) {
    $this->get($path)->assertRedirect(route('login'));
})->with([
    '/admin',
    '/admin/reservations',
    '/admin/availability',
    '/admin/rates',
    '/admin/photos',
    '/admin/texts',
    '/admin/accommodation',
]);

it('gives a logged in admin access to the dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertOk();
});

it('logs out a disabled admin with an active session', function () {
    $user = User::factory()->create(['is_active' => false]);

    $this->actingAs($user)->get('/admin')->assertRedirect(route('login'));

    $this->assertGuest();
});

it('can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
});

it('blocks logging in after 5 failed attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('redirects an admin to the dashboard after logging in', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('admin.dashboard', absolute: false));
});
