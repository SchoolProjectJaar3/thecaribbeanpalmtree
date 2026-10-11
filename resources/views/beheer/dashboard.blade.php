@php
$datum = fn ($datum) => $datum->locale('nl')->translatedFormat('j M Y');
$onderdelen = collect(config('beheer.navigatie'))
    ->pluck('items')
    ->flatten(1)
    ->reject(fn ($item) => $item['route'] === 'beheer.dashboard');
@endphp

<x-beheer-layout
    title="Dashboard"
    :description="'Welkom terug, ' . auth()->user()->first_name . '. Hier zie je in één oogopslag hoe het ervoor staat.'">

    <!-- Kerncijfers -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-beheer.stat
            label="Openstaande aanvragen"
            :value="$openAanvragen"
            icon="inbox"
            hint="Wachten op jouw beoordeling"
            :href="route('beheer.reserveringen')" />

        <x-beheer.stat
            label="Aankomsten"
            :value="$aankomsten"
            icon="calendar"
            hint="In de komende 30 dagen"
            :href="route('beheer.beschikbaarheid')" />

        <x-beheer.stat
            :label="'Bezetting ' . now()->locale('nl')->translatedFormat('F')"
            :value="$bezetting . '%'"
            icon="chart"
            hint="Geboekte nachten deze maand" />

        <x-beheer.stat
            label="Nieuwe berichten"
            :value="$nieuweBerichten"
            icon="mail"
            hint="Via het contactformulier" />
    </div>


    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">

        <!-- Huidige en komende verblijven -->
        <x-beheer.card title="Huidige en komende verblijven" class="lg:col-span-2">
            <x-slot name="action">
                <a href="{{ route('beheer.reserveringen') }}" class="font-medium text-turquoise hover:text-deep-blue">Alle reserveringen</a>
            </x-slot>

            @if ($eerstvolgende->isEmpty())
            <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er staan nog geen bevestigde verblijven gepland.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase tracking-wider text-deep-blue/60">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-semibold">Gast</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Aankomst</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Vertrek</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Personen</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-deep-blue/10">
                        @foreach ($eerstvolgende as $reservering)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-3">
                                <span class="block font-medium text-deep-blue">{{ $reservering->gast }}</span>
                                <span class="block text-xs text-deep-blue/60">{{ $reservering->reservation_number }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $datum($reservering->arrival_date) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $datum($reservering->departure_date) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $reservering->adults + $reservering->children }}</td>
                            <td class="whitespace-nowrap px-5 py-3"><x-beheer.status-badge :status="$reservering->status" /></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </x-beheer.card>


        <!-- Openstaande aanvragen -->
        <x-beheer.card title="Nieuwe aanvragen">
            @if ($aanvragen->isEmpty())
            <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er zijn geen aanvragen die op je wachten.</p>
            @else
            <ul class="divide-y divide-deep-blue/10">
                @foreach ($aanvragen as $aanvraag)
                <li class="px-5 py-3">
                    <p class="font-medium text-deep-blue">{{ $aanvraag->gast }}</p>
                    <p class="mt-0.5 text-sm text-deep-blue/70">
                        {{ $datum($aanvraag->arrival_date) }} t/m {{ $datum($aanvraag->departure_date) }}
                    </p>
                    <p class="mt-0.5 text-xs text-deep-blue/60">Ontvangen {{ $aanvraag->created_at->locale('nl')->diffForHumans() }}</p>
                </li>
                @endforeach
            </ul>
            @endif
        </x-beheer.card>

    </div>


    <!-- Onderdelen van de beheeromgeving -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold text-deep-blue">Beheer</h2>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($onderdelen as $onderdeel)
            <a
                href="{{ route($onderdeel['route']) }}"
                class="group flex items-start gap-4 rounded-xl border border-deep-blue/10 bg-white p-5 shadow-sm transition hover:-translate-y-px hover:border-turquoise/40 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-turquoise">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-deep-blue text-sand">
                    <x-site.icon :name="$onderdeel['icoon']" class="h-5 w-5" />
                </span>
                <span>
                    <span class="flex items-center gap-1 font-semibold text-deep-blue group-hover:text-turquoise">
                        {{ $onderdeel['label'] }}
                        <x-site.icon name="chevron-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    </span>
                    <span class="mt-1 block text-sm text-deep-blue/70">{{ $onderdeel['omschrijving'] }}</span>
                </span>
            </a>
            @endforeach
        </div>
    </section>


    <!-- Woning -->
    <x-beheer.card title="Woning" class="mt-10">
        <x-slot name="action">
            <a href="{{ route('beheer.woning') }}" class="font-medium text-turquoise hover:text-deep-blue">Woninggegevens</a>
        </x-slot>

        @if ($woning)
        <dl class="grid gap-px overflow-hidden rounded-b-xl bg-deep-blue/10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Naam</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $woning->name }}</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Capaciteit</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $woning->max_guests }} personen</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Indeling</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $woning->bedrooms }} slaapkamers, {{ $woning->bathrooms }} badkamers</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Plaats</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $woning->city ?? 'Nog niet ingevuld' }}</dd>
            </div>
        </dl>
        @else
        <div class="flex flex-col items-start gap-4 px-5 py-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-deep-blue/70">Er is nog geen woning vastgelegd. Zodra de woninggegevens zijn ingevuld, zie je hier een samenvatting.</p>
            <a
                href="{{ route('beheer.woning') }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-coral px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-coral/90">
                Naar woninggegevens
            </a>
        </div>
        @endif
    </x-beheer.card>

</x-beheer-layout>
