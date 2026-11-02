<?php

use App\Models\User;

test('registration screen is not available', function () {
    $this->get('/register')->assertNotFound();
});

test('users cannot register', function () {
    $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});

test('login screen does not link to registration', function () {
    $this->get('/login')->assertDontSee('Registreren');
});
