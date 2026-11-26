<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReservationStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\ContactMessage;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

        return view('admin.dashboard', [
            'accommodation' => Accommodation::where('is_active', true)->orderBy('id')->first(),
            'openRequests' => Reservation::where('status', ReservationStatus::Request)->count(),
            'arrivals' => Reservation::whereIn('status', ReservationStatus::booked())
                ->whereBetween('arrival_date', [$today->toDateString(), $today->copy()->addDays(30)->toDateString()])
                ->count(),
            'newMessages' => ContactMessage::where('status', 'new')->count(),
            'occupancy' => $this->occupancy($today->copy()->startOfMonth(), $today->copy()->endOfMonth()->startOfDay()),
            'upcoming' => $this->reservations(fn (Builder $query) => $query
                ->whereIn('status', ReservationStatus::booked())
                ->where('departure_date', '>', $today->toDateString())
                ->orderBy('arrival_date')),
            'bookingRequests' => $this->reservations(fn (Builder $query) => $query
                ->where('status', ReservationStatus::Request)
                ->orderByDesc('created_at')),
        ]);
    }

    /**
     * The first five reservations, with their guest.
     */
    private function reservations(callable $filter): Collection
    {
        return $filter(Reservation::with('guest')->limit(5))->get();
    }

    /**
     * Percentage of the nights between $start and $end on which the
     * accommodation is occupied by a booked reservation.
     */
    private function occupancy(Carbon $start, Carbon $end): int
    {
        $nights = $start->diffInDays($end) + 1;

        $occupied = Reservation::whereIn('status', ReservationStatus::booked())
            ->where('arrival_date', '<=', $end->toDateString())
            ->where('departure_date', '>', $start->toDateString())
            ->get(['arrival_date', 'departure_date'])
            ->sum(fn (Reservation $reservation) => $reservation->arrival_date->max($start)
                ->diffInDays($reservation->departure_date->min($end->copy()->addDay())));

        return (int) round(min($occupied, $nights) / $nights * 100);
    }
}
