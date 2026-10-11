@props(['features' => []])

<x-beheer.card>
    <div class="px-6 py-12 text-center sm:px-12">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sand/25 text-deep-blue">
            <x-site.icon name="tools" class="h-7 w-7" />
        </span>

        <h2 class="mt-5 text-lg font-semibold text-deep-blue">Dit onderdeel is in ontwikkeling</h2>
        <p class="mx-auto mt-2 max-w-xl text-deep-blue/70">Binnenkort beheer je dit onderdeel hier zelf. Tot die tijd blijven de gegevens op de website ongewijzigd.</p>

        @if ($features)
        <div class="mx-auto mt-8 max-w-xl rounded-xl bg-sand-white p-6 text-left">
            <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">Wat je hier straks kunt</p>
            <ul class="mt-4 space-y-3">
                @foreach ($features as $feature)
                <li class="flex items-start gap-3 text-deep-blue/80">
                    <x-site.icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-turquoise" />
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{ $slot }}
    </div>
</x-beheer.card>
