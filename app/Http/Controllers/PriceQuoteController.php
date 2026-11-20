<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Services\PriceCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PriceQuoteController extends Controller
{
    public function __invoke(Request $request, PriceCalculator $calculator): JsonResponse
    {
        $accommodation = Accommodation::current();

        $data = $request->validate([
            'arrival' => ['required', 'date_format:Y-m-d'],
            'departure' => ['required', 'date_format:Y-m-d', 'after:arrival'],
            'guests' => ['required', 'integer', 'min:1', ...($accommodation ? ['max:' . $accommodation->max_guests] : [])],
        ]);

        return response()->json([
            'quote' => $accommodation === null ? null : $calculator->quote(
                $accommodation,
                Carbon::parse($data['arrival']),
                Carbon::parse($data['departure']),
                (int) $data['guests'],
            ),
        ]);
    }
}
