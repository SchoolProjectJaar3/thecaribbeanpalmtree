<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReservationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

        return view('admin.dashboard', [
            'accommodation' => DB::table('accommodations')->where('is_active', true)->orderBy('id')->first(),
            'openRequests' => DB::table('reservations')->where('status', ReservationStatus::Request)->count(),
            'arrivals' => DB::table('reservations')
                ->whereIn('status', ReservationStatus::booked())
                ->whereBetween('arrival_date', [$today->toDateString(), $today->copy()->addDays(30)->toDateString()])
                ->count(),
            'newMessages' => DB::table('contact_messages')->where('status', 'new')->count(),
            'occupancy' => $this->occupancy($today->copy()->startOfMonth(), $today->copy()->endOfMonth()->startOfDay()),
            'upcoming' => $this->reservations(fn (Builder $query) => $query
                ->whereIn('reservations.status', ReservationStatus::booked())
                ->where('reservations.departure_date', '>', $today->toDateString())
                ->orderBy('reservations.arrival_date')),
            'bookingRequests' => $this->reservations(fn (Builder $query) => $query
                ->where('reservations.status', ReservationStatus::Request)
                ->orderByDesc('reservations.created_at')),
        ]);
    }

    /**
     * The first five reservations with the name of the guest.
     */
    private function reservations(callable $filter): Collection
    {
        $query = DB::table('reservations')
            ->join('guests', 'guests.id', '=', 'reservations.guest_id')
            ->select('reservations.*', 'guests.first_name', 'guests.infix', 'guests.last_name')
            ->limit(5);

        return $filter($query)->get()->map(function (object $reservation) {
            $reservation->guest = collect([$reservation->first_name, $reservation->infix, $reservation->last_name])
                ->filter()
                ->implode(' ');
            $reservation->arrival_date = Carbon::parse($reservation->arrival_date);
            $reservation->departure_date = Carbon::parse($reservation->departure_date);
            $reservation->created_at = Carbon::parse($reservation->created_at);
            $reservation->status = ReservationStatus::from($reservation->status);

            return $reservation;
        });
    }

    /**
     * Percentage of the nights between $start and $end on which the
     * accommodation is occupied by a booked reservation.
     */
    private function occupancy(Carbon $start, Carbon $end): int
    {
        $nights = $start->diffInDays($end) + 1;

        $occupied = DB::table('reservations')
            ->whereIn('status', ReservationStatus::booked())
            ->where('arrival_date', '<=', $end->toDateString())
            ->where('departure_date', '>', $start->toDateString())
            ->get(['arrival_date', 'departure_date'])
            ->sum(fn (object $reservation) => Carbon::parse($reservation->arrival_date)->max($start)
                ->diffInDays(Carbon::parse($reservation->departure_date)->min($end->copy()->addDay())));

        return (int) round(min($occupied, $nights) / $nights * 100);
    }
}
