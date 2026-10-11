<aside class="flex w-full grow flex-col overflow-y-auto bg-deep-blue px-4 pb-4 text-white">

    <!-- Logo -->
    <a
        href="{{ route('beheer.dashboard') }}"
        class="-mx-1 mt-5 flex items-center gap-3 rounded-lg px-1 py-1 focus:outline-none focus:ring-2 focus:ring-turquoise">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sand-white">
            <img
                src="{{ asset('images/the-caribbean-palm-tree-compact-logo.png') }}"
                alt=""
                class="h-9 w-9 object-contain">
        </span>
        <span class="leading-tight">
            <span class="block text-sm font-semibold">The Caribbean Palm Tree</span>
            <span class="block text-xs font-medium uppercase tracking-widest text-sand">Beheer</span>
        </span>
    </a>

    <!-- Navigatie -->
    <nav class="mt-8 flex flex-1 flex-col gap-7" aria-label="Beheernavigatie">
        @foreach (config('beheer.navigatie') as $groep)
        <div>
            <p class="px-3 text-xs font-semibold uppercase tracking-widest text-white/50">{{ $groep['titel'] }}</p>

            <ul class="mt-2 space-y-1">
                @foreach ($groep['items'] as $item)
                @php($actief = request()->routeIs($item['route'], $item['route'] . '.*'))
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        @if ($actief) aria-current="page" @endif
                        class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-turquoise {{ $actief ? 'bg-white/10 text-white' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <x-site.icon :name="$item['icoon']" class="h-5 w-5 shrink-0 {{ $actief ? 'text-sand' : 'text-white/50 group-hover:text-white' }}" />
                        {{ $item['label'] }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </nav>

    <!-- Naar de website -->
    <a
        href="{{ url('/') }}"
        target="_blank"
        rel="noopener"
        class="mt-6 flex items-center gap-3 rounded-lg border border-white/15 px-3 py-2.5 text-sm font-medium text-white/75 transition-colors hover:bg-white/5 hover:text-white focus:outline-none focus:ring-2 focus:ring-turquoise">
        <x-site.icon name="external" class="h-5 w-5 shrink-0 text-white/50" />
        Bekijk de website
    </a>

</aside>
