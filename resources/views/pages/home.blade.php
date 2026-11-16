<!-- home -->
@extends('layouts.app')

@php
/*
Sample content for the homepage (see project plan chapter 6.1).
The final texts, reviews and FAQ are supplied by the client and will
later come from the admin area.
*/

// Impression (selection from the photo gallery)
$impression = [
['title' => 'Zwembad en terras', 'photo' => 'pool_terrace.avif', 'alt' => 'Privézwembad met houten terras en ligbedden', 'class' => 'sm:col-span-2 sm:row-span-2'],
['title' => 'Woonkamer', 'photo' => 'living_room.webp', 'alt' => 'Lichte woonkamer met bank en openslaande deuren naar het terras', 'class' => ''],
['title' => 'Keuken', 'photo' => 'kitchen.jpg', 'alt' => 'Moderne keuken met kookeiland en barkrukken', 'class' => ''],
['title' => 'Slaapkamer', 'photo' => 'bedroom.avif', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning', 'class' => ''],
['title' => 'Uitzicht', 'photo' => 'view.jpg', 'alt' => 'Uitzicht over het zwembad, de tropische tuin en de zee', 'class' => ''],
];

// Facilities (selection from "Het huis", managed in the accommodation details)
$facilities = $accommodation?->facilities->take(6) ?? collect();

// Surroundings (highlights, texts from the CMS with these texts as fallback)
$surroundings = [
['title' => 'Stranden', 'text' => 'Daaibooi, Kokomo en Playa Porto Marie liggen allemaal binnen een kwartier rijden.', 'travel_time' => 'vanaf 14 min.'],
['title' => 'Snorkelen en duiken', 'text' => 'Ontdek het koraalrif en de schildpadden bij Playa Grandi en de Knip-baaien.', 'travel_time' => 'vanaf 26 min.'],
['title' => 'Willemstad', 'text' => 'Kleurrijke gevels, de Pontjesbrug en gezellige terrassen in de historische binnenstad.', 'travel_time' => '18 min.'],
['title' => 'Natuur', 'text' => 'Beklim de Christoffelberg, spot flamingo\'s of bezoek de golven van Shete Boka.', 'travel_time' => 'dagtrip'],
];

$surroundings = collect($surroundings)->map(fn ($place, $index) => [
'title' => $cms?->text('surroundings', 'item_' . ($index + 1) . '_title') ?? $place['title'],
'text' => $cms?->text('surroundings', 'item_' . ($index + 1) . '_text') ?? $place['text'],
'travel_time' => $cms?->text('surroundings', 'item_' . ($index + 1) . '_travel_time') ?? $place['travel_time'],
]);

// Reviews (sample content, entered via the admin area later)
$reviews = array_slice(config('site.reviews'), 0, 3);

// Frequently asked questions (answers are supplied by the client)
$faq = array_slice(config('site.faq'), 0, 6);

// Contact
$email = config('site.contact.email');
$whatsappUrl = 'https://wa.me/' . config('site.contact.whatsapp');
@endphp

@section('content')

<!-- Hero -->
<section class="relative isolate overflow-hidden bg-deep-blue">
    <img
        src="{{ asset('images/website_static/hero_banner.webp') }}"
        alt=""
        class="absolute inset-0 -z-10 h-full w-full object-cover"
        fetchpriority="high">
    <!-- Dark overlay for readable text on the photo -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-deep-blue/90 via-deep-blue/60 to-deep-blue/20" aria-hidden="true"></div>

    <div class="mx-auto flex min-h-[70vh] max-w-7xl flex-col justify-center px-4 py-24 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-sand">
            {{ $cms?->text('hero', 'eyebrow') ?? 'Vakantiewoning op Curaçao' }}
        </p>

        <h1 class="mt-4 max-w-3xl text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
            {{ $cms?->text('hero', 'title') ?? 'The Caribbean Palm Tree' }}
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/90">
            {{ $cms?->text('hero', 'intro') ?? 'Geniet van een luxe vakantiewoning op Curaçao, rustig en centraal gelegen op Harmonie, dichtbij de mooiste stranden van het eiland.' }}
        </p>

        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
            <a
                href="/availability"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-0 focus:ring-offset-deep-blue">
                {{ $cms?->text('hero', 'button') ?? 'Bekijk beschikbaarheid' }}
            </a>

            <a
                href="/book"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-coral px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-coral/90 focus:outline-none focus:ring-0 focus:ring-coral focus:ring-offset-0 focus:ring-offset-deep-blue">
                {{ $cms?->text('hero', 'booking_button') ?? 'Boek nu' }}
            </a>
        </div>
    </div>
</section>


<!-- Key facts -->
@if ($accommodation)
<x-site.key-facts :accommodation="$accommodation" />
@endif


<!-- Impression -->
<section class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Sfeerimpressie</p>
                <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Een eerste blik op je verblijf</h2>
            </div>

            <a href="/photos" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Bekijk alle foto's <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="mt-10 grid auto-rows-[12rem] grid-cols-1 gap-4 sm:grid-cols-4">
            @foreach ($impression as $photo)
            <div class="relative overflow-hidden rounded-xl bg-deep-blue/10 {{ $photo['class'] }}">
                <img
                    src="{{ asset('images/website_static/' . $photo['photo']) }}"
                    alt="{{ $photo['alt'] }}"
                    class="h-full w-full object-cover transition duration-500 hover:scale-105"
                    loading="lazy"
                    decoding="async">
                <span class="absolute bottom-3 left-3 rounded-md bg-deep-blue/70 px-3 py-1 text-sm font-medium text-white">
                    {{ $photo['title'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- About the house -->
<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <img
            src="{{ asset('images/website_static/living_room.webp') }}"
            alt="Woonkamer met stenen muur en openslaande deuren naar het terras"
            class="aspect-[4/3] w-full rounded-xl object-cover shadow-sm"
            loading="lazy"
            decoding="async">

        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Het huis</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Alle comfort voor een zorgeloze vakantie</h2>
            @if ($accommodation?->text('short_description'))
            <p class="mt-4 leading-relaxed text-deep-blue/80">{{ $accommodation->text('short_description') }}</p>
            @endif

            @if ($facilities->isNotEmpty())
            <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                @foreach ($facilities as $facility)
                <li class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-deep-blue">{{ $facility->name() }}</span>
                </li>
                @endforeach
            </ul>
            @endif

            <a href="/the-house" class="mt-8 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Bekijk alle voorzieningen <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- Surroundings -->
<section class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">{{ $cms?->text('surroundings', 'eyebrow') ?? 'Omgeving' }}</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">{{ $cms?->text('surroundings', 'title') ?? 'Het hele eiland binnen handbereik' }}</h2>
            <p class="mt-4 leading-relaxed text-deep-blue/80">
                {{ $cms?->text('surroundings', 'text') ?? 'Vanaf Harmonie ben je snel bij de mooiste stranden, in Willemstad en op de luchthaven.' }}
            </p>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($surroundings as $place)
            <article class="flex flex-col rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <span class="inline-flex w-fit rounded-full bg-sand/30 px-3 py-1 text-xs font-semibold text-deep-blue">
                    {{ $place['travel_time'] }}
                </span>
                <h3 class="mt-4 text-lg font-semibold text-deep-blue">{{ $place['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $place['text'] }}</p>
            </article>
            @endforeach
        </div>

        <a href="/surroundings" class="mt-8 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
            {{ $cms?->text('surroundings', 'link') ?? 'Ontdek de omgeving' }} <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
</section>


<!-- Availability -->
<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Beschikbaarheid</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Wanneer kom jij?</h2>
            <p class="mt-4 leading-relaxed text-deep-blue/80">
                Bekijk in één oogopslag welke data nog vrij zijn en vraag direct een reservering aan.
                Je ontvangt de bevestiging per e-mail.
            </p>

            <a
                href="/book"
                class="mt-8 inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2">
                {{ $cms?->text('availability', 'button') ?? 'Naar het reserveringsformulier' }}
            </a>
        </div>

        <!-- Compact calendar -->
        <div>
            <x-site.calendar :months="1" />

            <div class="mt-4 flex justify-center gap-6 text-xs text-deep-blue/70">
                <span class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-sm bg-turquoise/15 ring-1 ring-turquoise/40" aria-hidden="true"></span> Vrij
                </span>
                <span class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-sm bg-deep-blue/10" aria-hidden="true"></span> Bezet
                </span>
            </div>
        </div>
    </div>
</section>


<!-- Reviews -->
<section class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Reviews</p>
                <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Wat onze gasten zeggen</h2>
            </div>

            <a href="/reviews" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Alle reviews <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($reviews as $review)
            <figure class="flex flex-col rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <x-site.stars :count="$review['stars']" />

                <blockquote class="mt-4 flex-1 leading-relaxed text-deep-blue/80">
                    &ldquo;{{ $review['text'] }}&rdquo;
                </blockquote>

                <figcaption class="mt-6 border-t border-deep-blue/10 pt-4">
                    <p class="font-semibold text-deep-blue">{{ $review['name'] }}</p>
                    <p class="text-sm text-deep-blue/60">{{ $review['city'] }}</p>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>


<!-- Frequently asked questions -->
<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Veelgestelde vragen</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Goed om te weten</h2>
        </div>

        <div class="mt-10 divide-y divide-deep-blue/10 rounded-xl border border-deep-blue/10 bg-sand-white">
            @foreach ($faq as $item)
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-deep-blue hover:text-turquoise focus:outline-none focus:ring-0 focus:ring-inset focus:ring-turquoise">
                    {{ $item['question'] }}
                    <span class="text-xl text-turquoise transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="px-6 pb-5 leading-relaxed text-deep-blue/80">
                    {{ $item['answer'] }}
                </p>
            </details>
            @endforeach
        </div>

        <div class="mt-8 text-center">
            <a href="/faq" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Bekijk alle vragen <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- About the owner -->
<section class="py-16 sm:py-24">
    <div class="mx-auto grid max-w-5xl items-center gap-10 px-4 sm:px-6 md:grid-cols-[16rem_1fr] lg:px-8">
        <img
            src="{{ asset('images/website_static/i_lab-wilma-uai-258x258.jpg') }}"
            alt="Wilma, eigenaar van The Caribbean Palm Tree"
            class="mx-auto h-64 w-64 rounded-full object-cover shadow-sm ring-4 ring-sand/60"
            loading="lazy"
            decoding="async">

        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Over de eigenaar</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Welkom bij Wilma</h2>
            <p class="mt-4 leading-relaxed text-deep-blue/80">
                Bij The Caribbean Palm Tree ben je geen boekingsnummer. Er is één woning, één eigenaar
                en altijd direct contact. Wilma helpt je graag met tips voor de mooiste stranden,
                de lekkerste restaurants en alles wat je nodig hebt voor een onvergetelijk verblijf.
            </p>

            <a href="/about-the-owner" class="mt-6 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                Lees het hele verhaal <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- Closing -->
<section class="bg-deep-blue py-16 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white sm:text-4xl">Klaar voor zon, zee en rust?</h2>
        <p class="mt-4 text-lg leading-relaxed text-white/80">
            Vraag vrijblijvend je verblijf aan of stel je vraag. We reageren meestal binnen 24 uur,
            rekening houdend met het tijdsverschil met Curaçao.
        </p>

        <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
            <a
                href="/availability"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2 focus:ring-offset-deep-blue">
                {{ $cms?->text('closing', 'button') ?? 'Bekijk beschikbaarheid' }}
            </a>

            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener"
                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-0 focus:ring-white focus:ring-offset-2 focus:ring-offset-deep-blue">
                <x-site.icon name="whatsapp" class="h-5 w-5" />
                {{ $cms?->text('closing', 'whatsapp_button') ?? 'WhatsApp' }}
            </a>
        </div>

        <p class="mt-8 text-sm text-white/70">
            Of mail naar
            <a href="mailto:{{ $email }}" class="font-semibold text-sand hover:text-white focus:outline-none focus:ring-0 focus:ring-sand">{{ $email }}</a>
        </p>
    </div>
</section>

@endsection