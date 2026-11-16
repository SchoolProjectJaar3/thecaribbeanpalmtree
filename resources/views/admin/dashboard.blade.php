@php
$formatDate = fn ($date) => $date->locale('nl')->translatedFormat('j M Y');

// Temporary: the forms do not store anything yet (see FormController).
// Set to true once reservations and messages are stored.

$formsAreStored = false;
$show = fn ($value) => $formsAreStored ? $value : '–';
$sections = collect(config('admin.navigation'))
->pluck('items')
->flatten(1)
->reject(fn ($item) => $item['route'] === 'admin.dashboard');
@endphp

<x-admin-layout
    title="Dashboard"
    :description="'Welkom terug, ' . auth()->user()->first_name . '. Hier zie je in één oogopslag hoe het ervoor staat.'">

    <!-- Key figures -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat
            label="Openstaande aanvragen"
            :value="$show($openRequests)"
            icon="inbox"
            hint="Wachten op jouw beoordeling"
            :href="route('admin.reservations')" />

        <x-admin.stat
            label="Aankomsten"
            :value="$show($arrivals)"
            icon="calendar"
            hint="In de komende 30 dagen"
            :href="route('admin.availability')" />

        <x-admin.stat
            :label="'Bezetting ' . now()->locale('nl')->translatedFormat('F')"
            :value="$show($occupancy) . '%'"
            icon="chart"
            hint="Geboekte nachten deze maand" />

        <x-admin.stat
            label="Nieuwe berichten"
            :value="$show($newMessages)"
            icon="mail"
            hint="Via het contactformulier" />
    </div>


    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">

        <!-- Current and upcoming stays -->
        <x-admin.card title="Huidige en komende verblijven" class="lg:col-span-2">
            <x-slot name="action">
                <a href="{{ route('admin.reservations') }}" class="font-medium text-turquoise hover:text-deep-blue">Alle reserveringen</a>
            </x-slot>

            @if ($upcoming->isEmpty())
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
                        @foreach ($upcoming as $reservation)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-3">
                                <span class="block font-medium text-deep-blue">{{ $reservation->guest }}</span>
                                <span class="block text-xs text-deep-blue/60">{{ $reservation->reservation_number }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $formatDate($reservation->arrival_date) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $formatDate($reservation->departure_date) }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-deep-blue/80">{{ $reservation->adults + $reservation->children }}</td>
                            <td class="whitespace-nowrap px-5 py-3"><x-admin.status-badge :status="$reservation->status" /></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </x-admin.card>


        <!-- Open requests -->
        <x-admin.card title="Nieuwe aanvragen">
            @if ($bookingRequests->isEmpty())
            <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er zijn geen aanvragen die op je wachten.</p>
            @else
            <ul class="divide-y divide-deep-blue/10">
                @foreach ($bookingRequests as $bookingRequest)
                <li class="px-5 py-3">
                    <p class="font-medium text-deep-blue">{{ $bookingRequest->guest }}</p>
                    <p class="mt-0.5 text-sm text-deep-blue/70">
                        {{ $formatDate($bookingRequest->arrival_date) }} t/m {{ $formatDate($bookingRequest->departure_date) }}
                    </p>
                    <p class="mt-0.5 text-xs text-deep-blue/60">Ontvangen {{ $bookingRequest->created_at->locale('nl')->diffForHumans() }}</p>
                </li>
                @endforeach
            </ul>
            @endif
        </x-admin.card>

    </div>


    <!-- Sections of the admin area -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold text-deep-blue">Beheer</h2>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($sections as $section)
            <a
                href="{{ route($section['route']) }}"
                class="group flex items-start gap-4 rounded-xl border border-deep-blue/10 bg-white p-5 shadow-sm transition hover:-translate-y-px hover:border-turquoise/40 hover:shadow-md focus:outline-none focus:ring-0 focus:ring-turquoise">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-deep-blue text-sand">
                    <x-site.icon :name="$section['icon']" class="h-5 w-5" />
                </span>
                <span>
                    <span class="flex items-center gap-1 font-semibold text-deep-blue group-hover:text-turquoise">
                        {{ $section['label'] }}
                        <x-site.icon name="chevron-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    </span>
                    <span class="mt-1 block text-sm text-deep-blue/70">{{ $section['description'] }}</span>
                </span>
            </a>
            @endforeach
        </div>
    </section>


    <!-- Accommodation -->
    <x-admin.card title="Woning" class="mt-10">
        <x-slot name="action">
            <a href="{{ route('admin.accommodation') }}" class="font-medium text-turquoise hover:text-deep-blue">Woninggegevens</a>
        </x-slot>

        @if ($accommodation)
        <dl class="grid gap-px overflow-hidden rounded-b-xl bg-deep-blue/10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Naam</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $accommodation->name }}</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Capaciteit</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $accommodation->max_guests }} personen</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Indeling</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $accommodation->bedrooms }} slaapkamers, {{ $accommodation->bathrooms }} badkamers</dd>
            </div>
            <div class="bg-white px-5 py-4">
                <dt class="text-xs font-semibold uppercase tracking-wider text-deep-blue/60">Plaats</dt>
                <dd class="mt-1 font-medium text-deep-blue">{{ $accommodation->city ?? 'Nog niet ingevuld' }}</dd>
            </div>
        </dl>
        @else
        <div class="flex flex-col items-start gap-4 px-5 py-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-deep-blue/70">Er is nog geen woning vastgelegd. Zodra de woninggegevens zijn ingevuld, zie je hier een samenvatting.</p>
            <a
                href="{{ route('admin.accommodation') }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-coral px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-coral/90">
                Naar woninggegevens
            </a>
        </div>
        @endif
    </x-admin.card>

</x-admin-layout>