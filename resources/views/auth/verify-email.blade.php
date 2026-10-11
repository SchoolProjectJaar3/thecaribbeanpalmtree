<x-guest-layout>
    <x-slot:heading>Bevestig je e-mailadres</x-slot:heading>
    <x-slot:intro>Bedankt voor je registratie! Klik op de link in de e-mail die we je net hebben gestuurd. Niets ontvangen? Dan sturen we je graag een nieuwe.</x-slot:intro>

    @if (session('status') == 'verification-link-sent')
    <x-auth-session-status class="mb-6" status="Er is een nieuwe verificatielink verstuurd naar het e-mailadres dat je bij de registratie hebt opgegeven." />
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="w-full sm:w-auto">
                Verificatiemail opnieuw versturen
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="text-sm rounded-md font-semibold text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                Uitloggen
            </button>
        </form>
    </div>
</x-guest-layout>
