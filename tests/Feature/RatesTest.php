<?php

use App\Models\Accommodation;
use App\Models\Season;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\LanguageSeeder;
use Illuminate\Support\Carbon;

function lastLowNight(): Carbon
{
    return today()->addDays(60);
}

beforeEach(function () {
    $this->seed([LanguageSeeder::class, AccommodationSeeder::class]);

    $accommodation = Accommodation::current();
    $low = Season::create(['name' => 'Laagseizoen']);
    $high = Season::create(['name' => 'Hoogseizoen']);

    $accommodation->rates()->createMany([
        ['season_id' => $low->id, 'start_date' => '2020-05-01', 'end_date' => '2020-11-30', 'price_per_night' => 111, 'min_nights' => 2],
        ['season_id' => $low->id, 'start_date' => today()->subMonth()->toDateString(), 'end_date' => lastLowNight()->toDateString(), 'price_per_night' => 137, 'min_nights' => 4],
        ['season_id' => $high->id, 'start_date' => lastLowNight()->addDay()->toDateString(), 'end_date' => today()->addMonths(6)->toDateString(), 'price_per_night' => 213, 'min_nights' => 8],
    ]);
});

it('shows the current and coming rates from the database', function () {
    $this->get('/rates')
        ->assertOk()
        ->assertSee('€ 137')
        ->assertSee('Minimaal 4 nachten')
        ->assertSee('€ 213')
        ->assertSee('Minimaal 8 nachten')
        ->assertDontSee('€ 111');
});

it('shows the minimum stays from the database on the availability page', function () {
    $this->get('/availability')
        ->assertOk()
        ->assertSee('4 nachten')
        ->assertSee('8 nachten');
});

it('shows a message when there are no rates', function () {
    Accommodation::current()->rates()->delete();

    $this->get('/rates')
        ->assertOk()
        ->assertSee('worden binnenkort bekendgemaakt');
});

it('returns a price estimate over a season change', function () {
    $this->getJson(route('rates.quote', [
        'arrival' => lastLowNight()->subDays(2)->toDateString(),
        'departure' => lastLowNight()->addDays(3)->toDateString(),
        'guests' => 2,
    ]))
        ->assertOk()
        ->assertJsonPath('quote.nights', 5)
        ->assertJsonPath('quote.lines.0.nights', 3)
        ->assertJsonPath('quote.lines.0.price_per_night', 137)
        ->assertJsonPath('quote.lines.1.nights', 2)
        ->assertJsonPath('quote.lines.1.price_per_night', 213)
        ->assertJsonPath('quote.stay', 3 * 137 + 2 * 213)
        ->assertJsonPath('quote.cleaning', config('site.rates.cleaning'));
});

it('validates the price estimate request', function () {
    $this->getJson(route('rates.quote', [
        'arrival' => '2030-06-10',
        'departure' => '2030-06-01',
        'guests' => 99,
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['departure', 'guests']);
});
