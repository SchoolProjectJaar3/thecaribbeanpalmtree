<?php

namespace App\Services;

use App\Models\Accommodation;
use App\Models\Rate;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

/**
 * Price of a stay, used by the rates page, the availability calendar and the
 * booking form. Every night gets the rate of the period it falls in, so a stay
 * that runs over two seasons is charged per night.
 *
 * Cleaning costs, tourist tax, deposit and discounts are not in the database
 * yet and still come from config/site.php.
 */
class PriceCalculator
{
    /**
     * @param  array{cleaning: int|float, tourist_tax: int|float, deposit: int|float, discounts: list<array{from: int, percent: int|float}>}  $extras
     */
    public function __construct(private readonly array $extras) {}

    /**
     * Null when the departure is not after the arrival.
     *
     * @return array{nights: int, lines: list<array{season: string, nights: int, price_per_night: float, amount: float}>, unpriced_nights: int, stay: float, discount_percent: float, discount: float, cleaning: float, tourist_tax: float, total: float, deposit: float, min_nights: int|null, meets_minimum: bool, complete: bool}|null
     */
    public function quote(Accommodation $accommodation, CarbonInterface $arrival, CarbonInterface $departure, int $guests): ?array
    {
        $arrival = CarbonImmutable::parse($arrival->toDateString());
        $departure = CarbonImmutable::parse($departure->toDateString());
        $nights = (int) $arrival->diffInDays($departure);

        if ($nights < 1) {
            return null;
        }

        $rates = $this->ratesBetween($accommodation, $arrival, $departure->subDay());

        $lines = [];
        $unpriced = 0;
        $previous = null;

        for ($night = $arrival; $night->lt($departure); $night = $night->addDay()) {
            $rate = $this->rateOn($rates, $night);

            if ($rate === null) {
                $unpriced++;
                $previous = null;
                continue;
            }

            if ($previous === $rate->id) {
                $lines[count($lines) - 1]['nights']++;
            } else {
                $lines[] = [
                    'season' => $rate->season->name,
                    'nights' => 1,
                    'price_per_night' => (float) $rate->price_per_night,
                ];
            }

            $previous = $rate->id;
        }

        $lines = array_map(fn (array $line) => $line + [
            'amount' => round($line['nights'] * $line['price_per_night'], 2),
        ], $lines);

        $stay = round(array_sum(array_column($lines, 'amount')), 2);
        $discountPercent = $this->discountPercent($nights);
        $discount = round($stay * $discountPercent / 100, 2);
        $cleaning = (float) $this->extras['cleaning'];
        $touristTax = round($this->extras['tourist_tax'] * $guests * $nights, 2);
        $minNights = $this->rateOn($rates, $arrival)?->min_nights;

        return [
            'nights' => $nights,
            'lines' => $lines,
            'unpriced_nights' => $unpriced,
            'stay' => $stay,
            'discount_percent' => $discountPercent,
            'discount' => $discount,
            'cleaning' => $cleaning,
            'tourist_tax' => $touristTax,
            'total' => round($stay - $discount + $cleaning + $touristTax, 2),
            'deposit' => (float) $this->extras['deposit'],
            'min_nights' => $minNights,
            'meets_minimum' => $minNights === null || $nights >= $minNights,
            'complete' => $unpriced === 0,
        ];
    }

    /**
     * @return Collection<int, Rate>
     */
    private function ratesBetween(Accommodation $accommodation, CarbonInterface $from, CarbonInterface $until): Collection
    {
        return $accommodation->rates()
            ->with('season')
            ->whereDate('start_date', '<=', $until)
            ->whereDate('end_date', '>=', $from)
            ->orderByDesc('start_date')
            ->get();
    }

    /**
     * With overlapping periods the one that starts last wins, so a short
     * period (e.g. Christmas) can override a long one.
     *
     * @param  Collection<int, Rate>  $rates
     */
    private function rateOn(Collection $rates, CarbonInterface $date): ?Rate
    {
        return $rates->first(fn (Rate $rate) => $date->between($rate->start_date, $rate->end_date));
    }

    private function discountPercent(int $nights): float
    {
        return (float) (collect($this->extras['discounts'])
            ->where('from', '<=', $nights)
            ->sortByDesc('from')
            ->first()['percent'] ?? 0);
    }
}
