@props(['eyebrow' => null, 'title', 'intro' => null, 'image' => null])

<section class="relative isolate overflow-hidden bg-deep-blue">
    @if ($image)
    <img
        src="{{ asset('images/website_static/' . $image) }}"
        alt=""
        class="absolute inset-0 -z-10 h-full w-full object-cover"
        fetchpriority="high">
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-deep-blue/95 via-deep-blue/75 to-deep-blue/40" aria-hidden="true"></div>
    @else
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-deep-blue via-deep-blue to-turquoise/60" aria-hidden="true"></div>
    @endif

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        @if ($eyebrow)
        <p class="text-sm font-semibold uppercase tracking-widest text-sand">{{ $eyebrow }}</p>
        @endif

        <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight text-white sm:text-5xl">{{ $title }}</h1>

        @if ($intro)
        <p class="mt-5 max-w-2xl text-lg leading-relaxed text-white/85">{{ $intro }}</p>
        @endif

        @if (isset($slot) && trim((string) $slot) !== '')
        <div class="mt-8 flex flex-col gap-4 sm:flex-row">{{ $slot }}</div>
        @endif
    </div>
</section>
