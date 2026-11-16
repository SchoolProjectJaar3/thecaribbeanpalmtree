@php
$tile = 'group flex flex-col rounded-xl border border-deep-blue/10 bg-white p-5 shadow-sm transition hover:-translate-y-px hover:border-turquoise/40 hover:shadow-md focus:outline-none focus:ring-0 focus:ring-turquoise';
$label = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset';
$missing = fn (int $count) => $count === 1 ? '1 tekst ontbreekt' : "{$count} teksten ontbreken";
@endphp

<x-admin-layout
    title="Teksten"
    description="Kies een pagina om de teksten aan te passen, in het Nederlands en het Engels.">

    <!-- On every page -->
    <section>
        <h2 class="text-lg font-semibold text-deep-blue">Op elke pagina</h2>

        <a href="{{ route('admin.texts.general') }}" class="{{ $tile }} mt-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
            <span class="flex items-start gap-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-deep-blue text-sand">
                    <x-site.icon name="document" class="h-5 w-5" />
                </span>
                <span>
                    <span class="flex items-center gap-1 font-semibold text-deep-blue group-hover:text-turquoise">
                        Algemene teksten
                        <x-site.icon name="chevron-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                    </span>
                    <span class="mt-1 block text-sm text-deep-blue/70">Het menu, de footer, het contactblok onderaan, de kalender en de meldingen in formulieren.</span>
                </span>
            </span>

            <span class="mt-4 flex shrink-0 flex-wrap gap-2 sm:mt-0">
                <span class="{{ $label }} bg-deep-blue/5 text-deep-blue/70 ring-deep-blue/15">{{ $general['count'] }} teksten</span>
                @if ($general['missing'])
                <span class="{{ $label }} bg-sand/25 text-deep-blue ring-sand">{{ $missing($general['missing']) }}</span>
                @else
                <span class="{{ $label }} bg-turquoise/10 text-deep-blue ring-turquoise/40">Volledig vertaald</span>
                @endif
            </span>
        </a>
    </section>


    <!-- Pages -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold text-deep-blue">Pagina's</h2>

        @if ($pages->isEmpty())
        <x-admin.card class="mt-4">
            <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er zijn nog geen pagina's vastgelegd.</p>
        </x-admin.card>
        @else
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($pages as $page)
            <a href="{{ route('admin.texts.edit', $page['page']) }}" class="{{ $tile }}">
                <span class="flex items-center gap-1 font-semibold text-deep-blue group-hover:text-turquoise">
                    {{ $page['name'] }}
                    <x-site.icon name="chevron-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                </span>
                <span class="mt-0.5 block text-sm text-deep-blue/60">{{ $page['path'] }}</span>

                <span class="mt-4 flex flex-wrap gap-2">
                    <span class="{{ $label }} bg-deep-blue/5 text-deep-blue/70 ring-deep-blue/15">{{ $page['count'] }} teksten</span>
                    @if ($page['missing'])
                    <span class="{{ $label }} bg-sand/25 text-deep-blue ring-sand">{{ $missing($page['missing']) }}</span>
                    @else
                    <span class="{{ $label }} bg-turquoise/10 text-deep-blue ring-turquoise/40">Volledig vertaald</span>
                    @endif
                </span>

                <span class="mt-auto block pt-4 text-xs text-deep-blue/60">
                    @if ($page['page']->editor)
                    Aangepast {{ $page['page']->updated_at->locale('nl')->diffForHumans() }} door {{ $page['page']->editor->first_name }}
                    @else
                    Nog niet aangepast in het beheer
                    @endif
                </span>
            </a>
            @endforeach
        </div>
        @endif
    </section>

</x-admin-layout>
