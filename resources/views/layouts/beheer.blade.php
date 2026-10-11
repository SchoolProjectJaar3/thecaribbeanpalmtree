<!DOCTYPE html>
<html lang="nl" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title }} | Beheer | The Caribbean Palm Tree</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-sand-white font-sans text-deep-blue antialiased">

    <div x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false" class="min-h-full">

        <!-- Sidebar mobiel -->
        <div x-show="menuOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true" aria-label="Menu beheeromgeving">
            <div
                x-show="menuOpen"
                x-transition:enter="transition-opacity duration-200 ease-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-150 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="menuOpen = false"
                class="fixed inset-0 bg-deep-blue/60"
                aria-hidden="true"></div>

            <div
                x-show="menuOpen"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 flex w-72 max-w-[calc(100vw-3rem)]">

                <x-beheer.sidebar />

                <button
                    type="button"
                    @click="menuOpen = false"
                    class="absolute -right-12 top-4 flex h-10 w-10 items-center justify-center rounded-lg text-white transition-colors hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
                    <span class="sr-only">Menu sluiten</span>
                    <x-site.icon name="close" />
                </button>
            </div>
        </div>

        <!-- Sidebar desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-64">
            <x-beheer.sidebar />
        </div>

        <div class="flex min-h-full flex-col lg:pl-64">

            <x-beheer.header />

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-6xl">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-deep-blue sm:text-3xl">{{ $title }}</h1>
                            @if ($description)
                            <p class="mt-1 max-w-2xl text-deep-blue/70">{{ $description }}</p>
                            @endif
                        </div>

                        @isset($actions)
                        <div class="flex shrink-0 gap-3">{{ $actions }}</div>
                        @endisset
                    </div>

                    <div class="mt-8">
                        {{ $slot }}
                    </div>

                </div>
            </main>

        </div>

    </div>

</body>

</html>
