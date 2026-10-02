<!-- beschikbaarheid -->
@extends('layouts.app', ['title' => 'Beschikbaarheid | The Caribbean Palm Tree'])

@php
$tarieven = config('woning.tarieven');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Beschikbaarheid"
    title="Wanneer kom jij?"
    intro="Kies je aankomst- en vertrekdatum in de kalender. Alleen vrije data zijn te selecteren; je aanvraag komt daarna bij de eigenaar terecht." />

<section class="py-16 sm:py-20" x-data="beschikbaarheid(@js($tarieven))">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_22rem] lg:px-8">

        <!-- Kalender -->
        <div>
            <div class="mb-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-deep-blue/80">
                <span class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded-sm bg-turquoise/15 ring-1 ring-turquoise/40" aria-hidden="true"></span> Vrij
                </span>
                <span class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded-sm bg-deep-blue/10" aria-hidden="true"></span> Bezet
                </span>
                <span class="flex items-center gap-2">
                    <span class="h-4 w-4 rounded-sm bg-turquoise" aria-hidden="true"></span> Jouw keuze
                </span>
            </div>

            <x-site.calendar :maanden="6" :interactief="true" />
        </div>

        <!-- Samenvatting -->
        <aside class="lg:sticky lg:top-6 lg:self-start">
            <div class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-deep-blue">Jouw verblijf</h2>

                <dl class="mt-5 space-y-4 text-sm">
                    <div>
                        <dt class="font-medium text-deep-blue/60">Aankomst</dt>
                        <dd class="mt-0.5 font-semibold text-deep-blue" x-text="aankomst ? datumTekst(aankomst) : 'Kies een aankomstdatum'"></dd>
                    </div>
                    <div>
                        <dt class="font-medium text-deep-blue/60">Vertrek</dt>
                        <dd class="mt-0.5 font-semibold text-deep-blue" x-text="vertrek ? datumTekst(vertrek) : 'Kies een vertrekdatum'"></dd>
                    </div>
                    <div x-show="prijs" x-cloak>
                        <dt class="font-medium text-deep-blue/60">Indicatie verblijf</dt>
                        <dd class="mt-0.5 font-semibold text-deep-blue">
                            <span x-text="prijs ? prijs.nachten + ' nachten' : ''"></span>
                            &middot;
                            <span x-text="prijs ? euro(prijs.verblijf - prijs.korting) : ''"></span>
                        </dd>
                        <dd class="mt-1 text-xs text-deep-blue/60">Exclusief schoonmaakkosten en toeristenbelasting. <a href="/tarieven" class="font-semibold text-turquoise hover:text-deep-blue">Bekijk de tarieven</a>.</dd>
                    </div>
                </dl>

                <p class="mt-4 rounded-lg bg-coral/10 p-3 text-sm font-medium text-deep-blue" x-show="melding" x-text="melding" x-cloak role="alert"></p>

                <p class="mt-4 rounded-lg bg-sand/30 p-3 text-sm text-deep-blue" x-show="prijs && !prijs.voldoetAanMinimum" x-cloak role="alert">
                    De minimale verblijfsduur voor deze periode is
                    <strong x-text="prijs ? prijs.minNachten + ' nachten' : ''"></strong>.
                </p>

                <a
                    :href="reserveerUrl"
                    :aria-disabled="!compleet"
                    :class="compleet ? 'bg-coral text-white hover:bg-coral/90' : 'pointer-events-none bg-deep-blue/10 text-deep-blue/40'"
                    class="mt-6 flex min-h-12 items-center justify-center rounded-lg px-5 py-3 text-base font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2">
                    Doorgaan naar reserveren
                </a>

                <button type="button" @click="reset()" x-show="aankomst" x-cloak class="mt-3 w-full text-center text-sm font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Keuze wissen
                </button>
            </div>

            <div class="mt-6 rounded-xl border border-deep-blue/10 bg-sand-white p-6 text-sm leading-relaxed text-deep-blue/80">
                <h3 class="flex items-center gap-2 font-semibold text-deep-blue">
                    <x-site.icon name="info" class="h-5 w-5 text-turquoise" /> Minimale verblijfsduur
                </h3>
                <ul class="mt-3 space-y-1">
                    <li>Laagseizoen (mei t/m november): <strong>{{ $tarieven['min_nachten_laag'] }} nachten</strong></li>
                    <li>Hoogseizoen (december t/m april): <strong>{{ $tarieven['min_nachten_hoog'] }} nachten</strong></li>
                </ul>
                <p class="mt-3">Een aanvraag voor vrije data komt bij de eigenaar terecht, die deze goedkeurt of afwijst.</p>
            </div>
        </aside>
    </div>
</section>

<x-site.cta-band title="Staat jouw periode er niet tussen?" tekst="Neem gerust contact op. Soms is er meer mogelijk dan de kalender laat zien." />

@endsection
