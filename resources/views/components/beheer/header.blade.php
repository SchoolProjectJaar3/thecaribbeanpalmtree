@php
$gebruiker = auth()->user();
$initialen = mb_strtoupper(mb_substr($gebruiker->first_name, 0, 1) . mb_substr($gebruiker->last_name, 0, 1));
@endphp

<header class="sticky top-0 z-30 border-b border-deep-blue/10 bg-white/90 backdrop-blur">
    <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">

        <!-- Menu openen (mobiel) -->
        <button
            type="button"
            @click="menuOpen = true"
            :aria-expanded="menuOpen.toString()"
            class="-ml-2 flex h-11 w-11 items-center justify-center rounded-lg text-deep-blue transition-colors hover:bg-deep-blue/5 focus:outline-none focus:ring-0 focus:ring-turquoise lg:hidden">
            <span class="sr-only">Menu openen</span>
            <x-site.icon name="menu" />
        </button>

        <p class="text-sm font-medium text-deep-blue/60">
            <span class="lg:hidden">Beheeromgeving</span>
            <span class="hidden first-letter:uppercase lg:inline">{{ now()->locale('nl')->translatedFormat('l j F Y') }}</span>
        </p>

        <!-- Gebruikersmenu -->
        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="relative ml-auto">
            <button
                type="button"
                @click="open = ! open"
                :aria-expanded="open.toString()"
                aria-haspopup="true"
                class="flex items-center gap-3 rounded-lg p-1.5 text-left transition-colors hover:bg-deep-blue/5 focus:outline-none focus:ring-0 focus:ring-turquoise">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-turquoise text-sm font-semibold text-white" aria-hidden="true">{{ $initialen }}</span>
                <span class="hidden leading-tight sm:block">
                    <span class="block text-sm font-semibold text-deep-blue">{{ $gebruiker->name }}</span>
                    @if ($gebruiker->role)
                    <span class="block text-xs text-deep-blue/60">{{ $gebruiker->role->name }}</span>
                    @endif
                </span>
                <span
                    class="hidden transition-transform duration-200 ease-out sm:block"
                    :class="open ? 'rotate-180' : ''"
                    aria-hidden="true">
                    <x-site.icon name="chevron-down" class="h-4 w-4 text-deep-blue/60" />
                </span>
            </button>

            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition duration-150 ease-out"
                x-transition:enter-start="-translate-y-1 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-transition:leave="transition duration-100 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute right-0 z-50 mt-2 w-60 origin-top-right rounded-xl border border-deep-blue/10 bg-white p-2 shadow-lg">
                <div class="border-b border-deep-blue/10 px-3 pb-2 pt-1">
                    <p class="truncate text-sm font-semibold text-deep-blue">{{ $gebruiker->name }}</p>
                    <p class="truncate text-xs text-deep-blue/60">{{ $gebruiker->email }}</p>
                </div>

                <a
                    href="{{ route('profile.edit') }}"
                    class="mt-2 flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                    <x-site.icon name="user" class="h-5 w-5" />
                    Mijn account
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-coral">
                        <x-site.icon name="logout" class="h-5 w-5" />
                        Uitloggen
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>