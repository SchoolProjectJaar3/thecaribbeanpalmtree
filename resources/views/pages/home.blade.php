<!-- home -->
@extends('layouts.app')

@php
/*
Voorbeeldcontent voor de homepagina (zie PVA hoofdstuk 6.1).
De definitieve teksten, reviews en FAQ worden aangeleverd door de opdrachtgever
en komen later uit de beheeromgeving.
*/

// Kerngegevens (strook met iconen)
$kerngegevens = [
    ['label' => 'Personen', 'waarde' => '6 gasten', 'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
    ['label' => 'Slaapkamers', 'waarde' => '3 kamers', 'icon' => 'm2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
    ['label' => 'Zwembad', 'waarde' => 'Privé', 'icon' => 'M2.25 15.75c1.5 0 1.5-1.5 3-1.5s1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5M2.25 19.5c1.5 0 1.5-1.5 3-1.5s1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5 1.5-1.5 3-1.5 1.5 1.5 3 1.5M8.25 12V5.25a1.5 1.5 0 0 1 3 0M15.75 12V5.25a1.5 1.5 0 0 1 3 0M8.25 8.25h7.5'],
    ['label' => 'Airco', 'waarde' => 'Alle kamers', 'icon' => 'M12 3v18m0-18-3 3m3-3 3 3m-3 15-3-3m3 3 3-3M3 12h18M3 12l3-3m-3 3 3 3m15-3-3-3m3 3-3 3'],
    ['label' => 'Wifi', 'waarde' => 'Glasvezel', 'icon' => 'M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z'],
    ['label' => 'Locatie', 'waarde' => 'Harmonie', 'icon' => 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z'],
];

// Sfeerimpressie (uitsnede van de fotogalerij)
$sfeerimpressie = [
    ['titel' => 'Zwembad en terras', 'foto' => 'zwembad_terras.avif', 'alt' => 'Privézwembad met houten terras en ligbedden', 'klasse' => 'sm:col-span-2 sm:row-span-2'],
    ['titel' => 'Woonkamer', 'foto' => 'woonkamer.webp', 'alt' => 'Lichte woonkamer met bank en openslaande deuren naar het terras', 'klasse' => ''],
    ['titel' => 'Keuken', 'foto' => 'keuken.jpg', 'alt' => 'Moderne keuken met kookeiland en barkrukken', 'klasse' => ''],
    ['titel' => 'Slaapkamer', 'foto' => 'slaapkamer.avif', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning', 'klasse' => ''],
    ['titel' => 'Uitzicht', 'foto' => 'uitzicht.jpg', 'alt' => 'Uitzicht over het zwembad, de tropische tuin en de zee', 'klasse' => ''],
];

// Voorzieningen (uitsnede van "Het huis")
$voorzieningen = [
    'Privézwembad met ruim terras en tuin',
    'Volledig uitgeruste keuken met vaatwasser',
    'Airconditioning in alle slaapkamers',
    'Smart-tv en snel glasvezelinternet',
    'Wasmachine, barbecue en kinderbed',
    'Eigen parkeerplaats op het terrein',
];

// Omgeving (hoogtepunten, reistijden uit het PVA)
$omgeving = [
    ['titel' => 'Stranden', 'tekst' => 'Daaibooi, Kokomo en Playa Porto Marie liggen allemaal binnen een kwartier rijden.', 'reistijd' => 'vanaf 14 min.'],
    ['titel' => 'Snorkelen en duiken', 'tekst' => 'Ontdek het koraalrif en de schildpadden bij Playa Grandi en de Knip-baaien.', 'reistijd' => 'vanaf 26 min.'],
    ['titel' => 'Willemstad', 'tekst' => 'Kleurrijke gevels, de Pontjesbrug en gezellige terrassen in de historische binnenstad.', 'reistijd' => '18 min.'],
    ['titel' => 'Natuur', 'tekst' => 'Beklim de Christoffelberg, spot flamingo\'s of bezoek de golven van Shete Boka.', 'reistijd' => 'dagtrip'],
];

// Beschikbaarheid (voorbeeldmaand, wordt later gevuld vanuit de beheeromgeving)
$kalenderMaand = 'Oktober 2026';
$kalenderStartDag = 4; // 1 oktober 2026 valt op een donderdag (ma = 1)
$kalenderDagen = 31;
$bezetteDagen = [5, 6, 7, 8, 9, 10, 11, 19, 20, 21, 22, 23, 24];

// Reviews (voorbeeldcontent, wordt later ingevoerd via de beheeromgeving)
$reviews = [
    ['naam' => 'Familie de Vries', 'plaats' => 'Utrecht', 'sterren' => 5, 'tekst' => 'Een heerlijk huis met een prachtig zwembad. Alles was schoon en tot in de puntjes verzorgd. Binnen een kwartier sta je op het strand!'],
    ['naam' => 'Sanne en Mark', 'plaats' => 'Rotterdam', 'sterren' => 5, 'tekst' => 'Wilma dacht overal aan mee en gaf ons de beste tips voor stranden en restaurants. We komen zeker terug.'],
    ['naam' => 'Thomas', 'plaats' => 'Antwerpen', 'sterren' => 4, 'tekst' => 'Rustig gelegen en toch centraal. Ideale uitvalsbasis om het hele eiland te ontdekken.'],
];

// Veelgestelde vragen (antwoorden worden aangeleverd door de opdrachtgever)
$faq = [
    ['vraag' => 'Is een auto noodzakelijk?', 'antwoord' => 'Ja, wij raden een huurauto aan. De woning ligt centraal, maar stranden, supermarkten en Willemstad bereik je het makkelijkst met de auto.'],
    ['vraag' => 'Is er airconditioning aanwezig?', 'antwoord' => 'Ja, alle slaapkamers zijn voorzien van airconditioning.'],
    ['vraag' => 'Hoe werkt de sleuteloverdracht?', 'antwoord' => 'Bij aankomst word je ontvangen door onze lokale contactpersoon, die je de sleutels overhandigt en de woning laat zien.'],
    ['vraag' => 'Zijn huisdieren toegestaan?', 'antwoord' => 'Huisdieren zijn helaas niet toegestaan in de woning.'],
    ['vraag' => 'Mag er gerookt worden?', 'antwoord' => 'Binnen is roken niet toegestaan. Op het terras mag je wel roken.'],
    ['vraag' => 'Hoe werkt annuleren?', 'antwoord' => 'De annuleringsvoorwaarden staan in de boekingsvoorwaarden. Neem bij twijfel gerust contact met ons op.'],
];

// Contact
$email = 'stay@thecaribbeanpalmtree.com';
$whatsappUrl = 'https://wa.me/'; // TODO: WhatsApp-nummer van de accommodatie toevoegen
@endphp

@section('content')

<!-- Hero -->
<section class="relative isolate overflow-hidden bg-deep-blue">
    <img
        src="{{ asset('images/website_static/grote_banner.webp') }}"
        alt=""
        class="absolute inset-0 -z-10 h-full w-full object-cover"
        fetchpriority="high">
    <!-- Donkere laag voor leesbare tekst op de foto -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-deep-blue/90 via-deep-blue/60 to-deep-blue/20" aria-hidden="true"></div>

    <div class="mx-auto flex min-h-[70vh] max-w-7xl flex-col justify-center px-4 py-24 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-widest text-sand">
            Vakantiewoning op Curaçao
        </p>

        <h1 class="mt-4 max-w-3xl text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
            The Caribbean Palm Tree
        </h1>

        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/90">
            Geniet van een luxe vakantiewoning op Curaçao, rustig en centraal gelegen op Harmonie,
            dichtbij de mooiste stranden van het eiland.
        </p>

        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
            <a
                href="/beschikbaarheid"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2 focus:ring-offset-deep-blue">
                Bekijk beschikbaarheid
            </a>

            <a
                href="/contact"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-coral px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-coral/90 focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2 focus:ring-offset-deep-blue">
                Boek nu
            </a>
        </div>
    </div>
</section>


<!-- Kerngegevens -->
<section class="border-b border-deep-blue/10 bg-white" aria-label="Kerngegevens">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <ul class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($kerngegevens as $item)
            <li class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                    </svg>
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


<!-- Sfeerimpressie -->
<section class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Sfeerimpressie</p>
                <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Een eerste blik op je verblijf</h2>
            </div>

            <a href="/fotos" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Bekijk alle foto's <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="mt-10 grid auto-rows-[12rem] grid-cols-1 gap-4 sm:grid-cols-4">
            @foreach ($sfeerimpressie as $foto)
            <div class="relative overflow-hidden rounded-xl bg-deep-blue/10 {{ $foto['klasse'] }}">
                <img
                    src="{{ asset('images/website_static/' . $foto['foto']) }}"
                    alt="{{ $foto['alt'] }}"
                    class="h-full w-full object-cover transition duration-500 hover:scale-105"
                    loading="lazy"
                    decoding="async">
                <span class="absolute bottom-3 left-3 rounded-md bg-deep-blue/70 px-3 py-1 text-sm font-medium text-white">
                    {{ $foto['titel'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Over het huis -->
<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <img
            src="{{ asset('images/website_static/woonkamer.webp') }}"
            alt="Woonkamer met stenen muur en openslaande deuren naar het terras"
            class="aspect-[4/3] w-full rounded-xl object-cover shadow-sm"
            loading="lazy"
            decoding="async">

        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Het huis</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Alle comfort voor een zorgeloze vakantie</h2>
            <p class="mt-4 leading-relaxed text-deep-blue/80">
                Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen.
                Hier kom je na een dag op het strand helemaal tot rust.
            </p>

            <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                @foreach ($voorzieningen as $voorziening)
                <li class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-deep-blue">{{ $voorziening }}</span>
                </li>
                @endforeach
            </ul>

            <a href="/het-huis" class="mt-8 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Bekijk alle voorzieningen <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- Omgeving -->
<section class="py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Omgeving</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Het hele eiland binnen handbereik</h2>
            <p class="mt-4 leading-relaxed text-deep-blue/80">
                Vanaf Harmonie ben je snel bij de mooiste stranden, in Willemstad en op de luchthaven.
            </p>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($omgeving as $plek)
            <article class="flex flex-col rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <span class="inline-flex w-fit rounded-full bg-sand/30 px-3 py-1 text-xs font-semibold text-deep-blue">
                    {{ $plek['reistijd'] }}
                </span>
                <h3 class="mt-4 text-lg font-semibold text-deep-blue">{{ $plek['titel'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-deep-blue/80">{{ $plek['tekst'] }}</p>
            </article>
            @endforeach
        </div>

        <a href="/omgeving" class="mt-8 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
            Ontdek de omgeving <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
</section>


<!-- Beschikbaarheid -->
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
                href="/beschikbaarheid"
                class="mt-8 inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                Naar het reserveringsformulier
            </a>
        </div>

        <!-- Compacte kalender -->
        <div class="rounded-xl border border-deep-blue/10 bg-sand-white p-6 shadow-sm">
            <p class="text-center font-semibold text-deep-blue">{{ $kalenderMaand }}</p>

            <div class="mt-4 grid grid-cols-7 gap-1 text-center text-xs font-medium text-deep-blue/60" aria-hidden="true">
                @foreach (['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'] as $dag)
                <span>{{ $dag }}</span>
                @endforeach
            </div>

            <div class="mt-2 grid grid-cols-7 gap-1 text-center text-sm">
                @for ($i = 1; $i < $kalenderStartDag; $i++)
                <span aria-hidden="true"></span>
                @endfor

                @for ($dag = 1; $dag <= $kalenderDagen; $dag++)
                @if (in_array($dag, $bezetteDagen))
                <span class="rounded-md bg-deep-blue/10 py-2 text-deep-blue/40 line-through">
                    {{ $dag }}<span class="sr-only"> (bezet)</span>
                </span>
                @else
                <span class="rounded-md bg-turquoise/15 py-2 font-medium text-deep-blue">
                    {{ $dag }}<span class="sr-only"> (vrij)</span>
                </span>
                @endif
                @endfor
            </div>

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

            <a href="/reviews" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Alle reviews <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($reviews as $review)
            <figure class="flex flex-col rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm">
                <div class="flex gap-1 text-sand" aria-label="{{ $review['sterren'] }} van de 5 sterren">
                    @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-5 w-5 {{ $i <= $review['sterren'] ? 'text-sand' : 'text-deep-blue/15' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                    </svg>
                    @endfor
                </div>

                <blockquote class="mt-4 flex-1 leading-relaxed text-deep-blue/80">
                    &ldquo;{{ $review['tekst'] }}&rdquo;
                </blockquote>

                <figcaption class="mt-6 border-t border-deep-blue/10 pt-4">
                    <p class="font-semibold text-deep-blue">{{ $review['naam'] }}</p>
                    <p class="text-sm text-deep-blue/60">{{ $review['plaats'] }}</p>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>


<!-- Veelgestelde vragen -->
<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Veelgestelde vragen</p>
            <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">Goed om te weten</h2>
        </div>

        <div class="mt-10 divide-y divide-deep-blue/10 rounded-xl border border-deep-blue/10 bg-sand-white">
            @foreach ($faq as $item)
            <details class="group">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-inset focus:ring-turquoise">
                    {{ $item['vraag'] }}
                    <span class="text-xl text-turquoise transition group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="px-6 pb-5 leading-relaxed text-deep-blue/80">
                    {{ $item['antwoord'] }}
                </p>
            </details>
            @endforeach
        </div>

        <div class="mt-8 text-center">
            <a href="/faq" class="inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Bekijk alle vragen <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- Over de eigenaar -->
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

            <a href="/over-de-eigenaar" class="mt-6 inline-flex items-center gap-1 font-semibold text-turquoise hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise">
                Lees het hele verhaal <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>


<!-- Afsluiting -->
<section class="bg-deep-blue py-16 sm:py-24">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white sm:text-4xl">Klaar voor zon, zee en rust?</h2>
        <p class="mt-4 text-lg leading-relaxed text-white/80">
            Vraag vrijblijvend je verblijf aan of stel je vraag. We reageren meestal binnen 24 uur,
            rekening houdend met het tijdsverschil met Curaçao.
        </p>

        <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
            <a
                href="/beschikbaarheid"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2 focus:ring-offset-deep-blue">
                Bekijk beschikbaarheid
            </a>

            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener"
                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-deep-blue">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                </svg>
                WhatsApp
            </a>
        </div>

        <p class="mt-8 text-sm text-white/70">
            Of mail naar
            <a href="mailto:{{ $email }}" class="font-semibold text-sand hover:text-white focus:outline-none focus:ring-2 focus:ring-sand">{{ $email }}</a>
        </p>
    </div>
</section>

@endsection
