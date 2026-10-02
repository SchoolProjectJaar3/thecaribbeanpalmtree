@props(['maanden' => 1, 'interactief' => false])

@php
// Bezette dagen komen uit de demo-configuratie (later uit de beheeromgeving).
$vandaag = \Carbon\Carbon::today();
$start = $vandaag->copy()->startOfMonth();

$bezet = [];
foreach (config('woning.beschikbaarheid.bezet') as [$offset, $dagen]) {
    for ($i = 0; $i < $dagen; $i++) {
        $bezet[$start->copy()->addDays($offset + $i)->toDateString()] = true;
    }
}

$weekdagen = ['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'];
$celKlasse = 'flex h-10 w-full items-center justify-center rounded-md text-sm transition';
@endphp

<div {{ $attributes->merge(['class' => 'grid gap-6 ' . ($maanden > 1 ? 'sm:grid-cols-2' : '')]) }}>
    @for ($m = 0; $m < $maanden; $m++)
    @php
        $maand = $start->copy()->addMonthsNoOverflow($m)->locale('nl');
        $leeg = $maand->dayOfWeekIso - 1;
    @endphp
    <div class="rounded-xl border border-deep-blue/10 bg-sand-white p-4 shadow-sm sm:p-5">
        <p class="text-center font-semibold capitalize text-deep-blue">{{ $maand->translatedFormat('F Y') }}</p>

        <div class="mt-4 grid grid-cols-7 gap-1 text-center text-xs font-medium text-deep-blue/60" aria-hidden="true">
            @foreach ($weekdagen as $dag)
            <span>{{ $dag }}</span>
            @endforeach
        </div>

        <div class="mt-2 grid grid-cols-7 gap-1 text-center">
            @for ($i = 0; $i < $leeg; $i++)
            <span aria-hidden="true"></span>
            @endfor

            @for ($d = 1; $d <= $maand->daysInMonth; $d++)
            @php
                $datum = $maand->copy()->day($d);
                $iso = $datum->toDateString();
                $verleden = $datum->lt($vandaag);
                $isBezet = isset($bezet[$iso]);
                $status = $verleden ? 'verleden' : ($isBezet ? 'bezet' : 'vrij');
                $label = $datum->translatedFormat('l j F Y') . ' (' . ($status === 'vrij' ? 'vrij' : ($status === 'bezet' ? 'bezet' : 'verstreken')) . ')';
                $statusKlasse = match ($status) {
                    'verleden' => 'text-deep-blue/25',
                    'bezet' => 'bg-deep-blue/10 text-deep-blue/40 line-through',
                    default => 'bg-turquoise/15 font-medium text-deep-blue',
                };
            @endphp
            @if ($interactief)
            <button
                type="button"
                data-date="{{ $iso }}"
                data-status="{{ $status }}"
                aria-label="{{ $label }}"
                @disabled($status !== 'vrij')
                @click="kies('{{ $iso }}')"
                :data-sel="sel('{{ $iso }}')"
                @class([
                    $celKlasse,
                    $statusKlasse,
                    'hover:bg-turquoise/30 focus:outline-none focus:ring-2 focus:ring-turquoise data-[sel=mid]:bg-turquoise/40 data-[sel=start]:bg-turquoise data-[sel=start]:text-white data-[sel=eind]:bg-turquoise data-[sel=eind]:text-white' => $status === 'vrij',
                    'cursor-not-allowed' => $status !== 'vrij',
                ])>
                {{ $d }}
            </button>
            @else
            <span class="{{ $celKlasse }} {{ $statusKlasse }}">
                {{ $d }}<span class="sr-only"> ({{ $status === 'vrij' ? 'vrij' : ($status === 'bezet' ? 'bezet' : 'verstreken') }})</span>
            </span>
            @endif
            @endfor
        </div>
    </div>
    @endfor
</div>
