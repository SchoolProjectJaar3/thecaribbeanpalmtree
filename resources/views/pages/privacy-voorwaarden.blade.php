<!-- privacy en voorwaarden -->
@extends('layouts.app', ['title' => 'Privacy en voorwaarden | The Caribbean Palm Tree'])

@php
$contact = config('woning.contact');
$t = config('woning.tarieven');
$inhoud = [
    'boekingsvoorwaarden' => 'Boekingsvoorwaarden',
    'huisregels' => 'Huisregels',
    'privacy' => 'Privacyverklaring',
    'cookies' => 'Cookies',
];
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Privacy en voorwaarden"
    title="Heldere afspraken"
    intro="De boekingsvoorwaarden, huisregels en onze privacyverklaring. Zo weet je vooraf precies waar je aan toe bent." />


<section class="py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-4 sm:px-6 lg:grid-cols-[14rem_1fr] lg:px-8">

        <!-- Inhoud -->
        <nav aria-label="Inhoud" class="lg:sticky lg:top-6 lg:self-start">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Inhoud</p>
            <ul class="mt-3 space-y-1">
                @foreach ($inhoud as $anker => $naam)
                <li>
                    <a href="#{{ $anker }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-deep-blue hover:bg-white hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">{{ $naam }}</a>
                </li>
                @endforeach
            </ul>
        </nav>

        <div class="min-w-0 max-w-3xl space-y-14 leading-relaxed text-deep-blue/80">

            <section id="boekingsvoorwaarden" class="scroll-mt-6">
                <h2 class="text-2xl font-bold text-deep-blue sm:text-3xl">Boekingsvoorwaarden</h2>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Reserveren</h3>
                <p class="mt-2">Je reservering is een aanvraag. Deze is pas definitief als de eigenaar de aanvraag heeft goedgekeurd en je daarvan een bevestiging per e-mail hebt ontvangen.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Betaling</h3>
                <p class="mt-2">Na goedkeuring ontvang je de betaalinstructies. Het verblijf, de schoonmaakkosten en de toeristenbelasting worden vooraf voldaan volgens de afspraken in de bevestiging.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Borg</h3>
                <p class="mt-2">We vragen een borg van € {{ number_format($t['borg'], 0, ',', '.') }}. Deze wordt binnen veertien dagen na vertrek terugbetaald, mits de woning en de inventaris in goede staat zijn achtergelaten.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Annuleren</h3>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    <li>Tot 60 dagen voor aankomst: kosteloos annuleren.</li>
                    <li>Tussen 60 en 30 dagen voor aankomst: 50% van de verblijfsprijs.</li>
                    <li>Binnen 30 dagen voor aankomst: 100% van de verblijfsprijs.</li>
                </ul>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Aansprakelijkheid</h3>
                <p class="mt-2">Het verblijf is op eigen risico. De eigenaar is niet aansprakelijk voor diefstal, verlies of schade aan eigendommen van gasten. Wij adviseren een reis- en annuleringsverzekering af te sluiten.</p>
            </section>

            <section id="huisregels" class="scroll-mt-6">
                <h2 class="text-2xl font-bold text-deep-blue sm:text-3xl">Huisregels</h2>
                <ul class="mt-4 list-disc space-y-2 pl-5">
                    <li>De woning biedt plaats aan maximaal 6 personen.</li>
                    <li>Inchecken kan vanaf 15.00 uur, uitchecken kan tot 11.00 uur.</li>
                    <li>Roken is binnen niet toegestaan; op het terras mag dat wel.</li>
                    <li>Huisdieren zijn niet toegestaan.</li>
                    <li>Feesten en evenementen zijn niet toegestaan. Houd na 22.00 uur rekening met de buren.</li>
                    <li>Ga zuinig om met water en energie.</li>
                    <li>Laat de woning bij vertrek netjes en afgesloten achter.</li>
                </ul>
            </section>

            <section id="privacy" class="scroll-mt-6">
                <h2 class="text-2xl font-bold text-deep-blue sm:text-3xl">Privacyverklaring</h2>

                <p class="mt-4">Wij gaan zorgvuldig om met je persoonsgegevens, conform de Algemene Verordening Gegevensbescherming (AVG).</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Welke gegevens verzamelen we?</h3>
                <p class="mt-2">Naam, e-mailadres, telefoonnummer en de gegevens van je reservering of bericht. Meer niet.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Waarvoor gebruiken we ze?</h3>
                <p class="mt-2">Alleen om je vraag of reserveringsaanvraag te beantwoorden en je verblijf te regelen. We delen je gegevens niet met derden voor commerciële doeleinden.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Hoe lang bewaren we ze?</h3>
                <p class="mt-2">Niet langer dan nodig is voor de afhandeling van je aanvraag en de wettelijke bewaartermijnen.</p>

                <h3 class="mt-6 text-lg font-semibold text-deep-blue">Jouw rechten</h3>
                <p class="mt-2">Je hebt het recht op inzage, correctie en verwijdering van je gegevens. Stuur daarvoor een e-mail naar <a href="mailto:{{ $contact['email'] }}" class="font-semibold text-turquoise hover:text-deep-blue">{{ $contact['email'] }}</a>.</p>
            </section>

            <section id="cookies" class="scroll-mt-6">
                <h2 class="text-2xl font-bold text-deep-blue sm:text-3xl">Cookies</h2>
                <p class="mt-4">Deze website gebruikt alleen functionele cookies die nodig zijn om de website en de formulieren goed te laten werken, zoals een sessiecookie en beveiliging tegen misbruik. We plaatsen geen tracking- of advertentiecookies.</p>
            </section>
        </div>
    </div>
</section>

@endsection
