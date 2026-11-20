<?php

use App\Models\ContactMessage;
use Database\Seeders\LanguageSeeder;

function validContactData(array $overrides = []): array
{
    return array_merge([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '0612345678',
        'message' => 'Is de woning in februari nog vrij?',
    ], $overrides);
}

it('stores a contact message with status new', function () {
    $this->seed(LanguageSeeder::class);

    $this->post(route('contact.send'), validContactData())
        ->assertRedirectContains('/contact')
        ->assertSessionHas('status');

    $message = ContactMessage::sole();

    expect($message->name)->toBe('John Doe')
        ->and($message->email)->toBe('john@example.com')
        ->and($message->phone)->toBe('0612345678')
        ->and($message->message)->toBe('Is de woning in februari nog vrij?')
        ->and($message->status)->toBe('new')
        ->and($message->ip_address)->toBeNull()
        ->and($message->language->code)->toBe('nl');
});

it('stores the language of the current locale', function () {
    $this->seed(LanguageSeeder::class);
    app()->setLocale('en');

    $this->post(route('contact.send'), validContactData());

    expect(ContactMessage::sole()->language->code)->toBe('en');
});

it('still stores the message when the language is unknown', function () {
    $this->post(route('contact.send'), validContactData());

    expect(ContactMessage::sole()->language_id)->toBeNull();
});

it('stores the message without a phone number', function () {
    $this->post(route('contact.send'), validContactData(['phone' => '']));

    expect(ContactMessage::sole()->phone)->toBeNull();
});

it('does not store an invalid contact message', function () {
    $this->post(route('contact.send'), [])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    $this->assertDatabaseCount('contact_messages', 0);
});

it('does not store a phone number that is longer than the database allows', function () {
    $this->post(route('contact.send'), validContactData(['phone' => str_repeat('1', 21)]))
        ->assertSessionHasErrors('phone');

    $this->assertDatabaseCount('contact_messages', 0);
});

it('does not store a message when the spam field is filled in', function () {
    $this->post(route('contact.send'), validContactData(['website' => 'https://spam.example']))
        ->assertSessionHasErrors('website');

    $this->assertDatabaseCount('contact_messages', 0);
});

it('limits the number of contact messages per minute', function () {
    foreach (range(1, 6) as $attempt) {
        $this->post(route('contact.send'), validContactData())->assertRedirectContains('/contact');
    }

    $this->post(route('contact.send'), validContactData())->assertStatus(429);

    expect(ContactMessage::count())->toBe(6);
});
