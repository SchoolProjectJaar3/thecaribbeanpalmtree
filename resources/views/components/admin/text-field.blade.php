@props(['name', 'field', 'label', 'languages', 'values' => [], 'hint' => null, 'max' => null, 'allLanguagesRequired' => false, 'required' => true, 'multiline' => null])

@php
$key = str_replace(['[', ']'], ['.', ''], $name);
$multiline ??= \Illuminate\Support\Str::is(['intro', 'text', '*_intro', '*_text'], $field);
$longest = collect($values)->sortByDesc(fn ($value) => mb_strlen((string) $value))->first() ?? '';
$rows = max(3, min(12, (int) ceil(mb_strlen($longest) / 55) + substr_count($longest, "\n")));
@endphp

<div class="px-5 py-5">
    <p class="text-sm font-semibold text-deep-blue">{{ $label }}</p>
    @if ($hint)
    <p class="mt-0.5 text-xs text-deep-blue/60">{{ $hint }}</p>
    @endif

    <div class="mt-3 grid gap-4 md:grid-cols-2">
        @foreach ($languages as $language)
        @php
        $input = "{$key}.{$language->code}";
        $id = \Illuminate\Support\Str::slug(str_replace('.', '-', $input));
        $value = old($input, $values[$language->code] ?? null);
        $isRequired = $allLanguagesRequired || ($required && $language->is_default);
        $hasError = $errors->has($input);
        $class = 'mt-1 block w-full rounded-lg bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise ' . ($hasError ? 'border-coral' : 'border-deep-blue/20');
        @endphp
        <div>
            <label for="{{ $id }}" class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-deep-blue/60">
                {{ $language->name }}
                @if (! $isRequired && ! $language->is_default && blank($values[$language->code] ?? null))
                <span class="rounded-full bg-sand/30 px-2 py-0.5 text-[0.65rem] normal-case tracking-normal text-deep-blue">Nog niet vertaald</span>
                @endif
            </label>

            @if ($multiline)
            <textarea
                id="{{ $id }}"
                name="{{ $name }}[{{ $language->code }}]"
                rows="{{ $rows }}"
                lang="{{ $language->code }}"
                @if ($max) maxlength="{{ $max }}" @endif
                @required($isRequired)
                class="{{ $class }}">{{ $value }}</textarea>
            @else
            <input
                id="{{ $id }}"
                type="text"
                name="{{ $name }}[{{ $language->code }}]"
                value="{{ $value }}"
                lang="{{ $language->code }}"
                @if ($max) maxlength="{{ $max }}" @endif
                @required($isRequired)
                class="{{ $class }}">
            @endif

            <x-input-error :messages="$errors->get($input)" class="mt-2" />
        </div>
        @endforeach
    </div>
</div>
