@php
$defaultLanguage = $languages->firstWhere('is_default', true);
$translations = $languages->mapWithKeys(fn ($language) => [$language->code => $page->translations->firstWhere('language_id', $language->id)]);
$pageValue = fn (string $field) => $translations->map(fn ($translation) => $translation?->{$field})->all();
@endphp

<x-admin-layout
    :title="'Teksten: ' . $name"
    :description="'Pas de teksten van deze pagina aan. Laat je een vertaling leeg, dan toont de website de tekst in het ' . $defaultLanguage?->name . '.'">

    <x-slot name="actions">
        <a
            href="{{ route('admin.texts') }}"
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

    <x-admin.text-form
        :action="route('admin.texts.update', $page)"
        :sections="$sections"
        :values="$values"
        :languages="$languages">

        <x-slot name="pageFields">
            <x-admin.card title="Pagina" id="page" class="scroll-mt-24">
                <div class="divide-y divide-deep-blue/10">
                    <x-admin.text-field
                        name="page[title]"
                        field="title"
                        label="Naam in het menu"
                        hint="Staat in het menu en in de footer."
                        :max="150"
                        :all-languages-required="true"
                        :languages="$languages"
                        :values="$pageValue('title')" />

                    <x-admin.text-field
                        name="page[meta_title]"
                        field="meta_title"
                        label="Titel in zoekmachines"
                        hint="Staat in het tabblad van de browser en in de zoekresultaten van Google. Maximaal 70 tekens."
                        :max="70"
                        :languages="$languages"
                        :values="$pageValue('meta_title')" />

                    <x-admin.text-field
                        name="page[meta_description]"
                        field="meta_description"
                        label="Omschrijving in zoekmachines"
                        hint="Korte samenvatting onder de titel in de zoekresultaten van Google. Maximaal 160 tekens."
                        :max="160"
                        :multiline="true"
                        :languages="$languages"
                        :values="$pageValue('meta_description')" />
                </div>
            </x-admin.card>
        </x-slot>

    </x-admin.text-form>

</x-admin-layout>
