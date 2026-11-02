<!-- het huis -->
@extends('layouts.app', ['title' => 'Het huis | The Caribbean Palm Tree'])

@php
$voorzieningen = $woning?->facilities ?? collect();
$ruimtes = config('woning.ruimtes');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Het huis"
    title="Alle comfort voor een zorgeloze vakantie"
    intro="Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen. Hier kom je na een dag op het strand helemaal tot rust."
    image="zwembad_terras.avif" />


<!-- Kerngegevens -->
@if ($woning)
<x-site.kerngegevens :woning="$woning" />
@endif


<!-- Beschrijving -->
<section class="py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <x-site.section-heading
                eyebrow="Over de woning"
                title="Een thuis op Harmonie" />

            @if ($woning?->paragraphs())
            <div class="mt-4 space-y-4 leading-relaxed text-deep-blue/80">
                @foreach ($woning->paragraphs() as $alinea)
                <p>{!! nl2br(e($alinea)) !!}</p>
                @endforeach
            </div>
            @endif

            <a href="/beschikbaarheid" class="mt-8 inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2">
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
@if ($voorzieningen->isNotEmpty())
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Voorzieningen"
            title="Alles wat je nodig hebt"
            intro="Van een volledig uitgeruste keuken tot een eigen zwembad: dit is wat de woning te bieden heeft." />

        <ul class="mt-12 grid gap-x-8 gap-y-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($voorzieningen as $voorziening)
            <li class="flex items-center gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$voorziening->icon ?? 'check'" />
                </span>
                <span class="font-semibold text-deep-blue">{{ $voorziening->name() }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>
@endif


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
            <a href="/fotos" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Bekijk alle foto's <x-site.icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>

<x-site.cta-band />

@endsection