<!-- reviews -->
@extends('layouts.app', ['title' => 'Reviews | The Caribbean Palm Tree'])

@php
$reviews = collect(config('woning.reviews'));
$gemiddelde = $reviews->avg('sterren');
$verdeling = collect([5, 4, 3, 2, 1])->mapWithKeys(fn ($s) => [$s => $reviews->where('sterren', $s)->count()]);
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Reviews"
    title="Wat onze gasten zeggen"
    intro="Echte ervaringen van gasten die hier eerder verbleven, met hun beoordeling, naam en woonplaats." />


<!-- Samenvatting -->
<section class="border-b border-deep-blue/10 bg-white">
    <div class="mx-auto grid max-w-7xl items-center gap-8 px-4 py-10 sm:px-6 md:grid-cols-[auto_1fr] lg:px-8">
        <div class="text-center md:text-left">
            <p class="text-5xl font-bold text-deep-blue">{{ number_format($gemiddelde, 1, ',', '') }}</p>
            <x-site.stars :aantal="$gemiddelde" class="mt-2 justify-center md:justify-start" />
            <p class="mt-2 text-sm text-deep-blue/70">Gebaseerd op {{ $reviews->count() }} reviews</p>
        </div>

        <ul class="space-y-2" aria-label="Verdeling van de beoordelingen">
            @foreach ($verdeling as $sterren => $aantal)
            <li class="flex items-center gap-3 text-sm">
                <span class="w-20 shrink-0 whitespace-nowrap text-deep-blue/70">{{ $sterren }} {{ $sterren === 1 ? 'ster' : 'sterren' }}</span>
                <span class="h-2.5 flex-1 overflow-hidden rounded-full bg-deep-blue/10">
                    <span class="block h-full rounded-full bg-sand" style="width: {{ $reviews->count() ? round($aantal / $reviews->count() * 100) : 0 }}%"></span>
                </span>
                <span class="w-6 shrink-0 text-right text-deep-blue/70">{{ $aantal }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>


<!-- Reviews -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($reviews as $review)
            <figure class="flex flex-col rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <x-site.stars :aantal="$review['sterren']" />

                <blockquote class="mt-4 flex-1 leading-relaxed text-deep-blue/80">
                    &ldquo;{{ $review['tekst'] }}&rdquo;
                </blockquote>

                <figcaption class="mt-6 border-t border-deep-blue/10 pt-4">
                    <p class="font-semibold text-deep-blue">{{ $review['naam'] }}</p>
                    <p class="text-sm text-deep-blue/60">{{ $review['plaats'] }} &middot; {{ $review['periode'] }}</p>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>

<x-site.cta-band title="Wil jij het ook ervaren?" />

@endsection
