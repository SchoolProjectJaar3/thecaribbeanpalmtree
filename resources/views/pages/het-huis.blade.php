<!-- het huis -->
@extends('layouts.app', ['title' => 'Het huis | The Caribbean Palm Tree'])

@php
$kerngegevens = config('woning.kerngegevens');
$voorzieningen = config('woning.voorzieningen');
$ruimtes = config('woning.ruimtes');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Het huis"
    title="Alle comfort voor een zorgeloze vakantie"
    intro="Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen. Hier kom je na een dag op het strand helemaal tot rust."
    image="zwembad_terras.avif" />


<!-- Kerngegevens -->
<section class="border-b border-deep-blue/10 bg-white" aria-label="Kerngegevens">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <ul class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($kerngegevens as $item)
            <li class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$item['icoon']" />
                </span>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-deep-blue/60">{{ $item['label'] }}</p>
                    <p class="font-semibold text-deep-blue">{{ $item['waarde'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</section>


<!-- Beschrijving -->
<section class="py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <x-site.section-heading
                eyebrow="Over de woning"
                title="Een thuis op Harmonie" />

            <div class="mt-4 space-y-4 leading-relaxed text-deep-blue/80">
                <p>
                    De woning ligt rustig en centraal op Harmonie, op korte rijafstand van de mooiste stranden,
                    van Willemstad en van de luchthaven. Binnen en buiten lopen vloeiend in elkaar over: de
                    openslaande deuren van de woonkamer komen uit op het terras met het privézwembad.
                </p>
                <p>
                    De drie slaapkamers hebben airconditioning en de keuken is volledig uitgerust. Zo heb je alles
                    wat je nodig hebt, of je nu een week of een maand blijft.
                </p>
            </div>

            <a href="/beschikbaarheid" class="mt-8 inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                Bekijk beschikbaarheid
            </a>
        </div>

        <img
            src="{{ asset('images/website_static/woonkamer.webp') }}"
            alt="Woonkamer met stenen muur en openslaande deuren naar het terras"
            class="aspect-[4/3] w-full rounded-xl object-cover shadow-sm"
            loading="lazy"
            decoding="async">
    </div>
</section>


<!-- Voorzieningen -->
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Voorzieningen"
            title="Alles wat je nodig hebt"
            intro="Van een volledig uitgeruste keuken tot een eigen zwembad: dit is wat de woning te bieden heeft." />

        <div class="mt-12 grid gap-10 md:grid-cols-2">
            @foreach ($voorzieningen as $groep)
            <div>
                <h3 class="border-b border-sand pb-3 text-xl font-semibold text-deep-blue">{{ $groep['titel'] }}</h3>

                <ul class="mt-5 space-y-5">
                    @foreach ($groep['items'] as $item)
                    <li class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                            <x-site.icon :name="$item['icoon']" />
                        </span>
                        <div>
                            <p class="font-semibold text-deep-blue">{{ $item['titel'] }}</p>
                            <p class="mt-0.5 text-sm leading-relaxed text-deep-blue/70">{{ $item['tekst'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Indeling per ruimte -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Indeling"
            title="Een rondleiding door de woning" />

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            @foreach ($ruimtes as $ruimte)
            <article class="overflow-hidden rounded-xl border border-deep-blue/10 bg-white shadow-sm">
                <img
                    src="{{ asset('images/website_static/' . $ruimte['foto']) }}"
                    alt="{{ $ruimte['alt'] }}"
                    class="aspect-[16/10] w-full object-cover"
                    loading="lazy"
                    decoding="async">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-deep-blue">{{ $ruimte['titel'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $ruimte['tekst'] }}</p>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-8">
            <a href="/fotos" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Bekijk alle foto's <x-site.icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>

<x-site.cta-band />

@endsection
