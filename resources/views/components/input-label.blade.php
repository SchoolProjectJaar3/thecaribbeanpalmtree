@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-deep-blue']) }}>
    {{ $value ?? $slot }}
</label>
