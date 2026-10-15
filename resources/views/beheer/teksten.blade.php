@php
$sectionNames = [
'hero' => 'Bovenaan de pagina (hero)',
'house' => 'Het huis',
'surroundings' => 'Omgeving',
];

$fieldNames = [
'title' => 'Titel',
'description' => 'Beschrijving',
'button_text' => 'Tekst op de knop',
'text' => 'Tekst',
];

$fieldClass = 'mt-1 block w-full rounded-lg border border-deep-blue/20 bg-white px-3 py-2 text-deep-blue shadow-sm focus:border-turquoise focus:outline-none focus:ring-1 focus:ring-turquoise';
@endphp

<x-beheer-layout
    title="Teksten"
    description="Pas de teksten op de homepagina aan, in het Nederlands en het Engels.">

    @if (session('status'))
    <div role="status" class="mb-6 rounded-lg border border-turquoise/30 bg-turquoise/10 px-4 py-3 text-sm font-medium text-deep-blue">
        {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div role="alert" class="mb-6 rounded-lg border border-coral/30 bg-coral/10 px-4 py-3 text-sm font-medium text-deep-blue">
        De teksten zijn niet opgeslagen. Controleer de gemarkeerde velden.
    </div>
    @endif

    @if (! $page)
    <x-beheer.card>
        <p class="px-5 py-10 text-center text-sm text-deep-blue/60">Er zijn nog geen teksten vastgelegd.</p>
    </x-beheer.card>
    @else
    <form method="POST" action="{{ route('beheer.teksten.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        @foreach ($page->sections as $section)
        <x-beheer.card :title="$sectionNames[$section->key] ?? $section->key">
            <div class="divide-y divide-deep-blue/10">
                @foreach ($section->contents->groupBy('key') as $field => $texts)
                <div class="px-5 py-5">
                    <p class="text-sm font-semibold text-deep-blue">{{ $fieldNames[$field] ?? $field }}</p>

                    <div class="mt-3 grid gap-4 md:grid-cols-2">
                        @foreach ($languages as $language)
                        @php($item = $texts->firstWhere('language_id', $language->id))
                        @continue(! $item)

                        <div>
                            <label for="content-{{ $item->id }}" class="block text-xs font-semibold uppercase tracking-wider text-deep-blue/60">{{ $language->name }}</label>

                            @if (in_array($field, ['title', 'button_text']))
                            <input
                                id="content-{{ $item->id }}"
                                type="text"
                                name="content[{{ $item->id }}]"
                                value="{{ old('content.' . $item->id, $item->content) }}"
                                class="{{ $fieldClass }}">
                            @else
                            <textarea
                                id="content-{{ $item->id }}"
                                name="content[{{ $item->id }}]"
                                rows="4"
                                class="{{ $fieldClass }}">{{ old('content.' . $item->id, $item->content) }}</textarea>
                            @endif

                            <x-input-error :messages="$errors->get('content.' . $item->id)" class="mt-2" />
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </x-beheer.card>
        @endforeach

        <div class="flex justify-end">
            <x-primary-button>Opslaan</x-primary-button>
        </div>
    </form>
    @endif

</x-beheer-layout>