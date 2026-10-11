<x-guest-layout>
    <x-slot:heading>Account aanmaken</x-slot:heading>
    <x-slot:intro>Vul je gegevens in om een account aan te maken.</x-slot:intro>

    <form method="POST" action="{{ route('register') }}" class="grid gap-5">
        @csrf

        <!-- Naam -->
        <div class="grid gap-5 sm:grid-cols-[1fr_6rem]">
            <div>
                <x-input-label for="first_name" value="Voornaam" />
                <x-text-input id="first_name" class="mt-1 block w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="infix" value="Tussenv." />
                <x-text-input id="infix" class="mt-1 block w-full" type="text" name="infix" :value="old('infix')" />
                <x-input-error :messages="$errors->get('infix')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="last_name" value="Achternaam" />
            <x-text-input id="last_name" class="mt-1 block w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- E-mailadres -->
        <div>
            <x-input-label for="email" value="E-mailadres" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Wachtwoord -->
        <div>
            <x-input-label for="password" value="Wachtwoord" />
            <x-text-input id="password" class="mt-1 block w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Wachtwoord bevestigen -->
        <div>
            <x-input-label for="password_confirmation" value="Wachtwoord bevestigen" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Account aanmaken
        </x-primary-button>
    </form>

    <x-slot:footer>
        Heb je al een account?
        <a class="rounded-md font-semibold text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2" href="{{ route('login') }}">Inloggen</a>
    </x-slot:footer>
</x-guest-layout>
