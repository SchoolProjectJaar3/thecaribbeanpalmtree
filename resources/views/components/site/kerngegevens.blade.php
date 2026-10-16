@props(['woning'])

@php
$aantal = fn (int $aantal, string $enkelvoud, string $meervoud) => $aantal . ' ' . ($aantal === 1 ? $enkelvoud : $meervoud);

$kerngegevens = array_filter([
    ['icoon' => 'users', 'label' => 'Personen', 'waarde' => $aantal($woning->max_guests, 'gast', 'gasten')],
    ['icoon' => 'bed', 'label' => 'Slaapkamers', 'waarde' => $aantal($woning->bedrooms, 'kamer', 'kamers')],
    ['icoon' => 'bath', 'label' => 'Badkamers', 'waarde' => $aantal($woning->bathrooms, 'badkamer', 'badkamers')],
    $woning->surface_area_m2 ? ['icoon' => 'home', 'label' => 'Oppervlakte', 'waarde' => $woning->surface_area_m2 . ' m²'] : null,
    ['icoon' => 'paw', 'label' => 'Huisdieren', 'waarde' => $woning->pets_allowed ? 'Welkom' : 'Niet toegestaan'],
    $woning->city ? ['icoon' => 'pin', 'label' => 'Locatie', 'waarde' => $woning->city] : null,
]);
@endphp

<section class="border-b border-deep-blue/10 bg-white" aria-label="Kerngegevens">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <ul class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:flex lg:justify-between">
            @foreach ($kerngegevens as $item)
            <li class="flex items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                    <x-site.icon :name="$item['icoon']" />
                </span>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-deep-blue/60">{{ $item['label'] }}</p>
                    <p class="font-semibold text-deep-blue">{{ $item['waarde'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</section>
