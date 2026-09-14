@props(['brand' => [], 'seo' => [], 'contact' => []])

@php
    $metaTitle = $seo['meta_title'] ?? ('Refi Spice — ' . ($brand['tagline'] ?? 'Single-Origin Botanical Exporter, Indonesia'));
    $metaDescription = $seo['meta_description'] ?? 'Refi Spice — eksportir rempah single-origin Nusantara (pala & cengkeh) dengan standar mutu dan keterlacakan kelas dunia.';
    $ogImagePath = $seo['og_image_path'] ?? null;

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
        'logo' => $ogImagePath ? asset('storage/' . $ogImagePath) : null,
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

    {{-- Open Graph / social share preview (PRD 10.1) — link likely shared via email/WhatsApp to buyers --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Refi Spice">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    @if ($ogImagePath)
        <meta property="og:image" content="{{ asset('storage/' . $ogImagePath) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    <script type="application/ld+json">{!! json_encode($organizationJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

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
