<!-- photo gallery -->
@extends('layouts.app', ['title' => "Foto's | The Caribbean Palm Tree"])

@php
$categories = config('site.photo_categories');
$photos = collect(config('site.photos'))->map(fn ($photo) => $photo + [
'url' => asset('images/website_static/' . $photo['file']),
])->values();
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Foto's"
    title="Een kijkje in de woning"
    intro="Van het zwembad en het terras tot de woonkamer en het uitzicht. Klik op een foto om hem groter te bekijken."
    image="view.jpg" />

<section class="py-16 sm:py-20" x-data="photoGallery(@js($photos))">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Filter by subject -->
        <div class="flex flex-wrap gap-2" role="group" aria-label="Filter foto's">
            @foreach ($categories as $key => $name)
            <button
                type="button"
                @click="filter = '{{ $key }}'"
                :aria-pressed="filter === '{{ $key }}'"
                :class="filter === '{{ $key }}' ? 'border-deep-blue bg-deep-blue text-white' : 'border-deep-blue/20 bg-white text-deep-blue hover:border-turquoise hover:text-turquoise'"
                class="rounded-full border px-4 py-2 text-sm font-semibold transition focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2">
                {{ $name }}
            </button>
            @endforeach
        </div>

        <!-- Grid -->
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($photos as $i => $photo)
            <button
                type="button"
                x-show="shows('{{ $photo['category'] }}')"
                @click="openPhoto({{ $i }})"
                class="group relative aspect-[4/3] overflow-hidden rounded-xl bg-deep-blue/10 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2">
                <img
                    src="{{ $photo['url'] }}"
                    alt="{{ $photo['alt'] }}"
                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    loading="lazy"
                    decoding="async">
                <span class="absolute bottom-3 left-3 rounded-md bg-deep-blue/70 px-3 py-1 text-sm font-medium text-white">
                    {{ $photo['title'] }}
                </span>
            </button>
            @endforeach
        </div>

        <p class="mt-8 text-sm text-deep-blue/60">
            De foto's van de omgeving en meer opnames van de woning volgen zodra het beeldmateriaal beschikbaar is.
        </p>
    </div>

    <!-- Lightbox -->
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        @keydown.escape.window="open && close()"
        @keydown.arrow-right.window="open && next()"
        @keydown.arrow-left.window="open && previous()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-deep-blue/95 p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Fotoweergave">

        <button type="button" @click="close()" class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none focus:ring-0 focus:ring-white" aria-label="Sluiten">
            <x-site.icon name="close" />
        </button>

        <button type="button" @click="previous()" class="absolute left-2 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none focus:ring-0 focus:ring-white sm:left-6" aria-label="Vorige foto">
            <x-site.icon name="chevron-left" />
        </button>

        <figure class="flex max-h-full max-w-5xl flex-col items-center">
            <img :src="photos[index].url" :alt="photos[index].alt" class="max-h-[80vh] w-auto max-w-full rounded-lg object-contain">
            <figcaption class="mt-4 text-center text-white" x-text="photos[index].title"></figcaption>
        </figure>

        <button type="button" @click="next()" class="absolute right-2 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 focus:outline-none focus:ring-0 focus:ring-white sm:right-6" aria-label="Volgende foto">
            <x-site.icon name="chevron-right" />
        </button>
    </div>
</section>

<x-site.cta-band />

@endsection