@props(['action', 'secties', 'waarden', 'talen'])

@if (session('status'))
<div role="status" class="mb-6 rounded-lg border border-turquoise/30 bg-turquoise/10 px-4 py-3 text-sm font-medium text-deep-blue">
    {{ session('status') }}
</div>
@endif

@if ($errors->any())
<div role="alert" class="mb-6 rounded-lg border border-coral/30 bg-coral/10 px-4 py-3 text-sm font-medium text-deep-blue">
    De teksten zijn niet opgeslagen. Controleer de gemarkeerde velden.
</div>
@endif

<form
    method="POST"
    action="{{ $action }}"
    x-data="{ gewijzigd: false }"
    @input="gewijzigd = true"
    @submit="gewijzigd = false"
    @beforeunload.window="if (gewijzigd) { $event.preventDefault(); $event.returnValue = ''; }"
    class="grid grid-cols-1 gap-8 lg:grid-cols-[13rem_1fr]">
    @csrf
    @method('PUT')

    <!-- Onderdelen -->
    <nav aria-label="Onderdelen" class="hidden lg:sticky lg:top-24 lg:block lg:self-start">
        <p class="px-3 text-xs font-semibold uppercase tracking-widest text-deep-blue/50">Onderdelen</p>
        <ul class="mt-2 space-y-0.5">
            @isset($pagina)
            <li>
                <a href="#pagina" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">Pagina</a>
            </li>
            @endisset
            @foreach ($secties as $sleutel => $sectie)
            <li>
                <a href="#sectie-{{ $sleutel }}" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">{{ $sectie['naam'] }}</a>
            </li>
            @endforeach
        </ul>
    </nav>

    <div class="min-w-0 space-y-8">
        {{ $pagina ?? '' }}

        @foreach ($secties as $sleutel => $sectie)
        <x-beheer.card :title="$sectie['naam']" id="sectie-{{ $sleutel }}" class="scroll-mt-24">
            @foreach ($sectie['groepen'] as $groep)
            @if ($groep['naam'])
            <p class="border-t border-deep-blue/10 bg-sand-white px-5 py-2 text-xs font-semibold uppercase tracking-widest text-turquoise">{{ $groep['naam'] }}</p>
            @endif

            <div class="divide-y divide-deep-blue/10">
                @foreach ($groep['velden'] as $veld => $definitie)
                <x-beheer.text-field
                    :naam="'teksten[' . $sleutel . '][' . $veld . ']'"
                    :veld="$veld"
                    :label="$definitie['label']"
                    :hint="$definitie['hint']"
                    :talen="$talen"
                    :waarden="$waarden[$sleutel][$veld] ?? []" />
                @endforeach
            </div>
            @endforeach
        </x-beheer.card>
        @endforeach

        <!-- Opslaan -->
        <div class="sticky bottom-4 z-20 flex items-center justify-between gap-4 rounded-xl border border-deep-blue/10 bg-white/95 p-3 shadow-lg backdrop-blur sm:p-4">
            <p class="text-sm text-deep-blue/70" aria-live="polite">
                <span x-show="gewijzigd" x-cloak class="font-medium text-coral">Je hebt wijzigingen die nog niet zijn opgeslagen.</span>
                <span x-show="! gewijzigd" class="hidden sm:inline">Wijzigingen worden bewaard zodra je op Opslaan klikt.</span>
            </p>
            <x-primary-button>Opslaan</x-primary-button>
        </div>
    </div>
</form>
