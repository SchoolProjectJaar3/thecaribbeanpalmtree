<!-- tarieven -->
@extends('layouts.app', ['title' => 'Tarieven | The Caribbean Palm Tree'])

@php
$t = config('woning.tarieven');
$euro = fn ($bedrag) => '€ ' . number_format($bedrag, 0, ',', '.');
@endphp

@section('content')

<x-site.page-hero
    eyebrow="Tarieven"
    title="Duidelijke prijzen, geen verborgen kosten"
    intro="Op deze pagina zie je precies wat een verblijf kost: de prijs per nacht, de bijkomende kosten en de kortingen bij een langer verblijf." />


<!-- Seizoenen -->
<section class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-site.section-heading
            eyebrow="Prijs per nacht"
            title="Laag- en hoogseizoen" />

        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <article class="rounded-xl border border-deep-blue/10 bg-white p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Laagseizoen</p>
                <p class="mt-3 text-4xl font-bold text-deep-blue">{{ $euro($t['prijs_laag']) }} <span class="text-base font-medium text-deep-blue/60">per nacht</span></p>
                <ul class="mt-6 space-y-2 text-deep-blue/80">
                    <li class="flex items-center gap-2"><x-site.icon name="calendar" class="h-5 w-5 text-turquoise" /> Mei t/m november</li>
                    <li class="flex items-center gap-2"><x-site.icon name="clock" class="h-5 w-5 text-turquoise" /> Minimaal {{ $t['min_nachten_laag'] }} nachten</li>
                </ul>
            </article>

            <article class="rounded-xl border-2 border-sand bg-white p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Hoogseizoen</p>
                <p class="mt-3 text-4xl font-bold text-deep-blue">{{ $euro($t['prijs_hoog']) }} <span class="text-base font-medium text-deep-blue/60">per nacht</span></p>
                <ul class="mt-6 space-y-2 text-deep-blue/80">
                    <li class="flex items-center gap-2"><x-site.icon name="calendar" class="h-5 w-5 text-turquoise" /> December t/m april</li>
                    <li class="flex items-center gap-2"><x-site.icon name="clock" class="h-5 w-5 text-turquoise" /> Minimaal {{ $t['min_nachten_hoog'] }} nachten</li>
                </ul>
            </article>
        </div>
    </div>
</section>


