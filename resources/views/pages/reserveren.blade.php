<!-- reserveren -->
@extends('layouts.app', ['title' => 'Reserveren | The Caribbean Palm Tree'])

@php
$t = config('woning.tarieven');
$begin = [
    'aankomst' => old('aankomst', request('aankomst', '')),
    'vertrek' => old('vertrek', request('vertrek', '')),
    'personen' => old('personen', request('personen', 2)),
];
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Reserveren"
    title="Vraag je verblijf aan"
    intro="Vul je gegevens in en we bekijken je aanvraag. Een online betaling is niet nodig: Wilma keurt je aanvraag goed of af en je ontvangt direct bericht." />


<section class="py-16 sm:py-20" x-data="prijsCalculator(@js($t), @js($begin))">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_24rem] lg:px-8">

        <div id="bevestiging" class="scroll-mt-6">
            @if (session('status'))
            <div class="mb-8 flex items-start gap-3 rounded-xl border border-turquoise/40 bg-turquoise/10 p-5 text-deep-blue" role="status">
                <x-site.icon name="check" class="mt-0.5 h-6 w-6 shrink-0 text-turquoise" />
                <p class="font-medium">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('reserveren.verstuur') }}" class="space-y-6 rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm sm:p-8" novalidate>
                @csrf

                <div class="space-y-5" role="group" aria-labelledby="titel-verblijf">
                    <h2 id="titel-verblijf" class="text-2xl font-bold text-deep-blue">Je verblijf</h2>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="aankomst" class="block text-sm font-semibold text-deep-blue">Aankomst<span class="text-coral" aria-hidden="true"> *</span></label>
                            <input id="aankomst" name="aankomst" type="date" x-model="aankomst" min="{{ now()->toDateString() }}" required class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise @error('aankomst') border-coral @enderror">
                            @error('aankomst')<p class="mt-1 text-sm font-medium text-coral">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="vertrek" class="block text-sm font-semibold text-deep-blue">Vertrek<span class="text-coral" aria-hidden="true"> *</span></label>
                            <input id="vertrek" name="vertrek" type="date" x-model="vertrek" :min="aankomst" required class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise @error('vertrek') border-coral @enderror">
                            @error('vertrek')<p class="mt-1 text-sm font-medium text-coral">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="personen" class="block text-sm font-semibold text-deep-blue">Aantal personen<span class="text-coral" aria-hidden="true"> *</span></label>
                        <select id="personen" name="personen" x-model="personen" required class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise">
                            @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'persoon' : 'personen' }}</option>
                            @endfor
                        </select>
                        @error('personen')<p class="mt-1 text-sm font-medium text-coral">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-deep-blue/60">De woning biedt plaats aan maximaal 6 personen.</p>
                    </div>
                </div>

                <div class="space-y-5 border-t border-deep-blue/10 pt-6" role="group" aria-labelledby="titel-gegevens">
                    <h2 id="titel-gegevens" class="text-2xl font-bold text-deep-blue">Jouw gegevens</h2>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-site.field name="naam" label="Naam" :required="true" autocomplete="name" />
                        <x-site.field name="email" label="E-mailadres" type="email" :required="true" autocomplete="email" />
                    </div>

                    <x-site.field name="telefoon" label="Telefoonnummer" type="tel" autocomplete="tel" hint="Optioneel" />
                    <x-site.field name="opmerkingen" label="Opmerkingen" as="textarea" hint="Bijvoorbeeld een kinderbed, een late aankomst of een speciale gelegenheid." />
                </div>

                <!-- Spambeveiliging: dit veld is voor mensen onzichtbaar en moet leeg blijven -->
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="akkoord" value="1" @checked(old('akkoord')) class="mt-1 h-5 w-5 rounded border-deep-blue/30 text-turquoise focus:ring-turquoise">
                        <span class="text-sm text-deep-blue/80">
                            Ik ga akkoord met de <a href="/privacy-voorwaarden#boekingsvoorwaarden" target="_blank" class="font-semibold text-turquoise hover:text-deep-blue">boekingsvoorwaarden</a>
                            en het <a href="/privacy-voorwaarden#privacy" target="_blank" class="font-semibold text-turquoise hover:text-deep-blue">privacybeleid</a>.
                        </span>
                    </label>
                    @error('akkoord')<p class="mt-1 text-sm font-medium text-coral">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-lg bg-coral px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-coral/90 focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2 sm:w-auto">
                    Aanvraag versturen
                </button>
            </form>
        </div>

        <!-- Prijsindicatie en uitleg -->
        <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            <div class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm" aria-live="polite">
                <h2 class="text-xl font-semibold text-deep-blue">Prijsindicatie</h2>

                <p class="mt-4 text-sm text-deep-blue/60" x-show="!resultaat">Vul je aankomst- en vertrekdatum in om de prijs te zien.</p>

                <template x-if="resultaat">
                    <div>
                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between gap-4">
                                <dt class="text-deep-blue/70" x-text="resultaat.nachten + (resultaat.nachten === 1 ? ' nacht' : ' nachten')"></dt>
                                <dd class="font-medium text-deep-blue" x-text="euro(resultaat.verblijf)"></dd>
                            </div>
                            <div class="flex justify-between gap-4" x-show="resultaat.korting > 0">
                                <dt class="text-deep-blue/70" x-text="'Korting (' + resultaat.kortingProcent + '%)'"></dt>
                                <dd class="font-medium text-deep-blue" x-text="'- ' + euro(resultaat.korting)"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-deep-blue/70">Schoonmaakkosten</dt>
                                <dd class="font-medium text-deep-blue" x-text="euro(resultaat.schoonmaak)"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-deep-blue/70">Toeristenbelasting</dt>
                                <dd class="font-medium text-deep-blue" x-text="euro(resultaat.belasting)"></dd>
                            </div>
                            <div class="flex justify-between gap-4 border-t border-deep-blue/10 pt-3 text-base">
                                <dt class="font-semibold text-deep-blue">Totaal</dt>
                                <dd class="font-bold text-deep-blue" x-text="euro(resultaat.totaal)"></dd>
                            </div>
                        </dl>

                        <p class="mt-3 text-xs text-deep-blue/60">Exclusief borg van <span x-text="euro(resultaat.borg)"></span>.</p>

                        <p class="mt-3 rounded-lg bg-sand/30 p-3 text-sm text-deep-blue" x-show="!resultaat.voldoetAanMinimum">
                            Let op: de minimale verblijfsduur voor deze periode is <strong x-text="resultaat.minNachten + ' nachten'"></strong>.
                        </p>
                    </div>
                </template>

                <p class="mt-4 text-sm font-medium text-coral" x-show="ongeldig" x-cloak role="alert">De vertrekdatum moet na de aankomstdatum liggen.</p>

                <a href="/beschikbaarheid" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Controleer de beschikbaarheid <x-site.icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>

            <div class="rounded-xl border border-deep-blue/10 bg-sand-white p-6 text-sm leading-relaxed text-deep-blue/80">
                <h3 class="font-semibold text-deep-blue">Zo werkt het</h3>
                <ol class="mt-3 space-y-3">
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-turquoise text-xs font-bold text-white">1</span> Je verstuurt je aanvraag voor vrije data.</li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-turquoise text-xs font-bold text-white">2</span> Wilma keurt de aanvraag goed of af.</li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-turquoise text-xs font-bold text-white">3</span> Je ontvangt automatisch bericht per e-mail.</li>
                </ol>
            </div>
        </aside>
    </div>
</section>

@endsection
