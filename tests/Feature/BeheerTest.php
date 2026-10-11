<?php

use App\Enums\ReservationStatus;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

dataset('beheerpaginas', [
    'dashboard' => ['beheer.dashboard', 'Dashboard'],
    'reserveringen' => ['beheer.reserveringen', 'Reserveringen'],
    'beschikbaarheid' => ['beheer.beschikbaarheid', 'Beschikbaarheid'],
    'tarieven' => ['beheer.tarieven', 'Tarieven'],
    'fotos' => ['beheer.fotos', 'Foto&#039;s'],
    'teksten' => ['beheer.teksten', 'Teksten'],
    'woninggegevens' => ['beheer.woning', 'Woninggegevens'],
]);

function reservering(int $woning, string $aankomst, string $vertrek, ReservationStatus $status): void
{
    static $volgnummer = 0;

    $gast = DB::table('guests')->insertGetId([
        'first_name' => 'Jan',
        'last_name' => 'Jansen',
        'email' => 'jan' . ++$volgnummer . '@example.com',
    ]);

    DB::table('reservations')->insert([
        'reservation_number' => 'R2026-' . str_pad($volgnummer, 4, '0', STR_PAD_LEFT),
        'accommodation_id' => $woning,
        'guest_id' => $gast,
        'arrival_date' => $aankomst,
        'departure_date' => $vertrek,
        'adults' => 2,
        'status' => $status->value,
        'total_amount' => 1000,
    ]);
}

it('stuurt gasten door naar de inlogpagina', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with('beheerpaginas');

it('toont de beheerpagina met de navigatie', function (string $route, string $titel) {
    $this->actingAs(User::factory()->create())
        ->get(route($route))
        ->assertOk()
        ->assertSee($titel, false)
        ->assertSee('Beheernavigatie')
        ->assertSee(route('beheer.reserveringen'))
        ->assertSee(route('beheer.woning'));
})->with('beheerpaginas');

it('markeert het actieve menu-item', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('beheer.tarieven'))
        ->assertSeeInOrder(['aria-current="page"', 'Tarieven'], false);
});

it('toont de gegevens uit de database op het dashboard', function () {
    Carbon::setTestNow('2026-10-11');

    $woning = DB::table('accommodations')->insertGetId([
        'name' => 'The Caribbean Palm Tree',
        'slug' => 'the-caribbean-palm-tree',
        'max_guests' => 6,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'city' => 'Willemstad',
    ]);

    reservering($woning, '2026-10-20', '2026-10-27', ReservationStatus::Confirmed);
    reservering($woning, '2026-10-29', '2026-11-05', ReservationStatus::Paid);
    reservering($woning, '2026-12-01', '2026-12-08', ReservationStatus::Request);
    reservering($woning, '2026-10-01', '2026-10-05', ReservationStatus::Cancelled);

    DB::table('contact_messages')->insert(['name' => 'Piet', 'email' => 'piet@example.com', 'message' => 'Hallo']);

    $this->actingAs(User::factory()->create())
        ->get(route('beheer.dashboard'))
        ->assertOk()
        ->assertViewHas('openAanvragen', 1)
        ->assertViewHas('aankomsten', 2)
        ->assertViewHas('nieuweBerichten', 1)
        ->assertViewHas('bezetting', 32)
        ->assertSee('R2026-0001')
        ->assertSee('Jan Jansen')
        ->assertSee('3 slaapkamers, 2 badkamers');
});

it('toont een lege staat als er nog geen gegevens zijn', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('beheer.dashboard'))
        ->assertOk()
        ->assertViewHas('bezetting', 0)
        ->assertSee('Er is nog geen woning vastgelegd.');
});
