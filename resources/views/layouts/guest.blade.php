<!-- guest, layout voor de inlog- en accountpagina's (Breeze) -->
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <title>{{ isset($heading) ? trim(strip_tags($heading)) . ' | ' : '' }}The Caribbean Palm Tree</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-sand-white font-sans text-deep-blue antialiased">
    <div class="flex min-h-screen">

        <!-- Beeld (alleen op grote schermen) -->
        <aside class="relative isolate hidden w-1/2 overflow-hidden bg-deep-blue lg:flex lg:flex-col lg:justify-between" aria-hidden="true">
            <img
                src="{{ asset('images/website_static/zwembad_terras.avif') }}"
                alt=""
                class="absolute inset-0 -z-10 h-full w-full object-cover">
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-deep-blue via-deep-blue/70 to-deep-blue/30"></div>

            <div class="p-10">
                <img
                    src="{{ asset('images/the-caribbean-palm-tree-compact-logo.png') }}"
                    alt=""
                    class="h-16 w-16 rounded-xl bg-sand-white/90 object-contain p-1">
            </div>

            <div class="p-10 xl:p-14">
                <p class="text-sm font-semibold uppercase tracking-widest text-sand">The Caribbean Palm Tree</p>
                <p class="mt-3 max-w-md text-3xl font-bold leading-tight text-white xl:text-4xl">
                    Jouw vakantiehuis op Curaçao, overzichtelijk beheerd.
                </p>
            </div>
        </aside>

        <!-- Formulier -->
        <main class="flex w-full flex-col px-4 py-8 sm:px-6 lg:w-1/2 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <a
                    href="/"
                    class="flex items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2 lg:invisible"
                    aria-label="The Caribbean Palm Tree - Home">
                    <img
                        src="{{ asset('images/the-caribbean-palm-tree-compact-logo.png') }}"
                        alt="The Caribbean Palm Tree"
                        class="h-12 w-12 object-contain">
                </a>

                <a
                    href="/"
                    class="inline-flex items-center gap-1 rounded-md text-sm font-medium text-deep-blue/70 transition-colors hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2">
                    <span aria-hidden="true">&larr;</span> Terug naar de website
                </a>
            </div>

            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-md">
                    @isset($heading)
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-deep-blue">{{ $heading }}</h1>

                        @isset($intro)
                        <p class="mt-3 leading-relaxed text-deep-blue/70">{{ $intro }}</p>
                        @endisset
                    </div>
                    @endisset

                    <div class="rounded-xl border border-deep-blue/10 bg-white p-6 shadow-sm sm:p-8">
                        {{ $slot }}
                    </div>

                    @isset($footer)
                    <div class="mt-6 text-center text-sm text-deep-blue/70">
                        {{ $footer }}
                    </div>
                    @endisset
                </div>
            </div>

            <p class="text-center text-xs text-deep-blue/50">
                &copy; {{ date('Y') }} The Caribbean Palm Tree
            </p>
        </main>
    </div>
</body>

</html>
