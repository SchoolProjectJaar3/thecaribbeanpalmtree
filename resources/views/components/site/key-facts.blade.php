@props(['accommodation'])

@php
$quantity = fn (int $count, string $singular, string $plural) => $count . ' ' . ($count === 1 ? $singular : $plural);

$keyFacts = array_filter([
    ['icon' => 'users', 'label' => 'Personen', 'value' => $quantity($accommodation->max_guests, 'gast', 'gasten')],
    ['icon' => 'bed', 'label' => 'Slaapkamers', 'value' => $quantity($accommodation->bedrooms, 'kamer', 'kamers')],
    ['icon' => 'bath', 'label' => 'Badkamers', 'value' => $quantity($accommodation->bathrooms, 'badkamer', 'badkamers')],
    $accommodation->surface_area_m2 ? ['icon' => 'home', 'label' => 'Oppervlakte', 'value' => $accommodation->surface_area_m2 . ' m²'] : null,
    ['icon' => 'paw', 'label' => 'Huisdieren', 'value' => $accommodation->pets_allowed ? 'Welkom' : 'Niet toegestaan'],
    $accommodation->city ? ['icon' => 'pin', 'label' => 'Locatie', 'value' => $accommodation->city] : null,
]);
@endphp

<section class="border-b border-deep-blue/10 bg-white" aria-label="Kerngegevens">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <ul class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:flex lg:justify-between">
            @foreach ($keyFacts as $item)
            <li class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$item['icon']" />
                </span>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-deep-blue/60">{{ $item['label'] }}</p>
                    <p class="font-semibold text-deep-blue">{{ $item['value'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</section>
