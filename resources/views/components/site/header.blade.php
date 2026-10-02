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

            <!-- Hoofdnavigatie desktop -->
            <nav
                class="hidden items-center gap-4 lg:flex"
                aria-label="Hoofdnavigatie">

                <!-- Home -->
                <a
                    href="/"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('/') ? 'text-coral' : 'text-deep-blue' }}">
                    Home

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('/') ? 'scale-x-100' : '' }}"></span>
                </a>
                <!-- Het huis -->
                <a
                    href="/het-huis"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('het-huis') ? 'text-coral' : 'text-deep-blue' }}">
                    Het huis

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('het-huis') ? 'scale-x-100' : '' }}"></span>
                </a>
                <!-- Foto's -->
                <a
                    href="/fotos"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('fotos') ? 'text-coral' : 'text-deep-blue' }}">
                    Foto's

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('fotos') ? 'scale-x-100' : '' }}"></span>
                </a>
                <!-- Beschikbaarheid -->
                <a
                    href="/beschikbaarheid"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('beschikbaarheid') ? 'text-coral' : 'text-deep-blue' }}">
                    Beschikbaarheid

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('beschikbaarheid') ? 'scale-x-100' : '' }}"></span>
                </a>
                <!-- Tarieven -->
                <a
                    href="/tarieven"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('tarieven') ? 'text-coral' : 'text-deep-blue' }}">
                    Tarieven

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('tarieven') ? 'scale-x-100' : '' }}"></span>
                </a>
                <!-- Contact -->
                <a
                    href="/contact"
                    class="group relative rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('contact') ? 'text-coral' : 'text-deep-blue' }}">
                    Contact

                    <span
                        class="absolute bottom-0 left-2 right-2 h-0.5 origin-left scale-x-0 rounded-full bg-turquoise transition-transform duration-300 group-hover:scale-x-100 {{ request()->is('contact') ? 'scale-x-100' : '' }}"></span>
                </a>


                <!-- Meer -->
                <details class="group relative">

                    <summary
                        class="flex cursor-pointer list-none items-center gap-1 rounded-md px-2 py-2 text-sm font-medium transition-colors hover:text-turquoise focus:outline-none focus:ring-0 {{ request()->is('omgeving', 'reviews', 'faq', 'over-de-eigenaar', 'praktische-informatie') ? 'text-coral' : 'text-deep-blue' }}"
                        aria-label="Meer pagina's">
                        <span>Meer</span>

                        <!-- Pijltje -->
                        <svg
                            class="h-4 w-4 transition-transform duration-200 group-open:rotate-180"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </summary>
                    <!-- Meer dropdown -->
                    <div
                        class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl border border-deep-blue/10 bg-white p-2 shadow-lg
                               opacity-0 invisible translate-y-[-5px] scale-95
                               transition-all duration-200
                               group-open:visible group-open:opacity-100 group-open:translate-y-0 group-open:scale-100">

                        <a
                            href="/omgeving"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                            Omgeving
                        </a>

                        <a
                            href="/reviews"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                            Reviews
                        </a>

                        <a
                            href="/faq"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                            FAQ
                        </a>

                        <a
                            href="/over-de-eigenaar"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                            Over de eigenaar
                        </a>

                        <a
                            href="/praktische-informatie"
                            class="block rounded-lg px-3 py-2 text-sm text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                            Praktische informatie
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
                        class="rounded px-1 py-1 text-deep-blue transition-colors hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        NL
                    </a>

                    <span class="text-deep-blue/40" aria-hidden="true">|</span>

                    <a
                        href="#"
                        class="rounded px-1 py-1 text-deep-blue transition-colors hover:text-turquoise focus:outline-none focus:ring-2 focus:ring-turquoise">
                        EN
                    </a>
                </div>

                <!-- CTA desktop -->
                <a
                    href="/beschikbaarheid"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-coral px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                           transition-all duration-200
                           hover:-translate-y-px hover:bg-coral/90 hover:shadow-md
                           focus:outline-none focus:ring-0
                           active:translate-y-0">
                    Bekijk beschikbaarheid

                    <svg
                        class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true">
                        <path
                            fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 10H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>

            </div>


            <!-- Mobiele navigatie -->
            <div class="lg:hidden">

                <details class="group relative">

                    <!-- Hamburger -->
                    <summary
                        class="flex min-h-11 min-w-11 cursor-pointer list-none items-center justify-center rounded-lg text-deep-blue transition-colors hover:bg-deep-blue/10 focus:outline-none focus:ring-2 focus:ring-turquoise"
                        aria-label="Menu openen">

                        <!-- Hamburger icon -->
                        <span class="relative block h-6 w-6">

                            <span
                                class="absolute left-0 top-1 h-0.5 w-6 rounded-full bg-current transition-all duration-300 group-open:top-3 group-open:rotate-45"></span>

                            <span
                                class="absolute left-0 top-3 h-0.5 w-6 rounded-full bg-current transition-all duration-200 group-open:opacity-0"></span>

                            <span
                                class="absolute left-0 top-5 h-0.5 w-6 rounded-full bg-current transition-all duration-300 group-open:top-3 group-open:-rotate-45"></span>

                        </span>

                    </summary>


                    <!-- Mobiel menu -->
                    <div
                        class="absolute right-0 z-50 mt-3 w-72 max-w-[calc(100vw-2rem)] origin-top-right rounded-xl border border-deep-blue/10 bg-white p-4 shadow-xl
                               opacity-0 invisible translate-y-[-5px] scale-95
                               transition-all duration-200
                               group-open:visible group-open:opacity-100 group-open:translate-y-0 group-open:scale-100">

                        <nav
                            class="flex flex-col gap-1"
                            aria-label="Mobiele navigatie">

                            <a
                                href="/"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Home
                            </a>

                            <a
                                href="/het-huis"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Het huis
                            </a>

                            <a
                                href="/fotos"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Foto's
                            </a>

                            <a
                                href="/beschikbaarheid"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Beschikbaarheid
                            </a>

                            <a
                                href="/tarieven"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Tarieven
                            </a>

                            <a
                                href="/omgeving"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Omgeving
                            </a>

                            <a
                                href="/reviews"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Reviews
                            </a>

                            <a
                                href="/faq"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                FAQ
                            </a>

                            <a
                                href="/over-de-eigenaar"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Over de eigenaar
                            </a>

                            <a
                                href="/praktische-informatie"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Praktische informatie
                            </a>

                            <a
                                href="/contact"
                                class="rounded-lg px-3 py-3 font-medium text-deep-blue transition-colors hover:bg-sand-white hover:text-turquoise">
                                Contact
                            </a>

                        </nav>


                        <!-- Scheidingslijn -->
                        <div class="my-3 border-t border-deep-blue/10"></div>

                        <!-- Taal -->
                        <div class="flex items-center gap-2 px-3 py-2 text-sm font-medium">

                            <a
                                href="#"
                                class="text-deep-blue transition-colors hover:text-turquoise">
                                NL
                            </a>

                            <span class="text-deep-blue/40">|</span>

                            <a
                                href="#"
                                class="text-deep-blue transition-colors hover:text-turquoise">
                                EN
                            </a>

                        </div>

                        <!-- CTA mobiel -->
                        <a
                            href="/beschikbaarheid"
                            class="mt-3 flex min-h-12 items-center justify-center gap-2 rounded-lg bg-coral px-5 py-3 text-sm font-semibold text-white shadow-sm
                                   transition-all duration-200
                                   hover:-translate-y-0.5 hover:bg-coral/90 hover:shadow-md
                                   focus:outline-none focus:ring-2 focus:ring-coral focus:ring-offset-2
                                   active:translate-y-0">
                            Bekijk beschikbaarheid

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true">
                                <path
                                    fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 10H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </a>

                    </div>

                </details>

            </div>

        </div>

    </div>
</header>


<!-- Sluit openstaande dropdowns wanneer buiten het menu wordt geklikt -->
<script>
    document.addEventListener('click', function(event) {

        document.querySelectorAll('header details[open]').forEach(function(details) {

            if (!details.contains(event.target)) {
                details.removeAttribute('open');
            }

        });

    });
</script>