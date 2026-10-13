<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-lg bg-turquoise px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-turquoise/90 focus:outline-none focus:ring-0 focus:ring-turquoise focus:ring-offset-0 disabled:opacity-50']) }}>
    {{ $slot }}
</button>