<!-- Bijkomende kosten en kortingen -->
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">

        <div>
            <x-site.section-heading
                eyebrow="Bijkomende kosten"
                title="Dit komt er bij" />

            <dl class="mt-8 divide-y divide-deep-blue/10 rounded-xl border border-deep-blue/10 bg-sand-white">
                <div class="flex items-start justify-between gap-6 p-5">
                    <div>
                        <dt class="font-semibold text-deep-blue">Schoonmaakkosten</dt>
                        <dd class="mt-0.5 text-sm text-deep-blue/70">Eenmalig, voor de eindschoonmaak.</dd>
                    </div>
                    <dd class="shrink-0 font-semibold text-deep-blue">{{ $euro($t['schoonmaak']) }}</dd>
                </div>
                <div class="flex items-start justify-between gap-6 p-5">
                    <div>
                        <dt class="font-semibold text-deep-blue">Toeristenbelasting</dt>
                        <dd class="mt-0.5 text-sm text-deep-blue/70">Per persoon per nacht.</dd>
                    </div>
                    <dd class="shrink-0 font-semibold text-deep-blue">{{ $euro($t['toeristenbelasting']) }}</dd>
                </div>
                <div class="flex items-start justify-between gap-6 p-5">
                    <div>
                        <dt class="font-semibold text-deep-blue">Borg</dt>
                        <dd class="mt-0.5 text-sm text-deep-blue/70">Wordt na vertrek terugbetaald als alles in orde is.</dd>
                    </div>
                    <dd class="shrink-0 font-semibold text-deep-blue">{{ $euro($t['borg']) }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <x-site.section-heading
                eyebrow="Kortingen"
                title="Langer blijven loont" />

            <ul class="mt-8 space-y-3">
                @foreach ($t['kortingen'] as $korting)
                <li class="flex items-center justify-between rounded-xl border border-deep-blue/10 bg-sand-white p-5">
                    <span class="font-semibold text-deep-blue">Vanaf {{ $korting['vanaf'] }} nachten</span>
                    <span class="rounded-full bg-turquoise/15 px-3 py-1 text-sm font-semibold text-deep-blue">{{ $korting['procent'] }}% korting</span>
                </li>
                @endforeach
            </ul>
            <p class="mt-4 text-sm text-deep-blue/70">De korting geldt over de verblijfsprijs, niet over de bijkomende kosten.</p>
        </div>
    </div>
</section>


<!-- Prijsindicatie -->
<section class="py-16 sm:py-20" x-data="prijsCalculator(@js($t))">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">

        <div>
            <x-site.section-heading
                eyebrow="Prijsindicatie"
                title="Bereken je verblijf"
                intro="Vul je data in en zie direct wat je verblijf ongeveer kost, inclusief seizoenen, kortingen en bijkomende kosten." />

            <form class="mt-8 grid gap-5 sm:grid-cols-2" @submit.prevent>
                <div>
                    <label for="calc-aankomst" class="block text-sm font-semibold text-deep-blue">Aankomst</label>
                    <input id="calc-aankomst" type="date" x-model="aankomst" class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise">
                </div>
                <div>
                    <label for="calc-vertrek" class="block text-sm font-semibold text-deep-blue">Vertrek</label>
                    <input id="calc-vertrek" type="date" x-model="vertrek" :min="aankomst" class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise">
                </div>
                <div class="sm:col-span-2">
                    <label for="calc-personen" class="block text-sm font-semibold text-deep-blue">Aantal personen</label>
                    <select id="calc-personen" x-model="personen" class="mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm focus:border-turquoise focus:ring-turquoise">
                        @for ($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'persoon' : 'personen' }}</option>
                        @endfor
                    </select>
                </div>
            </form>

            <p class="mt-4 text-sm font-medium text-coral" x-show="ongeldig" x-cloak role="alert">De vertrekdatum moet na de aankomstdatum liggen.</p>
        </div>

        <div class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm sm:p-8" aria-live="polite">
            <h3 class="text-xl font-semibold text-deep-blue">Jouw indicatie</h3>

            <p class="mt-6 text-deep-blue/60" x-show="!resultaat">Kies een aankomst- en vertrekdatum om de prijs te zien.</p>

            <template x-if="resultaat">
                <div>
                    <dl class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-deep-blue/70" x-text="resultaat.nachten + (resultaat.nachten === 1 ? ' nacht' : ' nachten')"></dt>
                            <dd class="font-medium text-deep-blue" x-text="euro(resultaat.verblijf)"></dd>
                        </div>
                        <div class="flex justify-between gap-4" x-show="resultaat.korting > 0">
                            <dt class="text-deep-blue/70" x-text="'Korting (' + resultaat.kortingProcent + '%)'"></dt>
                            <dd class="font-medium text-deep-blue" x-text="'- ' + euro(resultaat.korting)"></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-deep-blue/70">Schoonmaakkosten</dt>
                            <dd class="font-medium text-deep-blue" x-text="euro(resultaat.schoonmaak)"></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-deep-blue/70">Toeristenbelasting</dt>
                            <dd class="font-medium text-deep-blue" x-text="euro(resultaat.belasting)"></dd>
                        </div>
                        <div class="flex justify-between gap-4 border-t border-deep-blue/10 pt-4 text-base">
                            <dt class="font-semibold text-deep-blue">Totaal</dt>
                            <dd class="font-bold text-deep-blue" x-text="euro(resultaat.totaal)"></dd>
                        </div>
                    </dl>

                    <p class="mt-4 text-xs text-deep-blue/60">
                        Daarnaast vragen we een borg van <span x-text="euro(resultaat.borg)"></span>, die je na vertrek terugkrijgt.
                    </p>

                    <p class="mt-4 rounded-lg bg-sand/30 p-3 text-sm text-deep-blue" x-show="!resultaat.voldoetAanMinimum">
                        Let op: de minimale verblijfsduur voor deze periode is <strong x-text="resultaat.minNachten + ' nachten'"></strong>.
                    </p>

                    <a :href="'/reserveren?aankomst=' + aankomst + '&vertrek=' + vertrek + '&personen=' + personen" class="mt-6 flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-5 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                        Doorgaan naar reserveren
                    </a>
                </div>
            </template>
        </div>
    </div>
</section>

<x-site.cta-band />

@endsection
