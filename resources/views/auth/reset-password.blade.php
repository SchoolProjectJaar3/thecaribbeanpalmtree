<x-guest-layout>
    <x-slot:heading>Nieuw wachtwoord</x-slot:heading>
    <x-slot:intro>Kies een nieuw wachtwoord voor je account.</x-slot:intro>

    <form method="POST" action="{{ route('password.store') }}" class="grid gap-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- E-mailadres -->
        <div>
            <x-input-label for="email" value="E-mailadres" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Wachtwoord -->
        <div>
            <x-input-label for="password" value="Nieuw wachtwoord" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
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
            Wachtwoord opslaan
        </x-primary-button>
    </form>
</x-guest-layout>
