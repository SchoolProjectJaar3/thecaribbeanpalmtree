<!-- omgeving -->
@extends('layouts.app', ['title' => 'Omgeving | The Caribbean Palm Tree'])

@php
$afstanden = config('woning.afstanden');
$activiteiten = config('woning.activiteiten');
$extraStranden = config('woning.extra_stranden');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Omgeving"
    title="Het hele eiland binnen handbereik"
    intro="De woning ligt centraal op Harmonie. Vanaf hier ben je snel bij de mooiste stranden, in Willemstad en op de luchthaven."
    image="grote_banner.webp" />


<!-- Reistijden -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Afstanden"
            title="Reistijden vanaf de woning"
            intro="Alle reistijden zijn met de auto vanaf de woning." />

        <div class="mt-10 grid gap-8 lg:grid-cols-2">
            @foreach ($afstanden as $groep => $plekken)
            <div class="{{ $loop->first ? 'lg:row-span-2' : '' }}">
                <h3 class="text-xl font-semibold text-deep-blue">{{ $groep }}</h3>

                <div class="mt-4 overflow-hidden rounded-xl border border-deep-blue/10 bg-white shadow-sm">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-sand/30 text-deep-blue">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold">Bestemming</th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold">Reistijd</th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold">Afstand</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-deep-blue/10">
                            @foreach ($plekken as $plek)
                            <tr>
                                <th scope="row" class="px-5 py-3 font-medium text-deep-blue">{{ $plek['naam'] }}</th>
                                <td class="px-5 py-3 text-right text-deep-blue/80">{{ $plek['minuten'] }} min.</td>
                                <td class="px-5 py-3 text-right text-deep-blue/80">{{ $plek['km'] }} km</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <details class="group mt-8 rounded-xl border border-deep-blue/10 bg-white shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-inset focus:ring-turquoise">
                Meer stranden om te ontdekken
                <x-site.icon name="chevron-down" class="h-5 w-5 text-turquoise transition group-open:rotate-180" />
            </summary>
            <ul class="flex flex-wrap gap-2 px-6 pb-6">
                @foreach ($extraStranden as $strand)
                <li class="rounded-full bg-turquoise/15 px-4 py-1.5 text-sm font-medium text-deep-blue">{{ $strand }}</li>
                @endforeach
            </ul>
        </details>
    </div>
</section>


<!-- Kaart -->
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Kaart"
            title="Zo ligt de woning op het eiland" />

        <div class="mt-10 overflow-hidden rounded-xl border border-deep-blue/10 shadow-sm">
            <iframe
                title="Kaart van Harmonie, Curaçao"
                src="https://www.google.com/maps?q=Harmonie,+Cura%C3%A7ao&output=embed"
                class="h-96 w-full"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen></iframe>
        </div>

        <a href="https://www.google.com/maps/search/?api=1&query=Harmonie%2C%20Cura%C3%A7ao" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
            Open in Google Maps <x-site.icon name="arrow-right" class="h-4 w-4" />
        </a>
    </div>
</section>


<!-- Activiteiten -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Activiteiten"
            title="Wat kun je doen op Curaçao?"
            intro="Of je nu actief op pad wilt of liever ontspant: het eiland heeft voor ieder wat wils." />

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($activiteiten as $activiteit)
            <article class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$activiteit['icoon']" />
                </span>
                <h3 class="mt-4 text-lg font-semibold text-deep-blue">{{ $activiteit['titel'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $activiteit['tekst'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<x-site.cta-band />

@endsection
