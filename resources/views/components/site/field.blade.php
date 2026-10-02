@props(['name', 'label', 'type' => 'text', 'as' => 'input', 'required' => false, 'value' => null, 'hint' => null])

@php
$klasse = 'mt-1 block w-full rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise';
if ($errors->has($name)) { $klasse .= ' border-coral'; }
$waarde = old($name, $value);
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-deep-blue">
        {{ $label }}@if ($required)<span class="text-coral" aria-hidden="true"> *</span>@endif
    </label>

    @if ($as === 'textarea')
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="5"
        @required($required)
        {{ $attributes->merge(['class' => $klasse]) }}>{{ $waarde }}</textarea>
    @else
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $waarde }}"
        @required($required)
        {{ $attributes->merge(['class' => $klasse]) }}>
    @endif

    @if ($hint)
    <p class="mt-1 text-xs text-deep-blue/60">{{ $hint }}</p>
    @endif

    @error($name)
    <p class="mt-1 text-sm font-medium text-coral">{{ $message }}</p>
    @enderror
</div>
