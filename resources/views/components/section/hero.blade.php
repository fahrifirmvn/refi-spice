@props(['hero' => [], 'companyStats' => null])

<section id="hero" class="relative overflow-hidden bg-[var(--color-bg-cream)] px-6 py-20 sm:py-28">
    <div class="mx-auto flex max-w-(--container-heritage) flex-col items-center text-center">
        <div class="flex items-center gap-4 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--color-primary)]">
            <span class="h-px w-10 bg-[var(--color-primary)]/40"></span>
            <span class="font-heading italic tracking-normal text-[var(--color-primary)]">{{ $hero['eyebrow'] ?? '' }}</span>
            <span class="h-px w-10 bg-[var(--color-primary)]/40"></span>
        </div>

        <h1 class="mt-8 max-w-4xl font-heading text-4xl leading-tight text-[var(--color-text-ink)] sm:text-5xl md:text-6xl lg:text-7xl">
            {{ $hero['headline'] ?? '' }}
        </h1>

        <div class="mt-6 flex items-center gap-4 text-[11px] font-semibold uppercase tracking-[0.14em] text-[var(--color-text-muted)]">
            <span class="h-px w-16 bg-[var(--color-border-hairline)]"></span>
            <span>{{ $hero['subheadline'] ?? '' }}</span>
            <span class="h-px w-16 bg-[var(--color-border-hairline)]"></span>
        </div>

        <p class="mt-8 max-w-2xl text-base leading-relaxed text-[var(--color-text-muted)] sm:text-lg">
            {{ $hero['body'] ?? '' }}
        </p>

        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
            <a href="#koleksi-produk" class="rounded-md bg-[var(--color-primary)] px-7 py-3.5 text-xs font-semibold uppercase tracking-[0.1em] text-[var(--color-text-cream)] transition-opacity hover:opacity-90">
                {{ $hero['cta_primary_label'] ?? 'Jelajahi Koleksi' }} &rarr;
            </a>
            <a href="#kualitas" class="rounded-md border border-[var(--color-border-hairline)] px-7 py-3.5 text-xs font-semibold uppercase tracking-[0.1em] text-[var(--color-text-ink)] transition-colors hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                {{ $hero['cta_secondary_label'] ?? 'Pelajari Standar Ekspor' }} &rarr;
            </a>
        </div>

        <div class="mt-16 grid w-full max-w-4xl grid-cols-1 gap-10 border-t border-[var(--color-border-hairline)] pt-10 text-left sm:grid-cols-3">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[var(--color-primary)]">{{ $hero['value_prop_1_label'] ?? '' }}</p>
                <h3 class="mt-3 font-heading text-xl text-[var(--color-text-ink)]">{{ $hero['value_prop_1_title'] ?? '' }}</h3>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $hero['value_prop_1_description'] ?? '' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[var(--color-primary)]">{{ $hero['value_prop_2_label'] ?? '' }}</p>
                <h3 class="mt-3 font-heading text-xl text-[var(--color-text-ink)]">{{ $hero['value_prop_2_title'] ?? '' }}</h3>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $hero['value_prop_2_description'] ?? '' }}</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[var(--color-primary)]">{{ $hero['value_prop_3_label'] ?? '' }}</p>
                <h3 class="mt-3 font-heading text-xl text-[var(--color-text-ink)]">{{ $hero['value_prop_3_title'] ?? '' }}</h3>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $hero['value_prop_3_description'] ?? '' }}</p>
            </div>
        </div>

        <div class="mt-14 flex w-full max-w-4xl flex-col items-center gap-4 border-t border-[var(--color-border-hairline)] pt-6 text-[11px] font-medium uppercase tracking-[0.08em] text-[var(--color-text-muted)] sm:flex-row sm:justify-between">
            <span>{{ $hero['footer_coordinates'] ?? '' }}</span>
            <span class="rounded border border-[var(--color-border-hairline)] px-3 py-1.5">{{ $hero['footer_badge'] ?? '' }}</span>
        </div>
    </div>
</section>
