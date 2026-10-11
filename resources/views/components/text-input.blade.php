@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-deep-blue/20 bg-white text-deep-blue shadow-sm placeholder:text-deep-blue/40 focus:border-turquoise focus:ring-turquoise disabled:bg-sand-white disabled:opacity-70']) }}>
