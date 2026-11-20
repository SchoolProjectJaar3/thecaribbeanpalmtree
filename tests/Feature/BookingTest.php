<?php

use App\Enums\ReservationStatus;
use App\Models\Accommodation;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Season;

beforeEach(function () {
    $this->accommodation = Accommodation::create([
        'name' => 'Testwoning',
        'slug' => 'testwoning',
        'max_guests' => 4,
        'bedrooms' => 2,
        'bathrooms' => 1,
        'is_active' => true,
    ]);

    $season = Season::create(['name' => 'Laagseizoen']);

    $this->accommodation->rates()->create([
        'season_id' => $season->id,
        'start_date' => '2030-01-01',
        'end_date' => '2030-12-31',
        'price_per_night' => 100,
        'min_nights' => 3,
    ]);

    // A valid request; each test overrides what it needs.
    $this->book = fn (array $overrides = []) => $this->post('/book', array_merge([
        'arrival' => '2030-06-10',
        'departure' => '2030-06-14',
        'guests' => 2,
        'first_name' => 'Jan',
        'last_name' => 'van der Berg',
        'email' => 'jan@example.com',
        'phone' => '0612345678',
        'comments' => 'Graag een kinderbed.',
        'terms' => '1',
    ], $overrides));

    $this->guest = fn () => Guest::create(['first_name' => 'Piet', 'last_name' => 'Pietersen', 'email' => 'piet@example.com']);
});

it('stores a valid booking request', function () {
    ($this->book)()
        ->assertRedirect(route('booking') . '#confirmation')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', fn (string $status) => str_contains($status, 'R' . now()->year . '-0001'));

    $guest = Guest::sole();
    expect($guest)
        ->first_name->toBe('Jan')
        ->last_name->toBe('van der Berg')
        ->email->toBe('jan@example.com');

    // 4 nights × 100, plus cleaning and tourist tax from config/site.php.
    $rates = config('site.rates');
    $expectedTotal = 400 + $rates['cleaning'] + $rates['tourist_tax'] * 2 * 4;

    expect(Reservation::sole())
        ->reservation_number->toBe('R' . now()->year . '-0001')
        ->guest_id->toBe($guest->id)
        ->accommodation_id->toBe($this->accommodation->id)
        ->status->toBe(ReservationStatus::Request)
        ->adults->toBe(2)
        ->guest_note->toBe('Graag een kinderbed.')
        ->and((float) Reservation::sole()->total_amount)->toBe((float) $expectedTotal);
});

it('numbers reservations in order and reuses a guest with the same email', function () {
    ($this->book)();
    ($this->book)(['arrival' => '2030-07-01', 'departure' => '2030-07-05']);

    expect(Guest::count())->toBe(1)
        ->and(Reservation::orderBy('id')->pluck('reservation_number')->all())
        ->toBe(['R' . now()->year . '-0001', 'R' . now()->year . '-0002']);
});

it('refuses more guests than the accommodation allows', function () {
    ($this->book)(['guests' => 5])->assertSessionHasErrors('guests');

    expect(Reservation::count())->toBe(0);
});

it('refuses a stay shorter than the minimum number of nights', function () {
    ($this->book)(['departure' => '2030-06-12'])->assertSessionHasErrors('departure');

    expect(Reservation::count())->toBe(0)
        ->and(Guest::count())->toBe(0);
});

it('refuses an overlap with a booked reservation', function () {
    $this->accommodation->reservations()->create([
        'reservation_number' => 'R2030-0099',
        'guest_id' => ($this->guest)()->id,
        'arrival_date' => '2030-06-12',
        'departure_date' => '2030-06-20',
        'status' => ReservationStatus::Confirmed,
        'total_amount' => 800,
    ]);

    ($this->book)()->assertSessionHasErrors('arrival');

    expect(Reservation::count())->toBe(1);
});

it('ignores requests and cancelled reservations when checking overlap', function (ReservationStatus $status) {
    $this->accommodation->reservations()->create([
        'reservation_number' => 'R2030-0099',
        'guest_id' => ($this->guest)()->id,
        'arrival_date' => '2030-06-10',
        'departure_date' => '2030-06-14',
        'status' => $status,
        'total_amount' => 400,
    ]);

    ($this->book)()->assertSessionHasNoErrors();
})->with([ReservationStatus::Request, ReservationStatus::Cancelled]);

it('allows arriving on the departure day of another stay', function () {
    $this->accommodation->reservations()->create([
        'reservation_number' => 'R2030-0099',
        'guest_id' => ($this->guest)()->id,
        'arrival_date' => '2030-06-05',
        'departure_date' => '2030-06-10',
        'status' => ReservationStatus::Paid,
        'total_amount' => 500,
    ]);

    ($this->book)()->assertSessionHasNoErrors();
});

it('refuses an overlap with a blocked period', function () {
    $this->accommodation->blockedPeriods()->create([
        'start_date' => '2030-06-13',
        'end_date' => '2030-06-13',
        'reason' => 'maintenance',
    ]);

    ($this->book)()->assertSessionHasErrors('arrival');

    expect(Reservation::count())->toBe(0);
});

it('refuses a period without rates', function () {
    ($this->book)(['arrival' => '2031-02-01', 'departure' => '2031-02-05'])->assertSessionHasErrors('arrival');

    expect(Reservation::count())->toBe(0);
});

it('keeps the spam protection', function () {
    ($this->book)(['website' => 'https://spam.example.com'])->assertSessionHasErrors('website');

    expect(Reservation::count())->toBe(0);
});

it('keeps the rate limit', function () {
    // Six requests per minute are allowed, the seventh is refused.
    foreach (range(1, 6) as $i) {
        ($this->book)(['email' => 'bad'])->assertStatus(302);
    }

    ($this->book)(['email' => 'bad'])->assertStatus(429);
});
