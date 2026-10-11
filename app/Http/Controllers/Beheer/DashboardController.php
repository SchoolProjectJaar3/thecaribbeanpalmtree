<?php

namespace App\Http\Controllers\Beheer;

use App\Enums\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\ContactMessage;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $vandaag = Carbon::today();

        return view('beheer.dashboard', [
            'woning' => Accommodation::active()->orderBy('id')->first(),
            'openAanvragen' => Reservation::where('status', ReservationStatus::Request)->count(),
            'aankomsten' => Reservation::booked()
                ->whereBetween('arrival_date', [$vandaag->toDateString(), $vandaag->copy()->addDays(30)->toDateString()])
                ->count(),
            'nieuweBerichten' => ContactMessage::where('status', 'new')->count(),
            'bezetting' => $this->bezetting($vandaag->copy()->startOfMonth(), $vandaag->copy()->endOfMonth()->startOfDay()),
            'eerstvolgende' => Reservation::booked()
                ->with('guest')
                ->where('departure_date', '>', $vandaag->toDateString())
                ->orderBy('arrival_date')
                ->limit(5)
                ->get(),
            'aanvragen' => Reservation::where('status', ReservationStatus::Request)
                ->with('guest')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }

    /**
     * Percentage van de nachten tussen $start en $eind waarop de woning
     * door een geboekte reservering bezet is.
     */
    private function bezetting(Carbon $start, Carbon $eind): int
    {
        $nachten = $start->diffInDays($eind) + 1;

        $bezet = Reservation::booked()
            ->where('arrival_date', '<=', $eind->toDateString())
            ->where('departure_date', '>', $start->toDateString())
            ->get(['arrival_date', 'departure_date'])
            ->sum(fn (Reservation $reservering) => $reservering->arrival_date->max($start)
                ->diffInDays($reservering->departure_date->min($eind->copy()->addDay())));

        return (int) round(min($bezet, $nachten) / $nachten * 100);
    }
}
