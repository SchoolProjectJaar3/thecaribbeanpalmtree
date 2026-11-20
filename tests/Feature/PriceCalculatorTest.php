<?php

use App\Models\Accommodation;
use App\Models\Rate;
use App\Models\Season;
use App\Services\PriceCalculator;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->accommodation = Accommodation::create([
        'name' => 'Testwoning',
        'slug' => 'testwoning',
        'max_guests' => 6,
        'bedrooms' => 3,
        'bathrooms' => 2,
    ]);

    $low = Season::create(['name' => 'Laagseizoen']);
    $high = Season::create(['name' => 'Hoogseizoen']);

    $this->accommodation->rates()->createMany([
        ['season_id' => $low->id, 'start_date' => '2030-05-01', 'end_date' => '2030-11-30', 'price_per_night' => 100, 'min_nights' => 3],
        ['season_id' => $high->id, 'start_date' => '2030-12-01', 'end_date' => '2031-04-30', 'price_per_night' => 150, 'min_nights' => 7],
    ]);

    $this->calculator = new PriceCalculator([
        'cleaning' => 80,
        'tourist_tax' => 2,
        'deposit' => 300,
        'discounts' => [
            ['from' => 7, 'percent' => 5],
            ['from' => 14, 'percent' => 10],
        ],
    ]);

    $this->quote = fn (string $arrival, string $departure, int $guests = 2) => $this->calculator->quote(
        $this->accommodation,
        Carbon::parse($arrival),
        Carbon::parse($departure),
        $guests,
    );
});

it('calculates a stay within one season', function () {
    $quote = ($this->quote)('2030-06-10', '2030-06-14');

    expect($quote)
        ->nights->toBe(4)
        ->lines->toBe([['season' => 'Laagseizoen', 'nights' => 4, 'price_per_night' => 100.0, 'amount' => 400.0]])
        ->stay->toBe(400.0)
        ->discount->toBe(0.0)
        ->cleaning->toBe(80.0)
        ->tourist_tax->toBe(16.0)
        ->total->toBe(496.0)
        ->deposit->toBe(300.0)
        ->complete->toBeTrue();
});

it('charges every night the rate of its own season over a season change', function () {
    $quote = ($this->quote)('2030-11-28', '2030-12-03');

    expect($quote)
        ->nights->toBe(5)
        ->lines->toBe([
            ['season' => 'Laagseizoen', 'nights' => 3, 'price_per_night' => 100.0, 'amount' => 300.0],
            ['season' => 'Hoogseizoen', 'nights' => 2, 'price_per_night' => 150.0, 'amount' => 300.0],
        ])
        ->stay->toBe(600.0)
        ->total->toBe(600.0 + 80 + 2 * 2 * 5);
});

it('does not charge the departure day', function () {
    expect(($this->quote)('2030-11-29', '2030-12-01'))
        ->lines->toBe([['season' => 'Laagseizoen', 'nights' => 2, 'price_per_night' => 100.0, 'amount' => 200.0]]);
});

it('uses the minimum number of nights of the arrival date', function () {
    expect(($this->quote)('2030-11-28', '2030-12-03'))
        ->min_nights->toBe(3)
        ->meets_minimum->toBeTrue();

    expect(($this->quote)('2030-12-01', '2030-12-06'))
        ->min_nights->toBe(7)
        ->meets_minimum->toBeFalse();
});

it('applies the highest discount the stay qualifies for', function () {
    expect(($this->quote)('2030-06-01', '2030-06-08'))
        ->discount_percent->toBe(5.0)
        ->discount->toBe(35.0);

    expect(($this->quote)('2030-06-01', '2030-06-16'))
        ->discount_percent->toBe(10.0)
        ->discount->toBe(150.0)
        ->total->toBe(1500.0 - 150 + 80 + 2 * 2 * 15);
});

it('charges tourist tax per person per night', function () {
    expect(($this->quote)('2030-06-01', '2030-06-04', 5))->tourist_tax->toBe(30.0);
});

it('lets a period that starts later override an overlapping period', function () {
    $christmas = Season::create(['name' => 'Kerst']);
    $this->accommodation->rates()->create([
        'season_id' => $christmas->id, 'start_date' => '2030-12-20', 'end_date' => '2031-01-02', 'price_per_night' => 250, 'min_nights' => 7,
    ]);

    expect(($this->quote)('2030-12-18', '2030-12-22'))
        ->lines->toBe([
            ['season' => 'Hoogseizoen', 'nights' => 2, 'price_per_night' => 150.0, 'amount' => 300.0],
            ['season' => 'Kerst', 'nights' => 2, 'price_per_night' => 250.0, 'amount' => 500.0],
        ]);
});

it('reports nights without a rate', function () {
    $quote = ($this->quote)('2031-04-29', '2031-05-03');

    expect($quote)
        ->nights->toBe(4)
        ->unpriced_nights->toBe(2)
        ->stay->toBe(300.0)
        ->complete->toBeFalse();
});

it('only uses the rates of the given accommodation', function () {
    $other = Accommodation::create(['name' => 'Ander huis', 'slug' => 'ander-huis', 'max_guests' => 2, 'bedrooms' => 1, 'bathrooms' => 1]);
    $other->rates()->create([
        'season_id' => Season::first()->id, 'start_date' => '2030-06-01', 'end_date' => '2030-06-30', 'price_per_night' => 999, 'min_nights' => 1,
    ]);

    expect(($this->quote)('2030-06-10', '2030-06-11'))->stay->toBe(100.0);
});

it('returns nothing when the departure is not after the arrival', function () {
    expect(($this->quote)('2030-06-10', '2030-06-10'))->toBeNull()
        ->and(($this->quote)('2030-06-10', '2030-06-08'))->toBeNull();
});
