<x-guest-layout>
    <x-slot:heading>Bevestig je wachtwoord</x-slot:heading>
    <x-slot:intro>Dit is een beveiligd onderdeel. Bevestig je wachtwoord om verder te gaan.</x-slot:intro>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Wachtwoord -->
        <div>
            <x-input-label for="password" value="Wachtwoord" />
            <x-text-input id="password" class="mt-1 block w-full"
                type="password"
                name="password"
                required autofocus autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Bevestigen
        </x-primary-button>
    </form>
</x-guest-layout>
