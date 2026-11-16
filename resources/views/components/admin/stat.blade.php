@props(['label', 'value', 'icon', 'hint' => null, 'href' => null])

@php($tag = $href ? 'a' : 'div')

<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'flex items-start gap-4 rounded-xl border border-deep-blue/10 bg-white p-5 shadow-sm' . ($href ? ' transition hover:-translate-y-px hover:border-turquoise/40 hover:shadow-md focus:outline-none focus:ring-0 focus:ring-turquoise' : '')]) }}>
    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-turquoise/10 text-turquoise">
        <x-site.icon :name="$icon" class="h-6 w-6" />
    </span>
    <span class="min-w-0">
        <span class="block text-sm font-medium text-deep-blue/70">{{ $label }}</span>
        <span class="mt-1 block text-2xl font-bold text-deep-blue">{{ $value }}</span>
        @if ($hint)
        <span class="mt-0.5 block text-xs text-deep-blue/60">{{ $hint }}</span>
        @endif
    </span>
</{{ $tag }}>