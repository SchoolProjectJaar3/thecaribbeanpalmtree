@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border border-turquoise/40 bg-turquoise/10 p-4 text-sm font-medium text-deep-blue']) }} role="status">
        <x-site.icon name="check" class="h-5 w-5 shrink-0 text-turquoise" />
        <p>{{ $status }}</p>
    </div>
@endif
