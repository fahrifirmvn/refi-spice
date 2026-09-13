@props(['content' => [], 'milestones' => null])

<section id="cerita-kami" class="bg-[var(--color-bg-beige)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            {{-- Archive photo frame --}}
            <div class="lg:col-span-4">
                <div class="rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-cream)] p-3">
                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-[0.08em] text-[var(--color-text-muted)]">
                        <span>{{ $content['archive_plate_no'] ?? '' }}</span>
                        <span class="text-[var(--color-primary)]">{{ $content['archive_coord'] ?? '' }}</span>
                    </div>

                    <div class="relative mt-3">
                        <x-ui.placeholder-image
                            label="Dokumentasi kebun kemitraan Banda dan Minahasa"
                            class="aspect-[4/5] photo-heritage-grade rounded-md"
                        />

                        <div class="absolute bottom-4 left-4 rounded-md border border-[var(--color-border-hairline)] bg-[var(--color-bg-cream)]/95 px-4 py-3 text-center shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-ink)]">100% Single Origin</p>
                            <p class="mt-0.5 text-[10px] uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['photo_badge_subtitle'] ?? '' }}</p>
                        </div>
                    </div>

                    <p class="mt-3 text-xs italic leading-relaxed text-[var(--color-text-ink)]">{{ $content['photo_caption_title'] ?? '' }}</p>
                    <p class="mt-1 text-[10px] font-medium uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['photo_caption_meta'] ?? '' }}</p>
                </div>
            </div>

            {{-- Narrative --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-[var(--color-primary)]">
                    <span>{{ $content['eyebrow_label'] ?? '' }}</span>
                    <span class="h-px flex-1 bg-[var(--color-border-hairline)]"></span>
                    <span class="text-[var(--color-text-muted)]">{{ $content['eyebrow_sub'] ?? '' }}</span>
                </div>

                <h2 class="mt-5 font-heading text-3xl leading-tight text-[var(--color-text-ink)] sm:text-4xl">
                    {{ $content['headline'] ?? '' }}
                </h2>

                <p class="mt-6 text-[15px] leading-relaxed text-[var(--color-text-muted)]">
                    <span class="float-left mr-1 font-heading text-5xl leading-[0.8] text-[var(--color-primary)]">{{ mb_substr($content['body_paragraph_1'] ?? '', 0, 1) }}</span>{{ mb_substr($content['body_paragraph_1'] ?? '', 1) }}
                </p>

                <p class="mt-5 text-[15px] leading-relaxed text-[var(--color-text-muted)]">
                    {{ $content['body_paragraph_2'] ?? '' }}
                </p>

                <div class="mt-8 flex flex-col gap-4 border-t border-[var(--color-border-hairline)] pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--color-primary)] font-heading text-sm text-[var(--color-primary)]">R</span>
                        <div>
                            <p class="font-heading text-sm text-[var(--color-text-ink)]">PT Refi Rempah Nusantara</p>
                            <p class="text-[11px] uppercase tracking-[0.04em] text-[var(--color-text-muted)]">Dedikasi untuk Bumi Nusantara — Single-Origin Exporter</p>
                        </div>
                    </div>

                    <div class="text-right text-[11px] uppercase tracking-[0.06em] text-[var(--color-text-muted)]">
                        <p>Spesifikasi Komoditas</p>
                        <p class="font-semibold text-[var(--color-primary)]">Grade A &bull; Handpicked Sorted</p>
                    </div>
                </div>
            </div>

            {{-- Milestones timeline --}}
            <div class="lg:col-span-3">
                <ol class="space-y-8 border-l border-[var(--color-border-hairline)] pl-6">
                    @foreach ($milestones as $milestone)
                        <li class="relative">
                            <span class="absolute -left-[27px] top-1.5 h-2 w-2 rounded-full bg-[var(--color-primary)]"></span>
                            <p class="font-heading text-lg text-[var(--color-primary)]">{{ $milestone->year }}</p>
                            <p class="mt-1 text-sm font-semibold leading-snug text-[var(--color-text-ink)]">{{ $milestone->title }}</p>
                            <p class="mt-1 text-xs leading-relaxed text-[var(--color-text-muted)]">{{ $milestone->description }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>
