<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-lg bg-coral px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-coral/90 focus:outline-none focus:ring-0 focus:ring-coral focus:ring-offset-0 disabled:opacity-50']) }}>
    {{ $slot }}
</button>