<?php

use App\Enums\ReservationStatus;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

dataset('admin pages', [
    'dashboard' => ['admin.dashboard', 'Dashboard'],
    'reservations' => ['admin.reservations', 'Reserveringen'],
    'availability' => ['admin.availability', 'Beschikbaarheid'],
    'rates' => ['admin.rates', 'Tarieven'],
    'photos' => ['admin.photos', 'Foto&#039;s'],
    'texts' => ['admin.texts', 'Teksten'],
    'accommodation' => ['admin.accommodation', 'Woninggegevens'],
]);

function reservation(int $accommodation, string $arrival, string $departure, ReservationStatus $status): void
{
    static $sequence = 0;

    $guest = DB::table('guests')->insertGetId([
        'first_name' => 'Jan',
        'last_name' => 'Jansen',
        'email' => 'jan' . ++$sequence . '@example.com',
    ]);

    DB::table('reservations')->insert([
        'reservation_number' => 'R2026-' . str_pad($sequence, 4, '0', STR_PAD_LEFT),
        'accommodation_id' => $accommodation,
        'guest_id' => $guest,
        'arrival_date' => $arrival,
        'departure_date' => $departure,
        'adults' => 2,
        'status' => $status->value,
        'total_amount' => 1000,
    ]);
}

it('redirects guests to the login page', function (string $route) {
    $this->get(route($route))->assertRedirect(route('login'));
})->with('admin pages');

it('shows the admin page with the navigation', function (string $route, string $title) {
    $this->actingAs(User::factory()->create())
        ->get(route($route))
        ->assertOk()
        ->assertSee($title, false)
        ->assertSee('Beheernavigatie')
        ->assertSee(route('admin.reservations'))
        ->assertSee(route('admin.accommodation'));
})->with('admin pages');

it('highlights the active menu item', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.rates'))
        ->assertSeeInOrder(['aria-current="page"', 'Tarieven'], false);
});

it('shows the data from the database on the dashboard', function () {
    Carbon::setTestNow('2026-10-11');

    $accommodation = DB::table('accommodations')->insertGetId([
        'name' => 'The Caribbean Palm Tree',
        'slug' => 'the-caribbean-palm-tree',
        'max_guests' => 6,
        'bedrooms' => 3,
        'bathrooms' => 2,
        'city' => 'Willemstad',
    ]);

    reservation($accommodation, '2026-10-20', '2026-10-27', ReservationStatus::Confirmed);
    reservation($accommodation, '2026-10-29', '2026-11-05', ReservationStatus::Paid);
    reservation($accommodation, '2026-12-01', '2026-12-08', ReservationStatus::Request);
    reservation($accommodation, '2026-10-01', '2026-10-05', ReservationStatus::Cancelled);

    DB::table('contact_messages')->insert(['name' => 'Piet', 'email' => 'piet@example.com', 'message' => 'Hallo']);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('openRequests', 1)
        ->assertViewHas('arrivals', 2)
        ->assertViewHas('newMessages', 1)
        ->assertViewHas('occupancy', 32)
        ->assertSee('R2026-0001')
        ->assertSee('Jan Jansen')
        ->assertSee('3 slaapkamers, 2 badkamers');
});

it('shows an empty state when there is no data yet', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('occupancy', 0)
        ->assertSee('Er is nog geen woning vastgelegd.');
});
