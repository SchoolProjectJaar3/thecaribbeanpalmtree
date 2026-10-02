@props(['title' => 'Klaar voor zon, zee en rust?', 'tekst' => null])

@php
$contact = config('woning.contact');
$tekst ??= 'Bekijk welke data vrij zijn en vraag vrijblijvend je verblijf aan. We reageren ' . $contact['reactietijd'] . '.';
@endphp

<section class="bg-deep-blue py-16 sm:py-20">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white sm:text-4xl">{{ $title }}</h2>
        <p class="mt-4 text-lg leading-relaxed text-white/80">{{ $tekst }}</p>

        <div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
            <a
                href="/beschikbaarheid"
                class="inline-flex min-h-12 items-center justify-center rounded-lg bg-turquoise px-6 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2 focus:ring-offset-deep-blue">
                Bekijk beschikbaarheid
            </a>

            <a
                href="https://wa.me/{{ $contact['whatsapp'] }}"
                target="_blank"
                rel="noopener"
                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-deep-blue">
                <x-site.icon name="whatsapp" class="h-5 w-5" />
                WhatsApp
            </a>
        </div>

        <p class="mt-8 text-sm text-white/70">
            Of mail naar
            <a href="mailto:{{ $contact['email'] }}" class="font-semibold text-sand hover:text-white focus:outline-none focus:ring-2 focus:ring-sand">{{ $contact['email'] }}</a>
        </p>
    </div>
</section>
