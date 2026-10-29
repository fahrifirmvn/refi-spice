@props(['brand' => [], 'class' => 'h-12 w-12', 'loading' => 'eager'])

<img
    src="{{ $brand['logo_url'] ?? null ?: asset('images/brand/refi-logo-192.webp') }}"
    alt="Logo Rempah Favorit Indonesia"
    width="96"
    height="96"
    loading="{{ $loading }}"
    decoding="async"
    {{ $attributes->merge(['class' => $class.' shrink-0 rounded-full object-cover']) }}
>
