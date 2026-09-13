@props(['content' => [], 'processSteps' => null])

<section id="rantai-pasok" class="bg-[var(--color-bg-cream)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-[var(--color-primary)]">
                    <span class="h-px w-8 bg-[var(--color-primary)]/40"></span>
                    <span>{{ $content['eyebrow_label'] ?? '' }}</span>
                </div>

                <h2 class="mt-5 font-heading text-3xl leading-tight text-[var(--color-text-ink)] sm:text-4xl">
                    {{ $content['headline_plain'] ?? '' }}
                    <span class="italic text-[var(--color-primary)]">{{ $content['headline_emphasis'] ?? '' }}</span>
                </h2>

                <p class="mt-4 text-[15px] leading-relaxed text-[var(--color-text-muted)]">
                    {{ $content['body'] ?? '' }}
                </p>
            </div>

            <div class="flex divide-x divide-[var(--color-border-hairline)] rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)]">
                <div class="px-6 py-5 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['stat_1_label'] ?? '' }}</p>
                    <p class="mt-2 font-heading text-2xl text-[var(--color-primary)]">{{ $content['stat_1_value'] ?? '' }}</p>
                </div>
                <div class="px-6 py-5 text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['stat_2_label'] ?? '' }}</p>
                    <p class="mt-2 font-heading text-2xl text-[var(--color-primary)]">{{ $content['stat_2_value'] ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-14 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($processSteps as $step)
                <div class="rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-5">
                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">
                        <span>{{ sprintf('Tahap %s', ['I', 'II', 'III', 'IV'][$loop->index] ?? $loop->iteration) }}</span>
                        <span class="rounded border border-[var(--color-border-hairline)] px-2 py-0.5">{{ sprintf('Lot No. %02d', $step->step_number) }}</span>
                    </div>

                    <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.04em] text-[var(--color-primary)]">{{ $step->category_label }}</p>

                    <h3 class="mt-2 font-heading text-xl text-[var(--color-text-ink)]">{{ $step->step_number }}. {{ $step->title }}</h3>

                    <div class="relative mt-4">
                        <x-ui.placeholder-image
                            :label="$step->title"
                            class="aspect-[4/3] photo-heritage-grade rounded-md"
                        />
                        <span class="absolute bottom-2 left-2 rounded bg-[var(--color-bg-cream)]/90 px-2 py-1 text-[10px] font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">
                            {{ $step->image_caption }}
                        </span>
                    </div>

                    <p class="mt-4 text-sm leading-relaxed text-[var(--color-text-muted)]">{{ $step->description }}</p>

                    <div class="mt-4 flex items-center justify-between border-t border-[var(--color-border-hairline)] pt-3 text-xs">
                        <span class="uppercase tracking-[0.04em] text-[var(--color-text-muted)]">{{ $step->data_label }}</span>
                        <span class="font-semibold text-[var(--color-primary)]">{{ $step->data_value }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col gap-6 rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-[var(--color-primary)] text-[var(--color-primary)]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                        <rect x="4" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.5" />
                        <rect x="14" y="4" width="6" height="6" stroke="currentColor" stroke-width="1.5" />
                        <rect x="4" y="14" width="6" height="6" stroke="currentColor" stroke-width="1.5" />
                        <path d="M14 14h6v6h-6z" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-ink)]">
                        {{ $content['banner_title'] ?? '' }} &bull; {{ $content['banner_subtitle'] ?? '' }}
                    </p>
                    <p class="mt-1 max-w-lg text-sm leading-relaxed text-[var(--color-text-muted)]">{{ $content['banner_body'] ?? '' }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-4 border-t border-[var(--color-border-hairline)] pt-4 text-xs sm:flex-row sm:gap-8 lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
                <div>
                    <p class="font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['banner_cert_label'] ?? '' }}</p>
                    <p class="mt-1 text-[var(--color-text-ink)]">{{ $content['banner_cert_value'] ?? '' }}</p>
                </div>
                <div>
                    <p class="font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['banner_doc_label'] ?? '' }}</p>
                    <p class="mt-1 text-[var(--color-text-ink)]">{{ $content['banner_doc_value'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
