@props(['brand' => []])

@php
    $navLinks = [
        ['href' => '#cerita-kami', 'label' => 'Cerita Kami'],
        ['href' => '#koleksi-produk', 'label' => 'Koleksi Produk'],
        ['href' => '#kualitas', 'label' => 'Kualitas'],
        ['href' => '#rantai-pasok', 'label' => 'Rantai Pasok'],
        ['href' => '#kemitraan', 'label' => 'Kemitraan'],
        ['href' => '#kontak', 'label' => 'Kontak'],
    ];
@endphp

<header
    x-data="{ mobileOpen: false, scrolled: false }"
    x-init="
        scrolled = window.scrollY > 24;
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 24 }, { passive: true });
    "
    :class="scrolled
        ? 'border-b border-[var(--color-border-hairline)] bg-[var(--color-bg-cream)]/95 backdrop-blur'
        : 'border-b border-transparent bg-transparent'"
    class="sticky top-0 z-50 transition-colors duration-300"
>
    {{-- PRD Section 8.4 — GSAP-driven scroll progress indicator --}}
    <div class="absolute inset-x-0 top-0 h-[2px] bg-[var(--color-border-hairline)]/40">
        <div data-scroll-progress class="h-full w-0 bg-[var(--color-primary)]"></div>
    </div>

    <div class="mx-auto flex max-w-(--container-heritage) items-center justify-between gap-4 px-6 py-4">
        <a href="/" class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--color-primary)] text-[var(--color-primary)]" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                    <path d="M12 3c-4 3-6 7-6 11a6 6 0 0 0 12 0c0-4-2-8-6-11Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 7v11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-heading text-lg font-semibold tracking-[0.08em] text-[var(--color-text-ink)]">REFI SPICE</span>
                <span class="mt-1 hidden text-[10px] font-medium uppercase tracking-[0.18em] text-[var(--color-text-muted)] sm:block">
                    {{ $brand['tagline'] ?? 'Single-Origin Botanical Exporter, Indonesia' }}
                </span>
            </span>
        </a>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Navigasi utama">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" class="relative py-1 text-xs font-semibold uppercase tracking-[0.1em] text-[var(--color-text-muted)] transition-colors duration-200 after:absolute after:-bottom-0.5 after:left-0 after:h-px after:w-full after:origin-left after:scale-x-0 after:bg-[var(--color-primary)] after:transition-transform after:duration-200 hover:text-[var(--color-primary)] hover:after:scale-x-100">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a
                href="#"
                aria-disabled="true"
                title="Unduh katalog akan tersedia segera"
                class="rounded-full border border-[var(--color-border-hairline)] px-4 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-[var(--color-text-muted)] transition-colors duration-200 hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]"
            >
                Unduh Katalog
            </a>
            <a
                href="#kontak"
                class="rounded-full bg-[var(--color-primary)] px-5 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-[var(--color-text-cream)] transition-all duration-200 hover:scale-[1.02] hover:opacity-90"
            >
                Inquiry Ekspor &rarr;
            </a>
        </div>


        <button
            type="button"
            @click="mobileOpen = !mobileOpen"
            class="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--color-border-hairline)] text-[var(--color-text-ink)] lg:hidden"
            :aria-expanded="mobileOpen.toString()"
            aria-label="Buka menu navigasi"
        >
            <svg x-show="!mobileOpen" viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <svg x-show="mobileOpen" x-cloak viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    {{-- Mobile nav: slide-in panel from the right (PRD Section 9.1) --}}
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed inset-0 z-50 flex justify-end lg:hidden"
        role="dialog"
        aria-modal="true"
    >
        <div class="absolute inset-0 bg-[var(--color-text-ink)]/40" @click="mobileOpen = false"></div>

        <div class="relative flex h-full w-4/5 max-w-xs flex-col gap-6 bg-[var(--color-bg-cream)] px-6 py-6 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="font-heading text-base font-semibold tracking-[0.08em]">REFI SPICE</span>
                <button type="button" @click="mobileOpen = false" aria-label="Tutup menu navigasi" class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--color-border-hairline)]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                        <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-4" aria-label="Navigasi mobile">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" @click="mobileOpen = false" class="text-sm font-semibold uppercase tracking-[0.1em] text-[var(--color-text-ink)]">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto flex flex-col gap-3">
                <a href="#" aria-disabled="true" class="rounded-full border border-[var(--color-border-hairline)] px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.08em] text-[var(--color-text-muted)]">
                    Unduh Katalog
                </a>
                <a href="#kontak" @click="mobileOpen = false" class="rounded-full bg-[var(--color-primary)] px-4 py-3 text-center text-xs font-semibold uppercase tracking-[0.08em] text-[var(--color-text-cream)]">
                    Inquiry Ekspor &rarr;
                </a>
            </div>
        </div>
    </div>
</header>
