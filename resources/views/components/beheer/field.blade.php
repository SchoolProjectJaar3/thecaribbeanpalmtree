@props(['label', 'for', 'hint' => null, 'error' => null])

<div {{ $attributes }}>
    <label for="{{ $for }}" class="block text-sm font-semibold text-deep-blue">{{ $label }}</label>
    @if ($hint)
    <p id="{{ $for }}-hint" class="mt-0.5 text-xs text-deep-blue/60">{{ $hint }}</p>
    @endif

    {{ $slot }}

    <x-input-error :messages="$errors->get($error ?? $for)" class="mt-2" />
</div>
