@props(['eyebrow' => null, 'title', 'intro' => null, 'center' => false])

<div {{ $attributes->merge(['class' => $center ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl']) }}>
    @if ($eyebrow)
    <p class="text-sm font-semibold uppercase tracking-widest text-turquoise">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-2 text-3xl font-bold text-deep-blue sm:text-4xl">{{ $title }}</h2>
    @if ($intro)
    <p class="mt-4 leading-relaxed text-deep-blue/80">{{ $intro }}</p>
    @endif
</div>
