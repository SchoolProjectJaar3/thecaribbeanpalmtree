@php
$defaultLanguage = $languages->firstWhere('is_default', true);
$inputClass = fn (string $field) => 'mt-1 block w-full rounded-lg bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise ' . ($errors->has($field) ? 'border-coral' : 'border-deep-blue/20');
$texts = fn (string $field) => $languages->mapWithKeys(fn ($language) => [$language->code => $accommodation->translations->firstWhere('language_id', $language->id)?->{$field}])->all();
$sections = [
    'features' => 'Kenmerken',
    'pets' => 'Huisdieren',
    'stay' => 'In- en uitchecken',
    'description' => 'Beschrijving',
    'facilities' => 'Voorzieningen',
    'address' => 'Adres',
];
$petOptions = [
    1 => ['Huisdieren zijn welkom', 'Gasten mogen hun huisdier meenemen.'],
    0 => ['Geen huisdieren', 'Huisdieren zijn niet toegestaan in de woning.'],
];
@endphp

<x-admin-layout
    title="Woninggegevens"
    :description="'Pas de gegevens van de woning aan. Na het opslaan staan ze direct op de website. Laat je een vertaling leeg, dan toont de website de tekst in het ' . $defaultLanguage?->name . '.'">

    <x-slot name="actions">
        <a
            href="{{ route('property') }}"
            target="_blank"
            rel="noopener"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-deep-blue/20 bg-white px-4 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-0 focus:ring-turquoise">
            Bekijk op de website
            <x-site.icon name="external" class="h-4 w-4" />
        </a>
    </x-slot>

    <x-admin.form-messages error="De woninggegevens zijn niet opgeslagen. Controleer de gemarkeerde velden." />

    <form
        method="POST"
        action="{{ route('admin.accommodation.update') }}"
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
                @foreach ($sections as $key => $title)
                <li>
                    <a href="#{{ $key }}" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">{{ $title }}</a>
                </li>
                @endforeach
            </ul>
        </nav>

        <div class="min-w-0 space-y-8">

            <!-- Features -->
            <x-admin.card :title="$sections['features']" id="features" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-2">
                    <x-admin.field label="Naam van de woning" for="name" class="sm:col-span-2">
                        <input id="name" name="name" type="text" value="{{ old('name', $accommodation->name) }}" maxlength="150" required class="{{ $inputClass('name') }}">
                    </x-admin.field>

                    <x-admin.field label="Maximaal aantal gasten" for="max_guests" hint="Gasten kunnen niet voor meer personen reserveren.">
                        <input id="max_guests" name="max_guests" type="number" inputmode="numeric" min="1" max="50" step="1" value="{{ old('max_guests', $accommodation->max_guests) }}" required aria-describedby="max_guests-hint" class="{{ $inputClass('max_guests') }}">
                    </x-admin.field>

                    <x-admin.field label="Oppervlakte in m²" for="surface_area_m2" hint="Niet verplicht.">
                        <input id="surface_area_m2" name="surface_area_m2" type="number" inputmode="numeric" min="1" max="100000" step="1" value="{{ old('surface_area_m2', $accommodation->surface_area_m2) }}" aria-describedby="surface_area_m2-hint" class="{{ $inputClass('surface_area_m2') }}">
                    </x-admin.field>

                    <x-admin.field label="Aantal slaapkamers" for="bedrooms">
                        <input id="bedrooms" name="bedrooms" type="number" inputmode="numeric" min="0" max="50" step="1" value="{{ old('bedrooms', $accommodation->bedrooms) }}" required class="{{ $inputClass('bedrooms') }}">
                    </x-admin.field>

                    <x-admin.field label="Aantal badkamers" for="bathrooms">
                        <input id="bathrooms" name="bathrooms" type="number" inputmode="numeric" min="0" max="50" step="1" value="{{ old('bathrooms', $accommodation->bathrooms) }}" required class="{{ $inputClass('bathrooms') }}">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <!-- Pets -->
            <x-admin.card :title="$sections['pets']" id="pets" class="scroll-mt-24">
                <fieldset class="px-5 py-5">
                    <legend class="sr-only">Zijn huisdieren toegestaan?</legend>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($petOptions as $value => [$title, $explanation])
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-deep-blue/20 p-4 transition-colors hover:bg-sand-white has-[:checked]:border-turquoise has-[:checked]:bg-turquoise/5">
                            <input
                                type="radio"
                                name="pets_allowed"
                                value="{{ $value }}"
                                @checked((bool) old('pets_allowed', $accommodation->pets_allowed) === (bool) $value)
                                class="mt-0.5 border-deep-blue/30 text-turquoise focus:ring-turquoise">
                            <span>
                                <span class="block text-sm font-semibold text-deep-blue">{{ $title }}</span>
                                <span class="mt-0.5 block text-sm text-deep-blue/70">{{ $explanation }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <x-input-error :messages="$errors->get('pets_allowed')" class="mt-2" />
                </fieldset>
            </x-admin.card>

            <!-- Check-in and check-out -->
            <x-admin.card :title="$sections['stay']" id="stay" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-2">
                    <x-admin.field label="Inchecken vanaf" for="check_in_time">
                        <input id="check_in_time" name="check_in_time" type="time" step="60" value="{{ old('check_in_time', $accommodation->check_in_time) }}" class="{{ $inputClass('check_in_time') }}">
                    </x-admin.field>

                    <x-admin.field label="Uitchecken tot" for="check_out_time">
                        <input id="check_out_time" name="check_out_time" type="time" step="60" value="{{ old('check_out_time', $accommodation->check_out_time) }}" class="{{ $inputClass('check_out_time') }}">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <!-- Description -->
            <x-admin.card :title="$sections['description']" id="description" class="scroll-mt-24">
                <div class="divide-y divide-deep-blue/10">
                    <x-admin.text-field
                        name="translations[short_description]"
                        field="short_description"
                        label="Korte omschrijving"
                        hint="Staat op de homepage bij het huis. Maximaal 255 tekens."
                        :max="255"
                        :multiline="true"
                        :languages="$languages"
                        :values="$texts('short_description')" />

                    <x-admin.text-field
                        name="translations[description]"
                        field="description"
                        label="Beschrijving"
                        hint="Staat op de pagina Het huis. Een lege regel begint een nieuwe alinea."
                        :max="5000"
                        :multiline="true"
                        :languages="$languages"
                        :values="$texts('description')" />

                    <x-admin.text-field
                        name="translations[house_rules]"
                        field="house_rules"
                        label="Huisregels"
                        hint="Zet elke huisregel op een eigen regel. Het aantal gasten, de in- en uitchecktijden en het huisdierenbeleid worden er vanzelf bij gezet."
                        :max="5000"
                        :required="false"
                        :multiline="true"
                        :languages="$languages"
                        :values="$texts('house_rules')" />
                </div>
            </x-admin.card>

            <!-- Facilities -->
            <x-admin.card :title="$sections['facilities']" id="facilities" class="scroll-mt-24">
                <p class="border-b border-deep-blue/10 px-5 py-4 text-sm text-deep-blue/70">
                    Vink aan wat de woning heeft. Alleen aangevinkte voorzieningen staan op de website.
                </p>

                @if ($facilities->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-deep-blue/60">Er zijn nog geen voorzieningen. Voeg hieronder de eerste toe.</p>
                @else
                <ul class="divide-y divide-deep-blue/10">
                    @foreach ($facilities as $facility)
                    @php
                    $key = "facilities.{$facility->id}";
                    $name = "facilities[{$facility->id}]";
                    $id = "facility-{$facility->id}";
                    @endphp
                    <li class="px-5 py-3" x-data="{ open: @js($errors->has("{$key}.*")) }">
                        <div class="flex items-center gap-4">
                            <input type="hidden" name="{{ $name }}[selected]" value="0">
                            <input
                                id="{{ $id }}"
                                type="checkbox"
                                name="{{ $name }}[selected]"
                                value="1"
                                @checked(old("{$key}.selected", in_array($facility->id, $selected)))
                                class="h-5 w-5 rounded border-deep-blue/30 text-turquoise focus:ring-turquoise">
                            <label for="{{ $id }}" class="flex min-w-0 flex-1 cursor-pointer items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                                    <x-site.icon :name="$facility->icon ?? 'check'" class="h-5 w-5" />
                                </span>
                                <span class="truncate font-medium text-deep-blue">{{ $facility->name($defaultLanguage?->code) }}</span>
                            </label>
                            <button
                                type="button"
                                @click="open = ! open"
                                :aria-expanded="open.toString()"
                                aria-controls="{{ $id }}-fields"
                                class="flex min-h-11 shrink-0 items-center gap-1 rounded-lg px-2 text-sm font-medium text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                                Aanpassen
                                <x-site.icon name="chevron-down" class="h-4 w-4 transition-transform" ::class="open ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <div id="{{ $id }}-fields" x-show="open" @unless ($errors->has("{$key}.*")) x-cloak @endunless class="mt-3 grid gap-4 pb-2 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($languages as $language)
                            <x-admin.field :label="'Naam (' . $language->name . ')'" :for="$id . '-name-' . $language->code" :error="$key . '.name.' . $language->code">
                                <input
                                    id="{{ $id }}-name-{{ $language->code }}"
                                    name="{{ $name }}[name][{{ $language->code }}]"
                                    type="text"
                                    lang="{{ $language->code }}"
                                    maxlength="100"
                                    value="{{ old("{$key}.name.{$language->code}", $facility->translations->firstWhere('language_id', $language->id)?->name) }}"
                                    @required($language->is_default)
                                    class="{{ $inputClass("{$key}.name.{$language->code}") }}">
                            </x-admin.field>
                            @endforeach

                            <x-admin.field label="Icoon" :for="$id . '-icon'" :error="$key . '.icon'">
                                <select id="{{ $id }}-icon" name="{{ $name }}[icon]" class="{{ $inputClass("{$key}.icon") }}">
                                    <option value="">Standaard (vinkje)</option>
                                    @foreach ($icons as $icon => $description)
                                    <option value="{{ $icon }}" @selected(old("{$key}.icon", $facility->icon) === $icon)>{{ $description }}</option>
                                    @endforeach
                                </select>
                            </x-admin.field>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="border-t border-deep-blue/10 bg-sand-white px-5 py-5">
                    <p class="text-sm font-semibold text-deep-blue">Nieuwe voorziening toevoegen</p>
                    <p class="mt-0.5 text-xs text-deep-blue/60">Wordt na het opslaan aangevinkt en op de website getoond. Laat leeg als je niets wilt toevoegen.</p>

                    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($languages as $language)
                        <x-admin.field :label="'Naam (' . $language->name . ')'" :for="'new-facility-' . $language->code" :error="'new_facility.name.' . $language->code">
                            <input
                                id="new-facility-{{ $language->code }}"
                                name="new_facility[name][{{ $language->code }}]"
                                type="text"
                                lang="{{ $language->code }}"
                                maxlength="100"
                                value="{{ old("new_facility.name.{$language->code}") }}"
                                class="{{ $inputClass("new_facility.name.{$language->code}") }}">
                        </x-admin.field>
                        @endforeach

                        <x-admin.field label="Icoon" for="new-facility-icon" error="new_facility.icon">
                            <select id="new-facility-icon" name="new_facility[icon]" class="{{ $inputClass('new_facility.icon') }}">
                                <option value="">Standaard (vinkje)</option>
                                @foreach ($icons as $icon => $description)
                                <option value="{{ $icon }}" @selected(old('new_facility.icon') === $icon)>{{ $description }}</option>
                                @endforeach
                            </select>
                        </x-admin.field>
                    </div>
                </div>
            </x-admin.card>

            <!-- Address -->
            <x-admin.card :title="$sections['address']" id="address" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-6">
                    <x-admin.field label="Straat" for="street" class="sm:col-span-4">
                        <input id="street" name="street" type="text" maxlength="150" autocomplete="address-line1" value="{{ old('street', $accommodation->street) }}" class="{{ $inputClass('street') }}">
                    </x-admin.field>

                    <x-admin.field label="Huisnummer" for="house_number" class="sm:col-span-2">
                        <input id="house_number" name="house_number" type="text" maxlength="10" value="{{ old('house_number', $accommodation->house_number) }}" class="{{ $inputClass('house_number') }}">
                    </x-admin.field>

                    <x-admin.field label="Postcode" for="postal_code" class="sm:col-span-2">
                        <input id="postal_code" name="postal_code" type="text" maxlength="10" autocomplete="postal-code" value="{{ old('postal_code', $accommodation->postal_code) }}" class="{{ $inputClass('postal_code') }}">
                    </x-admin.field>

                    <x-admin.field label="Plaats" for="city" class="sm:col-span-4">
                        <input id="city" name="city" type="text" maxlength="100" autocomplete="address-level2" value="{{ old('city', $accommodation->city) }}" class="{{ $inputClass('city') }}">
                    </x-admin.field>

                    <x-admin.field label="Landcode" for="country_code" hint="Twee letters, bijvoorbeeld CW voor Curaçao." class="sm:col-span-3">
                        <input id="country_code" name="country_code" type="text" maxlength="2" autocomplete="country" value="{{ old('country_code', $accommodation->country_code) }}" aria-describedby="country_code-hint" class="{{ $inputClass('country_code') }} uppercase">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <!-- Save -->
            <x-admin.save-bar />
        </div>
    </form>

</x-admin-layout>
