<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-lg border border-deep-blue/20 bg-white px-5 py-2.5 text-sm font-semibold text-deep-blue shadow-sm transition hover:bg-sand-white focus:outline-none focus:ring-2 focus:ring-turquoise focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
