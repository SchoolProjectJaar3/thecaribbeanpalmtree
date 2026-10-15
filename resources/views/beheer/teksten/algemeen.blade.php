@php
$standaardtaal = $talen->firstWhere('is_default', true);
@endphp

<x-beheer-layout
    title="Algemene teksten"
    :description="'Teksten die op elke pagina staan, zoals het menu, de footer en het contactblok. Laat je een vertaling leeg, dan toont de website de tekst in het ' . $standaardtaal?->name . '.'">

    <x-slot name="actions">
        <a
            href="{{ route('beheer.teksten') }}"
            class="inline-flex min-h-11 items-center justify-center rounded-lg border border-deep-blue/20 bg-white px-4 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-0 focus:ring-turquoise">
            Alle pagina's
        </a>
    </x-slot>

    <x-beheer.text-form
        :action="route('beheer.teksten.algemeen.update')"
        :secties="$secties"
        :waarden="$waarden"
        :talen="$talen" />

</x-beheer-layout>
