@props(['brand' => [], 'seo' => [], 'contact' => []])

@php
    $metaTitle = $seo['meta_title'] ?? ('Refi Spice — ' . ($brand['tagline'] ?? 'Single-Origin Botanical Exporter, Indonesia'));
    $metaDescription = $seo['meta_description'] ?? 'Refi Spice — eksportir rempah single-origin Nusantara (pala & cengkeh) dengan standar mutu dan keterlacakan kelas dunia.';
    $logoUrl = $brand['logo_url'] ?? null ?: asset('images/brand/refi-logo-512.webp');
    $ogImageUrl = $seo['og_image_url'] ?? null ?: asset('images/brand/og-default.jpg');

    // PRD Section 10.1 — Organization structured data (JSON-LD), built from
    // the same page_contents rows editable via the admin panel.
    $organizationJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'PT Refi Rempah Nusantara',
        'alternateName' => 'Refi Spice',
        'url' => url('/'),
        'email' => $contact['email'] ?? null,
        'telephone' => $contact['phone'] ?? null,
        'logo' => $logoUrl,
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $contact['head_office_address'] ?? null,
            'addressCountry' => 'ID',
        ]),
    ], fn ($value) => $value !== null && $value !== []);
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#2f5224">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/brand/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">

    {{-- Open Graph / social share preview (PRD 10.1) — link likely shared via email/WhatsApp to buyers --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Refi Spice">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta name="twitter:card" content="summary_large_image">

    {{-- JSON-LD remains data, even when its fields are managed in the admin panel. --}}
    <script type="application/ld+json">{!! json_encode($organizationJsonLd, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ Vite::fonts() }}
</head>
<body class="bg-[var(--color-bg-cream)] font-sans text-[var(--color-text-ink)] antialiased">
    <x-layout.navbar :brand="$brand ?? []" />

    <main>
        {{ $slot }}
    </main>
</body>
</html>
