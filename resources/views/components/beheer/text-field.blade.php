@props(['naam', 'veld', 'label', 'talen', 'waarden' => [], 'hint' => null, 'max' => null, 'alleTalenVerplicht' => false, 'verplicht' => true, 'meerdereRegels' => null])

@php
$sleutel = str_replace(['[', ']'], ['.', ''], $naam);
$meerdereRegels ??= \Illuminate\Support\Str::is(['intro', 'text', '*_intro', '*_text'], $veld);
$langste = collect($waarden)->sortByDesc(fn ($waarde) => mb_strlen((string) $waarde))->first() ?? '';
$regels = max(3, min(12, (int) ceil(mb_strlen($langste) / 55) + substr_count($langste, "\n")));
@endphp

<div class="px-5 py-5">
    <p class="text-sm font-semibold text-deep-blue">{{ $label }}</p>
    @if ($hint)
    <p class="mt-0.5 text-xs text-deep-blue/60">{{ $hint }}</p>
    @endif

    <div class="mt-3 grid gap-4 md:grid-cols-2">
        @foreach ($talen as $taal)
        @php
        $invoer = "{$sleutel}.{$taal->code}";
        $id = \Illuminate\Support\Str::slug(str_replace('.', '-', $invoer));
        $waarde = old($invoer, $waarden[$taal->code] ?? null);
        $isVerplicht = $alleTalenVerplicht || ($verplicht && $taal->is_default);
        $fout = $errors->has($invoer);
        $klasse = 'mt-1 block w-full rounded-lg bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise ' . ($fout ? 'border-coral' : 'border-deep-blue/20');
        @endphp
        <div>
            <label for="{{ $id }}" class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-deep-blue/60">
                {{ $taal->name }}
                @if (! $isVerplicht && ! $taal->is_default && blank($waarden[$taal->code] ?? null))
                <span class="rounded-full bg-sand/30 px-2 py-0.5 text-[0.65rem] normal-case tracking-normal text-deep-blue">Nog niet vertaald</span>
                @endif
            </label>

            @if ($meerdereRegels)
            <textarea
                id="{{ $id }}"
                name="{{ $naam }}[{{ $taal->code }}]"
                rows="{{ $regels }}"
                lang="{{ $taal->code }}"
                @if ($max) maxlength="{{ $max }}" @endif
                @required($isVerplicht)
                class="{{ $klasse }}">{{ $waarde }}</textarea>
            @else
            <input
                id="{{ $id }}"
                type="text"
                name="{{ $naam }}[{{ $taal->code }}]"
                value="{{ $waarde }}"
                lang="{{ $taal->code }}"
                @if ($max) maxlength="{{ $max }}" @endif
                @required($isVerplicht)
                class="{{ $klasse }}">
            @endif

            <x-input-error :messages="$errors->get($invoer)" class="mt-2" />
        </div>
        @endforeach
    </div>
</div>
