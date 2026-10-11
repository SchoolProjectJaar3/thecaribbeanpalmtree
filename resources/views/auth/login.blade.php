<x-guest-layout>
    <x-slot:heading>Inloggen</x-slot:heading>
    <x-slot:intro>Log in om reserveringen, tarieven en de woning te beheren.</x-slot:intro>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- E-mailadres -->
        <div>
            <x-input-label for="email" value="E-mailadres" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Wachtwoord -->
        <div>
            <div class="flex items-center justify-between gap-4">
                <x-input-label for="password" value="Wachtwoord" />

                @if (Route::has('password.request'))
                <a class="text-sm rounded-md font-semibold text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2" href="{{ route('password.request') }}">
                    Wachtwoord vergeten?
                </a>
                @endif
            </div>

            <x-text-input id="password" class="mt-1 block w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Onthoud mij -->
        <label for="remember_me" class="inline-flex items-center gap-2">
            <input id="remember_me" type="checkbox" class="rounded border-deep-blue/30 text-turquoise shadow-sm focus:ring-turquoise" name="remember">
            <span class="text-sm text-deep-blue/80">Ingelogd blijven</span>
        </label>

        <x-primary-button class="w-full">
            Inloggen
        </x-primary-button>
    </form>

    @if (Route::has('register'))
    <x-slot:footer>
        Nog geen account?
        <a class="rounded-md font-semibold text-turquoise transition-colors hover:text-deep-blue focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2" href="{{ route('register') }}">Registreren</a>
    </x-slot:footer>
    @endif
</x-guest-layout>
