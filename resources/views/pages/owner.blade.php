<!-- about the owner -->
@extends('layouts.app', ['title' => 'Over de eigenaar | The Caribbean Palm Tree'])

@php
$story = config('site.owner_story');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Over de eigenaar"
    title="Welkom bij Wilma"
    :intro="$story['intro']" />


<section class="py-16 sm:py-20">
    <div class="mx-auto grid max-w-5xl items-start gap-10 px-4 sm:px-6 md:grid-cols-[16rem_1fr] lg:px-8">
        <img
            src="{{ asset('images/website_static/i_lab-wilma-uai-258x258.jpg') }}"
            alt="Wilma, eigenaar van The Caribbean Palm Tree"
            class="mx-auto h-64 w-64 rounded-full object-cover shadow-sm ring-4 ring-sand/60"
            decoding="async">

        <div class="space-y-8">
            @foreach ($story['blocks'] as $block)
            <div>
                <h2 class="text-2xl font-bold text-deep-blue">{{ $block['title'] }}</h2>
                <p class="mt-3 leading-relaxed text-deep-blue/80">{{ $block['text'] }}</p>
            </div>
            @endforeach

            <p class="border-l-4 border-sand pl-5 text-lg font-medium italic leading-relaxed text-deep-blue">
                &ldquo;Mensen huren liever van een persoon dan van een anoniem bedrijf. Bij mij weet je altijd met wie je te maken hebt.&rdquo;
                <span class="mt-2 block text-sm not-italic font-semibold text-deep-blue/70">Wilma Yard van der Stelt</span>
            </p>
        </div>
    </div>
</section>


<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            center
            eyebrow="Wat je mag verwachten"
            title="Persoonlijk, van begin tot eind" />

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($story['values'] as $value)
            <article class="rounded-xl border border-deep-blue/10 bg-sand-white p-6 text-center shadow-sm">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$value['icon']" />
                </span>
                <h3 class="mt-4 text-lg font-semibold text-deep-blue">{{ $value['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $value['text'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<x-site.cta-band title="Ik zie je graag op Curaçao" />

@endsection