<x-guest-layout>
    <x-slot:heading>Wachtwoord vergeten</x-slot:heading>
    <x-slot:intro>Geen probleem. Vul je e-mailadres in en we sturen je een link waarmee je een nieuw wachtwoord kunt kiezen.</x-slot:intro>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="grid gap-5">
        @csrf

        <!-- E-mailadres -->
        <div>
            <x-input-label for="email" value="E-mailadres" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Herstellink versturen
        </x-primary-button>
    </form>

    <x-slot:footer>
        Weet je het weer?
        <a class="rounded-md font-semibold text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-2" href="{{ route('login') }}">Terug naar inloggen</a>
    </x-slot:footer>
</x-guest-layout>