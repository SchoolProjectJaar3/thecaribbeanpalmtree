<!-- contact -->
@extends('layouts.app', ['title' => 'Contact | The Caribbean Palm Tree'])

@php
$contact = config('woning.contact');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Contact"
    title="Heb je een vraag?"
    intro="Stuur ons een bericht, mail of app ons. We reageren {{ $contact['reactietijd'] }}." />


<section class="py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_24rem] lg:px-8">

        <!-- Formulier -->
        <div id="bevestiging" class="scroll-mt-6">
            @if (session('status'))
            <div class="mb-8 flex items-start gap-3 rounded-xl border border-turquoise/40 bg-turquoise/10 p-5 text-deep-blue" role="status">
                <x-site.icon name="check" class="mt-0.5 h-6 w-6 shrink-0 text-turquoise" />
                <p class="font-medium">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('contact.verstuur') }}" class="space-y-5 rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm sm:p-8" novalidate>
                @csrf

                <h2 class="text-2xl font-bold text-deep-blue">Stuur een bericht</h2>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-site.field name="naam" label="Naam" :required="true" autocomplete="name" />
                    <x-site.field name="email" label="E-mailadres" type="email" :required="true" autocomplete="email" />
                </div>

                <x-site.field name="telefoon" label="Telefoonnummer" type="tel" autocomplete="tel" hint="Optioneel" />
                <x-site.field name="bericht" label="Bericht" as="textarea" :required="true" />

                <!-- Spambeveiliging: dit veld is voor mensen onzichtbaar en moet leeg blijven -->
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                    Bericht versturen
                </button>

                <p class="text-xs text-deep-blue/60">
                    Je ontvangt automatisch een bevestiging. We gebruiken je gegevens alleen om je vraag te beantwoorden;
                    lees ons <a href="/privacy-voorwaarden#privacy" class="font-semibold text-turquoise hover:text-deep-blue">privacybeleid</a>.
                </p>
            </form>
        </div>

        <!-- Contactgegevens -->
        <aside class="space-y-6">
            <div class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-deep-blue">Contactgegevens</h2>

                <ul class="mt-5 space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <x-site.icon name="mail" class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" />
                        <a href="mailto:{{ $contact['email'] }}" class="font-medium text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">{{ $contact['email'] }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-site.icon name="phone" class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" />
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contact['telefoon']) }}" class="font-medium text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">{{ $contact['telefoon'] }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-site.icon name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" />
                        <span class="font-medium text-deep-blue">Harmonie, Curaçao</span>
                    </li>
                </ul>

                <a
                    href="https://wa.me/{{ $contact['whatsapp'] }}"
                    target="_blank"
                    rel="noopener"
                    class="mt-6 flex min-h-12 items-center justify-center gap-2 rounded-lg bg-deep-blue px-5 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-deep-blue/90 focus:outline-none focus:ring-2 focus:ring-deep-blue focus:ring-offset-2">
                    <x-site.icon name="whatsapp" class="h-5 w-5" />
                    Stuur een WhatsApp-bericht
                </a>
            </div>

            <div class="rounded-xl border border-deep-blue/10 bg-sand-white p-6 text-sm leading-relaxed text-deep-blue/80">
                <h3 class="flex items-center gap-2 font-semibold text-deep-blue">
                    <x-site.icon name="clock" class="h-5 w-5 text-turquoise" /> Reactietijd
                </h3>
                <p class="mt-3">We reageren {{ $contact['reactietijd'] }}. {{ $contact['tijdsverschil'] }}</p>
            </div>
        </aside>
    </div>
</section>

@endsection
