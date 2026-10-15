@php
$standaardtaal = $talen->firstWhere('is_default', true);
$vertalingen = $talen->mapWithKeys(fn ($taal) => [$taal->code => $page->translations->firstWhere('language_id', $taal->id)]);
$paginaWaarde = fn (string $veld) => $vertalingen->map(fn ($vertaling) => $vertaling?->{$veld})->all();
@endphp

<x-beheer-layout
    :title="'Teksten: ' . $naam"
    :description="'Pas de teksten van deze pagina aan. Laat je een vertaling leeg, dan toont de website de tekst in het ' . $standaardtaal?->name . '.'">

    <x-slot name="actions">
        <a
            href="{{ route('beheer.teksten') }}"
            class="inline-flex min-h-11 items-center justify-center rounded-lg border border-deep-blue/20 bg-white px-4 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-0 focus:ring-turquoise">
            Alle pagina's
        </a>
        <a
            href="{{ $url }}"
            target="_blank"
            rel="noopener"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-deep-blue/20 bg-white px-4 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-0 focus:ring-turquoise">
            Bekijk pagina
            <x-site.icon name="external" class="h-4 w-4" />
        </a>
    </x-slot>

    <x-beheer.text-form
        :action="route('beheer.teksten.update', $page)"
        :secties="$secties"
        :waarden="$waarden"
        :talen="$talen">

        <x-slot name="pagina">
            <x-beheer.card title="Pagina" id="pagina" class="scroll-mt-24">
                <div class="divide-y divide-deep-blue/10">
                    <x-beheer.text-field
                        naam="pagina[title]"
                        veld="title"
                        label="Naam in het menu"
                        hint="Staat in het menu en in de footer."
                        :max="150"
                        :alle-talen-verplicht="true"
                        :talen="$talen"
                        :waarden="$paginaWaarde('title')" />

                    <x-beheer.text-field
                        naam="pagina[meta_title]"
                        veld="meta_title"
                        label="Titel in zoekmachines"
                        hint="Staat in het tabblad van de browser en in de zoekresultaten van Google. Maximaal 70 tekens."
                        :max="70"
                        :talen="$talen"
                        :waarden="$paginaWaarde('meta_title')" />

                    <x-beheer.text-field
                        naam="pagina[meta_description]"
                        veld="meta_description"
                        label="Omschrijving in zoekmachines"
                        hint="Korte samenvatting onder de titel in de zoekresultaten van Google. Maximaal 160 tekens."
                        :max="160"
                        :meerdere-regels="true"
                        :talen="$talen"
                        :waarden="$paginaWaarde('meta_description')" />
                </div>
            </x-beheer.card>
        </x-slot>

    </x-beheer.text-form>

</x-beheer-layout>
