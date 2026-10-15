@php
$tegel = 'group flex flex-col rounded-xl border border-deep-blue/10 bg-white p-5 shadow-sm transition hover:-translate-y-px hover:border-turquoise/40 hover:shadow-md focus:outline-none focus:ring-0 focus:ring-turquoise';
$label = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset';
$ontbrekend = fn (int $aantal) => $aantal === 1 ? '1 tekst ontbreekt' : "{$aantal} teksten ontbreken";
@endphp

<x-beheer-layout
    title="Teksten"
    description="Kies een pagina om de teksten aan te passen, in het Nederlands en het Engels.">

    <!-- Op elke pagina -->
    <section>
        <h2 class="text-lg font-semibold text-deep-blue">Op elke pagina</h2>

        <a href="{{ route('beheer.teksten.algemeen') }}" class="{{ $tegel }} mt-4 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
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
                <span class="{{ $label }} bg-deep-blue/5 text-deep-blue/70 ring-deep-blue/15">{{ $algemeen['aantal'] }} teksten</span>
                @if ($algemeen['ontbrekend'])
                <span class="{{ $label }} bg-sand/25 text-deep-blue ring-sand">{{ $ontbrekend($algemeen['ontbrekend']) }}</span>
                @else
                <span class="{{ $label }} bg-turquoise/10 text-deep-blue ring-turquoise/40">Volledig vertaald</span>
                @endif
            </span>
        </a>
    </section>


    <!-- Pagina's -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold text-deep-blue">Pagina's</h2>

        @if ($paginas->isEmpty())
        <x-beheer.card class="mt-4">
            <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er zijn nog geen pagina's vastgelegd.</p>
        </x-beheer.card>
        @else
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($paginas as $pagina)
            <a href="{{ route('beheer.teksten.edit', $pagina['page']) }}" class="{{ $tegel }}">
                <span class="flex items-center gap-1 font-semibold text-deep-blue group-hover:text-turquoise">
                    {{ $pagina['naam'] }}
                    <x-site.icon name="chevron-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                </span>
                <span class="mt-0.5 block text-sm text-deep-blue/60">{{ $pagina['pad'] }}</span>

                <span class="mt-4 flex flex-wrap gap-2">
                    <span class="{{ $label }} bg-deep-blue/5 text-deep-blue/70 ring-deep-blue/15">{{ $pagina['aantal'] }} teksten</span>
                    @if ($pagina['ontbrekend'])
                    <span class="{{ $label }} bg-sand/25 text-deep-blue ring-sand">{{ $ontbrekend($pagina['ontbrekend']) }}</span>
                    @else
                    <span class="{{ $label }} bg-turquoise/10 text-deep-blue ring-turquoise/40">Volledig vertaald</span>
                    @endif
                </span>

                <span class="mt-auto block pt-4 text-xs text-deep-blue/60">
                    @if ($pagina['page']->editor)
                    Aangepast {{ $pagina['page']->updated_at->locale('nl')->diffForHumans() }} door {{ $pagina['page']->editor->first_name }}
                    @else
                    Nog niet aangepast in het beheer
                    @endif
                </span>
            </a>
            @endforeach
        </div>
        @endif
    </section>

</x-beheer-layout>
