<!-- frequently asked questions -->
@extends('layouts.app', ['title' => 'Veelgestelde vragen | The Caribbean Palm Tree'])

@php
$faq = config('site.faq');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Veelgestelde vragen"
    title="Goed om te weten"
    intro="Antwoorden op de vragen die gasten het vaakst stellen. Staat jouw vraag er niet bij? Neem gerust contact op." />

<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="divide-y divide-deep-blue/10 rounded-xl border border-deep-blue/10 bg-white shadow-sm">
            @foreach ($faq as $item)
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-deep-blue hover:text-turquoise focus:outline-none focus:ring-0 focus:ring-inset focus:ring-turquoise">
                    {{ $item['question'] }}
                    <span class="text-xl text-turquoise transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="px-6 pb-5 leading-relaxed text-deep-blue/80">{{ $item['answer'] }}</p>
            </details>
            @endforeach
        </div>

        <div class="mt-10 rounded-xl bg-sand/30 p-6 text-center">
            <p class="font-semibold text-deep-blue">Je vraag staat er niet tussen?</p>
            <p class="mt-1 text-sm text-deep-blue/80">Stuur ons een bericht, we antwoorden {{ config('site.contact.response_time') }}.</p>
            <a href="/contact" class="mt-4 inline-flex min-h-11 items-center justify-center rounded-lg bg-turquoise px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2">
                Neem contact op
            </a>
        </div>
    </div>
</section>

<x-site.cta-band />

@endsection