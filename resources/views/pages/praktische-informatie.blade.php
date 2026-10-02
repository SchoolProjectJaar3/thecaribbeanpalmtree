<!-- praktische informatie -->
@extends('layouts.app', ['title' => 'Praktische informatie | The Caribbean Palm Tree'])

@php
$praktisch = config('woning.praktisch');
$noodnummers = config('woning.noodnummers');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Praktische informatie"
    title="Alles om goed voorbereid te vertrekken"
    intro="Van in- en uitchecken tot stroom, water en noodnummers: hier vind je wat je vooraf wilt weten." />


<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($praktisch as $item)
            <article class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$item['icoon']" />
                </span>
                <h2 class="mt-4 text-lg font-semibold text-deep-blue">{{ $item['titel'] }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $item['tekst'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>


<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Noodnummers"
            title="Wie bel je als het nodig is?"
            intro="Bewaar deze nummers op je telefoon voordat je vertrekt." />

        <dl class="mt-8 divide-y divide-deep-blue/10 rounded-xl border border-deep-blue/10 bg-sand-white">
            @foreach ($noodnummers as $nummer)
            <div class="flex items-center justify-between gap-6 p-5">
                <dt class="font-semibold text-deep-blue">{{ $nummer['naam'] }}</dt>
                <dd class="font-semibold text-deep-blue">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $nummer['nummer']) }}" class="inline-flex items-center gap-2 hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        <x-site.icon name="phone" class="h-5 w-5 text-turquoise" /> {{ $nummer['nummer'] }}
                    </a>
                </dd>
            </div>
            @endforeach
        </dl>

        <p class="mt-6 text-sm text-deep-blue/70">
            Meer weten over de voorwaarden en huisregels? Lees de
            <a href="/privacy-voorwaarden" class="font-semibold text-turquoise hover:text-deep-blue">boekingsvoorwaarden</a>.
        </p>
    </div>
</section>

<x-site.cta-band />

@endsection
