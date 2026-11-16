@props(['action', 'sections', 'values', 'languages'])

<x-admin.form-messages error="De teksten zijn niet opgeslagen. Controleer de gemarkeerde velden." />

<form
    method="POST"
    action="{{ $action }}"
    x-data="{ changed: false }"
    @input="changed = true"
    @submit="changed = false"
    @beforeunload.window="if (changed) { $event.preventDefault(); $event.returnValue = ''; }"
    class="grid grid-cols-1 gap-8 lg:grid-cols-[13rem_1fr]">
    @csrf
    @method('PUT')

    <!-- Sections -->
    <nav aria-label="Onderdelen" class="hidden lg:sticky lg:top-24 lg:block lg:self-start">
        <p class="px-3 text-xs font-semibold uppercase tracking-widest text-deep-blue/50">Onderdelen</p>
        <ul class="mt-2 space-y-0.5">
            @isset($pageFields)
            <li>
                <a href="#page" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">Pagina</a>
            </li>
            @endisset
            @foreach ($sections as $key => $section)
            <li>
                <a href="#section-{{ $key }}" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">{{ $section['name'] }}</a>
            </li>
            @endforeach
        </ul>
    </nav>

    <div class="min-w-0 space-y-8">
        {{ $pageFields ?? '' }}

        @foreach ($sections as $key => $section)
        <x-admin.card :title="$section['name']" id="section-{{ $key }}" class="scroll-mt-24">
            @foreach ($section['groups'] as $group)
            @if ($group['name'])
            <p class="border-t border-deep-blue/10 bg-sand-white px-5 py-2 text-xs font-semibold uppercase tracking-widest text-turquoise">{{ $group['name'] }}</p>
            @endif

            <div class="divide-y divide-deep-blue/10">
                @foreach ($group['fields'] as $field => $definition)
                <x-admin.text-field
                    :name="'texts[' . $key . '][' . $field . ']'"
                    :field="$field"
                    :label="$definition['label']"
                    :hint="$definition['hint']"
                    :languages="$languages"
                    :values="$values[$key][$field] ?? []" />
                @endforeach
            </div>
            @endforeach
        </x-admin.card>
        @endforeach

        <!-- Save -->
        <x-admin.save-bar />
    </div>
</form>
