<?php

namespace App\Http\Controllers\Beheer;

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
        $vandaag = Carbon::today();

        return view('beheer.dashboard', [
            'woning' => DB::table('accommodations')->where('is_active', true)->orderBy('id')->first(),
            'openAanvragen' => DB::table('reservations')->where('status', ReservationStatus::Request)->count(),
            'aankomsten' => DB::table('reservations')
                ->whereIn('status', ReservationStatus::booked())
                ->whereBetween('arrival_date', [$vandaag->toDateString(), $vandaag->copy()->addDays(30)->toDateString()])
                ->count(),
            'nieuweBerichten' => DB::table('contact_messages')->where('status', 'new')->count(),
            'bezetting' => $this->bezetting($vandaag->copy()->startOfMonth(), $vandaag->copy()->endOfMonth()->startOfDay()),
            'eerstvolgende' => $this->reserveringen(fn (Builder $query) => $query
                ->whereIn('reservations.status', ReservationStatus::booked())
                ->where('reservations.departure_date', '>', $vandaag->toDateString())
                ->orderBy('reservations.arrival_date')),
            'aanvragen' => $this->reserveringen(fn (Builder $query) => $query
                ->where('reservations.status', ReservationStatus::Request)
                ->orderByDesc('reservations.created_at')),
        ]);
    }

    /**
     * De eerste vijf reserveringen met de naam van de gast.
     */
    private function reserveringen(callable $filter): Collection
    {
        $query = DB::table('reservations')
            ->join('guests', 'guests.id', '=', 'reservations.guest_id')
            ->select('reservations.*', 'guests.first_name', 'guests.infix', 'guests.last_name')
            ->limit(5);

        return $filter($query)->get()->map(function (object $reservering) {
            $reservering->gast = collect([$reservering->first_name, $reservering->infix, $reservering->last_name])
                ->filter()
                ->implode(' ');
            $reservering->arrival_date = Carbon::parse($reservering->arrival_date);
            $reservering->departure_date = Carbon::parse($reservering->departure_date);
            $reservering->created_at = Carbon::parse($reservering->created_at);
            $reservering->status = ReservationStatus::from($reservering->status);

            return $reservering;
        });
    }

    /**
     * Percentage van de nachten tussen $start en $eind waarop de woning
     * door een geboekte reservering bezet is.
     */
    private function bezetting(Carbon $start, Carbon $eind): int
    {
        $nachten = $start->diffInDays($eind) + 1;

        $bezet = DB::table('reservations')
            ->whereIn('status', ReservationStatus::booked())
            ->where('arrival_date', '<=', $eind->toDateString())
            ->where('departure_date', '>', $start->toDateString())
            ->get(['arrival_date', 'departure_date'])
            ->sum(fn (object $reservering) => Carbon::parse($reservering->arrival_date)->max($start)
                ->diffInDays(Carbon::parse($reservering->departure_date)->min($eind->copy()->addDay())));

        return (int) round(min($bezet, $nachten) / $nachten * 100);
    }
}
