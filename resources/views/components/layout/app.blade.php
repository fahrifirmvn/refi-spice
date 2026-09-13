<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refi Spice — {{ $brand['tagline'] ?? 'Single-Origin Botanical Exporter, Indonesia' }}</title>
    <meta name="description" content="Refi Spice — eksportir rempah single-origin Nusantara (pala & cengkeh) dengan standar mutu dan keterlacakan kelas dunia.">

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
