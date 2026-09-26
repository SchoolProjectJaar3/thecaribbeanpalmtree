<header class="border-b border-deep-blue/10 bg-sand-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="flex min-h-20 items-center justify-between gap-6">

            <!-- Logo -->
            <a
                href="/"
                class="flex shrink-0 items-center rounded-lg focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2"
                aria-label="The Caribbean Palm Tree - Home">
                <img
                    src="{{ asset('images/the-caribbean-palm-tree-compact-logo.png') }}"
                    alt="The Caribbean Palm Tree"
                    class="h-14 w-14 object-contain">
            </a>


            <!-- Hoofdnavigatie -->
            <nav
                class="hidden items-center gap-5 lg:flex"
                aria-label="Hoofdnavigatie">

                <a
                    href="/"
                    class="rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Home
                </a>

                <a
                    href="/het-huis"
                    class="rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Het huis
                </a>

                <a
                    href="/fotos"
                    class="rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Foto's
                </a>

                <a
                    href="/beschikbaarheid"
                    class="rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Beschikbaarheid
                </a>

                <a
                    href="/tarieven"
                    class="rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                    Tarieven
                </a>


                <!-- Meer -->
                <details class="relative">
                    <summary
                        class="flex cursor-pointer list-none items-center gap-1 rounded-md px-2 py-2 text-sm font-medium text-deep-blue transition hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        Meer
                        <span aria-hidden="true">⌄</span>
                    </summary>

                    <div
                        class="absolute right-0 z-50 mt-2 w-56 rounded-xl border border-deep-blue/10 bg-white p-2 shadow-lg">
                        <a
                            href="/omgeving"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            Omgeving
                        </a>

                        <a
                            href="/reviews"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            Reviews
                        </a>

                        <a
                            href="/faq"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            FAQ
                        </a>

                        <a
                            href="/over-de-eigenaar"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            Over de eigenaar
                        </a>

                        <a
                            href="/praktische-informatie"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            Praktische informatie
                        </a>

                        <a
                            href="/contact"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue hover:bg-sand-white hover:text-turquoise">
                            Contact
                        </a>
                    </div>
                </details>

            </nav>


            <!-- Rechterkant desktop -->
            <div class="hidden items-center gap-4 lg:flex">

                <!-- Taal -->
                <div
                    class="flex items-center gap-1 text-sm font-medium"
                    aria-label="Taalkeuze">
                    <a
                        href="#"
                        class="rounded px-1 py-1 text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        NL
                    </a>

                    <span class="text-deep-blue/40" aria-hidden="true">|</span>

                    <a
                        href="#"
                        class="rounded px-1 py-1 text-deep-blue hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        EN
                    </a>
                </div>


                <!-- CTA -->
                <a
                    href="/beschikbaarheid"
                    class="inline-flex min-h-11 items-center justify-center rounded-lg bg-coral px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-coral/90 focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2">
                    Bekijk beschikbaarheid
                </a>

            </div>


            <!-- Mobiele navigatie -->
            <div class="lg:hidden">

                <details class="relative">
                    <summary
                        class="flex min-h-11 min-w-11 cursor-pointer list-none items-center justify-center rounded-lg text-deep-blue hover:bg-deep-blue/10 focus:outline-none focus:ring-2 focus:ring-turquoise"
                        aria-label="Menu openen">
                        <span class="text-2xl" aria-hidden="true">☰</span>
                    </summary>

                    <div
                        class="absolute right-0 z-50 mt-3 w-72 max-w-[calc(100vw-2rem)] rounded-xl border border-deep-blue/10 bg-white p-4 shadow-xl">

                        <nav
                            class="flex flex-col gap-1"
                            aria-label="Mobiele navigatie">

                            <a href="/" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Home
                            </a>

                            <a href="/het-huis" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Het huis
                            </a>

                            <a href="/fotos" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Foto's
                            </a>

                            <a href="/beschikbaarheid" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Beschikbaarheid
                            </a>

                            <a href="/tarieven" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Tarieven
                            </a>

                            <a href="/omgeving" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Omgeving
                            </a>

                            <a href="/reviews" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Reviews
                            </a>

                            <a href="/faq" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                FAQ
                            </a>

                            <a href="/over-de-eigenaar" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Over de eigenaar
                            </a>

                            <a href="/praktische-informatie" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Praktische informatie
                            </a>

                            <a href="/contact" class="rounded-lg px-3 py-3 font-medium text-deep-blue hover:bg-sand-white">
                                Contact
                            </a>

                        </nav>


                        <div class="my-3 border-t border-deep-blue/10"></div>


                        <!-- Taal -->
                        <div class="flex items-center gap-2 px-3 py-2 text-sm font-medium">
                            <a href="#" class="text-deep-blue hover:text-turquoise">
                                NL
                            </a>

                            <span class="text-deep-blue/40">|</span>

                            <a href="#" class="text-deep-blue hover:text-turquoise">
                                EN
                            </a>
                        </div>


                        <!-- CTA mobiel -->
                        <a
                            href="/beschikbaarheid"
                            class="mt-3 flex min-h-12 items-center justify-center rounded-lg bg-coral px-5 py-3 text-sm font-semibold text-white hover:bg-coral/90 focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2">
                            Bekijk beschikbaarheid
                        </a>

                    </div>
                </details>

            </div>

        </div>
    </div>
</header>