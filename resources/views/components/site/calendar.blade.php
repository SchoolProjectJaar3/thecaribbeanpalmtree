@props(['months' => 1, 'interactive' => false])

@php
// Booked days come from the demo configuration (later from the admin area).
$today = \Carbon\Carbon::today();
$start = $today->copy()->startOfMonth();

$booked = [];
foreach (config('site.availability.booked') as [$offset, $days]) {
    for ($i = 0; $i < $days; $i++) {
        $booked[$start->copy()->addDays($offset + $i)->toDateString()] = true;
    }
}

$weekdays = ['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'];
$cellClass = 'flex h-10 w-full items-center justify-center rounded-md text-sm transition';
$statusLabels = ['available' => 'vrij', 'booked' => 'bezet', 'past' => 'verstreken'];
@endphp

<div {{ $attributes->merge(['class' => 'grid gap-6 ' . ($months > 1 ? 'sm:grid-cols-2' : '')]) }}>
    @for ($m = 0; $m < $months; $m++)
    @php
        $month = $start->copy()->addMonthsNoOverflow($m)->locale('nl');
        $blanks = $month->dayOfWeekIso - 1;
    @endphp
    <div class="rounded-xl border border-deep-blue/10 bg-sand-white p-4 shadow-sm sm:p-5">
        <p class="text-center font-semibold capitalize text-deep-blue">{{ $month->translatedFormat('F Y') }}</p>

        <div class="mt-4 grid grid-cols-7 gap-1 text-center text-xs font-medium text-deep-blue/60" aria-hidden="true">
            @foreach ($weekdays as $weekday)
            <span>{{ $weekday }}</span>
            @endforeach
        </div>

        <div class="mt-2 grid grid-cols-7 gap-1 text-center">
            @for ($i = 0; $i < $blanks; $i++)
            <span aria-hidden="true"></span>
            @endfor

            @for ($d = 1; $d <= $month->daysInMonth; $d++)
            @php
                $date = $month->copy()->day($d);
                $iso = $date->toDateString();
                $status = $date->lt($today) ? 'past' : (isset($booked[$iso]) ? 'booked' : 'available');
                $label = $date->translatedFormat('l j F Y') . ' (' . $statusLabels[$status] . ')';
                $statusClass = match ($status) {
                    'past' => 'text-deep-blue/25',
                    'booked' => 'bg-deep-blue/10 text-deep-blue/40 line-through',
                    default => 'bg-turquoise/15 font-medium text-deep-blue',
                };
            @endphp
            @if ($interactive)
            <button
                type="button"
                data-date="{{ $iso }}"
                data-status="{{ $status }}"
                aria-label="{{ $label }}"
                @disabled($status !== 'available')
                @click="select('{{ $iso }}')"
                :data-selection="selection('{{ $iso }}')"
                @class([
                    $cellClass,
                    $statusClass,
                    'hover:bg-turquoise/30 focus:outline-none focus:ring-2 focus:ring-turquoise data-[selection=mid]:bg-turquoise/40 data-[selection=start]:bg-turquoise data-[selection=start]:text-white data-[selection=end]:bg-turquoise data-[selection=end]:text-white' => $status === 'available',
                    'cursor-not-allowed' => $status !== 'available',
                ])>
                {{ $d }}
            </button>
            @else
            <span class="{{ $cellClass }} {{ $statusClass }}">
                {{ $d }}<span class="sr-only"> ({{ $statusLabels[$status] }})</span>
            </span>
            @endif
            @endfor
        </div>
    </div>
    @endfor
</div>
