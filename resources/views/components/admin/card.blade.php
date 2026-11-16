@props(['title' => null])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-deep-blue/10 bg-white shadow-sm']) }}>
    @if ($title || isset($action))
    <div class="flex items-center justify-between gap-4 border-b border-deep-blue/10 px-5 py-4">
        @if ($title)
        <h2 class="font-semibold text-deep-blue">{{ $title }}</h2>
        @endif

        @isset($action)
        <div class="shrink-0 text-sm">{{ $action }}</div>
        @endisset
    </div>
    @endif

    {{ $slot }}
</section>
