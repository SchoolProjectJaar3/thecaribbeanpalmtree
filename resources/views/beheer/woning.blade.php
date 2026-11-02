@php
$standaardtaal = $talen->firstWhere('is_default', true);
$invoer = fn (string $veld) => 'mt-1 block w-full rounded-lg bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise ' . ($errors->has($veld) ? 'border-coral' : 'border-deep-blue/20');
$tekst = fn (string $veld) => $talen->mapWithKeys(fn ($taal) => [$taal->code => $woning->translations->firstWhere('language_id', $taal->id)?->{$veld}])->all();
$onderdelen = [
    'kenmerken' => 'Kenmerken',
    'huisdieren' => 'Huisdieren',
    'verblijf' => 'In- en uitchecken',
    'beschrijving' => 'Beschrijving',
    'voorzieningen' => 'Voorzieningen',
    'adres' => 'Adres',
];
$huisdieren = [
    1 => ['Huisdieren zijn welkom', 'Gasten mogen hun huisdier meenemen.'],
    0 => ['Geen huisdieren', 'Huisdieren zijn niet toegestaan in de woning.'],
];
@endphp

<x-beheer-layout
    title="Woninggegevens"
    :description="'Pas de gegevens van de woning aan. Na het opslaan staan ze direct op de website. Laat je een vertaling leeg, dan toont de website de tekst in het ' . $standaardtaal?->name . '.'">

    <x-slot name="actions">
        <a
            href="{{ route('het-huis') }}"
            target="_blank"
            rel="noopener"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-deep-blue/20 bg-white px-4 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-0 focus:ring-turquoise">
            Bekijk op de website
            <x-site.icon name="external" class="h-4 w-4" />
        </a>
    </x-slot>

    <x-beheer.form-meldingen fout="De woninggegevens zijn niet opgeslagen. Controleer de gemarkeerde velden." />

    <form
        method="POST"
        action="{{ route('beheer.woning.update') }}"
        x-data="{ gewijzigd: false }"
        @input="gewijzigd = true"
        @submit="gewijzigd = false"
        @beforeunload.window="if (gewijzigd) { $event.preventDefault(); $event.returnValue = ''; }"
        class="grid grid-cols-1 gap-8 lg:grid-cols-[13rem_1fr]">
        @csrf
        @method('PUT')

        <!-- Onderdelen -->
        <nav aria-label="Onderdelen" class="hidden lg:sticky lg:top-24 lg:block lg:self-start">
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-deep-blue/50">Onderdelen</p>
            <ul class="mt-2 space-y-0.5">
                @foreach ($onderdelen as $sleutel => $naam)
                <li>
                    <a href="#{{ $sleutel }}" class="block rounded-lg px-3 py-1.5 text-sm font-medium text-deep-blue/80 transition-colors hover:bg-white hover:text-turquoise">{{ $naam }}</a>
                </li>
                @endforeach
            </ul>
        </nav>

        <div class="min-w-0 space-y-8">

            <!-- Kenmerken -->
            <x-beheer.card :title="$onderdelen['kenmerken']" id="kenmerken" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-2">
                    <x-beheer.field label="Naam van de woning" for="name" class="sm:col-span-2">
                        <input id="name" name="name" type="text" value="{{ old('name', $woning->name) }}" maxlength="150" required class="{{ $invoer('name') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Maximaal aantal gasten" for="max_guests" hint="Gasten kunnen niet voor meer personen reserveren.">
                        <input id="max_guests" name="max_guests" type="number" inputmode="numeric" min="1" max="50" step="1" value="{{ old('max_guests', $woning->max_guests) }}" required aria-describedby="max_guests-hint" class="{{ $invoer('max_guests') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Oppervlakte in m²" for="surface_area_m2" hint="Niet verplicht.">
                        <input id="surface_area_m2" name="surface_area_m2" type="number" inputmode="numeric" min="1" max="100000" step="1" value="{{ old('surface_area_m2', $woning->surface_area_m2) }}" aria-describedby="surface_area_m2-hint" class="{{ $invoer('surface_area_m2') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Aantal slaapkamers" for="bedrooms">
                        <input id="bedrooms" name="bedrooms" type="number" inputmode="numeric" min="0" max="50" step="1" value="{{ old('bedrooms', $woning->bedrooms) }}" required class="{{ $invoer('bedrooms') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Aantal badkamers" for="bathrooms">
                        <input id="bathrooms" name="bathrooms" type="number" inputmode="numeric" min="0" max="50" step="1" value="{{ old('bathrooms', $woning->bathrooms) }}" required class="{{ $invoer('bathrooms') }}">
                    </x-beheer.field>
                </div>
            </x-beheer.card>

            <!-- Huisdieren -->
            <x-beheer.card :title="$onderdelen['huisdieren']" id="huisdieren" class="scroll-mt-24">
                <fieldset class="px-5 py-5">
                    <legend class="sr-only">Zijn huisdieren toegestaan?</legend>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($huisdieren as $waarde => [$titel, $uitleg])
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-deep-blue/20 p-4 transition-colors hover:bg-sand-white has-[:checked]:border-turquoise has-[:checked]:bg-turquoise/5">
                            <input
                                type="radio"
                                name="pets_allowed"
                                value="{{ $waarde }}"
                                @checked((bool) old('pets_allowed', $woning->pets_allowed) === (bool) $waarde)
                                class="mt-0.5 border-deep-blue/30 text-turquoise focus:ring-turquoise">
                            <span>
                                <span class="block text-sm font-semibold text-deep-blue">{{ $titel }}</span>
                                <span class="mt-0.5 block text-sm text-deep-blue/70">{{ $uitleg }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <x-input-error :messages="$errors->get('pets_allowed')" class="mt-2" />
                </fieldset>
            </x-beheer.card>

            <!-- In- en uitchecken -->
            <x-beheer.card :title="$onderdelen['verblijf']" id="verblijf" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-2">
                    <x-beheer.field label="Inchecken vanaf" for="check_in_time">
                        <input id="check_in_time" name="check_in_time" type="time" step="60" value="{{ old('check_in_time', $woning->check_in_time) }}" class="{{ $invoer('check_in_time') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Uitchecken tot" for="check_out_time">
                        <input id="check_out_time" name="check_out_time" type="time" step="60" value="{{ old('check_out_time', $woning->check_out_time) }}" class="{{ $invoer('check_out_time') }}">
                    </x-beheer.field>
                </div>
            </x-beheer.card>

            <!-- Beschrijving -->
            <x-beheer.card :title="$onderdelen['beschrijving']" id="beschrijving" class="scroll-mt-24">
                <div class="divide-y divide-deep-blue/10">
                    <x-beheer.text-field
                        naam="vertalingen[short_description]"
                        veld="short_description"
                        label="Korte omschrijving"
                        hint="Staat op de homepage bij het huis. Maximaal 255 tekens."
                        :max="255"
                        :meerdere-regels="true"
                        :talen="$talen"
                        :waarden="$tekst('short_description')" />

                    <x-beheer.text-field
                        naam="vertalingen[description]"
                        veld="description"
                        label="Beschrijving"
                        hint="Staat op de pagina Het huis. Een lege regel begint een nieuwe alinea."
                        :max="5000"
                        :meerdere-regels="true"
                        :talen="$talen"
                        :waarden="$tekst('description')" />

                    <x-beheer.text-field
                        naam="vertalingen[house_rules]"
                        veld="house_rules"
                        label="Huisregels"
                        hint="Zet elke huisregel op een eigen regel. Het aantal gasten, de in- en uitchecktijden en het huisdierenbeleid worden er vanzelf bij gezet."
                        :max="5000"
                        :verplicht="false"
                        :meerdere-regels="true"
                        :talen="$talen"
                        :waarden="$tekst('house_rules')" />
                </div>
            </x-beheer.card>

            <!-- Voorzieningen -->
            <x-beheer.card :title="$onderdelen['voorzieningen']" id="voorzieningen" class="scroll-mt-24">
                <p class="border-b border-deep-blue/10 px-5 py-4 text-sm text-deep-blue/70">
                    Vink aan wat de woning heeft. Alleen aangevinkte voorzieningen staan op de website.
                </p>

                @if ($voorzieningen->isEmpty())
                <p class="px-5 py-8 text-center text-sm text-deep-blue/60">Er zijn nog geen voorzieningen. Voeg hieronder de eerste toe.</p>
                @else
                <ul class="divide-y divide-deep-blue/10">
                    @foreach ($voorzieningen as $voorziening)
                    @php
                    $sleutel = "voorzieningen.{$voorziening->id}";
                    $naam = "voorzieningen[{$voorziening->id}]";
                    $id = "voorziening-{$voorziening->id}";
                    @endphp
                    <li class="px-5 py-3" x-data="{ open: @js($errors->has("{$sleutel}.*")) }">
                        <div class="flex items-center gap-4">
                            <input type="hidden" name="{{ $naam }}[aanwezig]" value="0">
                            <input
                                id="{{ $id }}"
                                type="checkbox"
                                name="{{ $naam }}[aanwezig]"
                                value="1"
                                @checked(old("{$sleutel}.aanwezig", in_array($voorziening->id, $aanwezig)))
                                class="h-5 w-5 rounded border-deep-blue/30 text-turquoise focus:ring-turquoise">
                            <label for="{{ $id }}" class="flex min-w-0 flex-1 cursor-pointer items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sand/30 text-deep-blue">
                                    <x-site.icon :name="$voorziening->icon ?? 'check'" class="h-5 w-5" />
                                </span>
                                <span class="truncate font-medium text-deep-blue">{{ $voorziening->name($standaardtaal?->code) }}</span>
                            </label>
                            <button
                                type="button"
                                @click="open = ! open"
                                :aria-expanded="open.toString()"
                                aria-controls="{{ $id }}-velden"
                                class="flex min-h-11 shrink-0 items-center gap-1 rounded-lg px-2 text-sm font-medium text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise">
                                Aanpassen
                                <x-site.icon name="chevron-down" class="h-4 w-4 transition-transform" ::class="open ? 'rotate-180' : ''" />
                            </button>
                        </div>

                        <div id="{{ $id }}-velden" x-show="open" @unless ($errors->has("{$sleutel}.*")) x-cloak @endunless class="mt-3 grid gap-4 pb-2 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($talen as $taal)
                            <x-beheer.field :label="'Naam (' . $taal->name . ')'" :for="$id . '-naam-' . $taal->code" :error="$sleutel . '.naam.' . $taal->code">
                                <input
                                    id="{{ $id }}-naam-{{ $taal->code }}"
                                    name="{{ $naam }}[naam][{{ $taal->code }}]"
                                    type="text"
                                    lang="{{ $taal->code }}"
                                    maxlength="100"
                                    value="{{ old("{$sleutel}.naam.{$taal->code}", $voorziening->translations->firstWhere('language_id', $taal->id)?->name) }}"
                                    @required($taal->is_default)
                                    class="{{ $invoer("{$sleutel}.naam.{$taal->code}") }}">
                            </x-beheer.field>
                            @endforeach

                            <x-beheer.field label="Icoon" :for="$id . '-icon'" :error="$sleutel . '.icon'">
                                <select id="{{ $id }}-icon" name="{{ $naam }}[icon]" class="{{ $invoer("{$sleutel}.icon") }}">
                                    <option value="">Standaard (vinkje)</option>
                                    @foreach ($iconen as $icoon => $omschrijving)
                                    <option value="{{ $icoon }}" @selected(old("{$sleutel}.icon", $voorziening->icon) === $icoon)>{{ $omschrijving }}</option>
                                    @endforeach
                                </select>
                            </x-beheer.field>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="border-t border-deep-blue/10 bg-sand-white px-5 py-5">
                    <p class="text-sm font-semibold text-deep-blue">Nieuwe voorziening toevoegen</p>
                    <p class="mt-0.5 text-xs text-deep-blue/60">Wordt na het opslaan aangevinkt en op de website getoond. Laat leeg als je niets wilt toevoegen.</p>

                    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($talen as $taal)
                        <x-beheer.field :label="'Naam (' . $taal->name . ')'" :for="'nieuwe-voorziening-' . $taal->code" :error="'nieuwe_voorziening.naam.' . $taal->code">
                            <input
                                id="nieuwe-voorziening-{{ $taal->code }}"
                                name="nieuwe_voorziening[naam][{{ $taal->code }}]"
                                type="text"
                                lang="{{ $taal->code }}"
                                maxlength="100"
                                value="{{ old("nieuwe_voorziening.naam.{$taal->code}") }}"
                                class="{{ $invoer("nieuwe_voorziening.naam.{$taal->code}") }}">
                        </x-beheer.field>
                        @endforeach

                        <x-beheer.field label="Icoon" for="nieuwe-voorziening-icon" error="nieuwe_voorziening.icon">
                            <select id="nieuwe-voorziening-icon" name="nieuwe_voorziening[icon]" class="{{ $invoer('nieuwe_voorziening.icon') }}">
                                <option value="">Standaard (vinkje)</option>
                                @foreach ($iconen as $icoon => $omschrijving)
                                <option value="{{ $icoon }}" @selected(old('nieuwe_voorziening.icon') === $icoon)>{{ $omschrijving }}</option>
                                @endforeach
                            </select>
                        </x-beheer.field>
                    </div>
                </div>
            </x-beheer.card>

            <!-- Adres -->
            <x-beheer.card :title="$onderdelen['adres']" id="adres" class="scroll-mt-24">
                <div class="grid gap-6 px-5 py-5 sm:grid-cols-6">
                    <x-beheer.field label="Straat" for="street" class="sm:col-span-4">
                        <input id="street" name="street" type="text" maxlength="150" autocomplete="address-line1" value="{{ old('street', $woning->street) }}" class="{{ $invoer('street') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Huisnummer" for="house_number" class="sm:col-span-2">
                        <input id="house_number" name="house_number" type="text" maxlength="10" value="{{ old('house_number', $woning->house_number) }}" class="{{ $invoer('house_number') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Postcode" for="postal_code" class="sm:col-span-2">
                        <input id="postal_code" name="postal_code" type="text" maxlength="10" autocomplete="postal-code" value="{{ old('postal_code', $woning->postal_code) }}" class="{{ $invoer('postal_code') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Plaats" for="city" class="sm:col-span-4">
                        <input id="city" name="city" type="text" maxlength="100" autocomplete="address-level2" value="{{ old('city', $woning->city) }}" class="{{ $invoer('city') }}">
                    </x-beheer.field>

                    <x-beheer.field label="Landcode" for="country_code" hint="Twee letters, bijvoorbeeld CW voor Curaçao." class="sm:col-span-3">
                        <input id="country_code" name="country_code" type="text" maxlength="2" autocomplete="country" value="{{ old('country_code', $woning->country_code) }}" aria-describedby="country_code-hint" class="{{ $invoer('country_code') }} uppercase">
                    </x-beheer.field>
                </div>
            </x-beheer.card>

            <!-- Opslaan -->
            <x-beheer.opslaan-balk />
        </div>
    </form>

</x-beheer-layout>
