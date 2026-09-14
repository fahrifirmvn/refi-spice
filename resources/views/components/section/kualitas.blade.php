@props(['content' => [], 'companyStats' => null, 'certifications' => null])

<section id="kualitas" class="bg-[var(--color-bg-dark)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
            <div data-aos="fade-up">
                <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-[var(--color-accent-gold)]">
                    <span class="h-px w-8 bg-[var(--color-accent-gold)]/50"></span>
                    <span>{{ $content['eyebrow_label'] ?? '' }}</span>
                </div>

                <h2 class="mt-5 font-heading text-3xl leading-tight text-[var(--color-text-cream)] sm:text-4xl">
                    {{ $content['headline'] ?? '' }}
                </h2>

                <p class="mt-5 max-w-md text-[15px] leading-relaxed text-[var(--color-text-cream-muted)]">
                    {{ $content['body'] ?? '' }}
                </p>

                <div class="mt-8 flex max-w-md gap-4 rounded-lg border border-[var(--color-accent-gold)]/30 bg-white/[0.03] p-5">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[var(--color-accent-gold)] text-[var(--color-accent-gold)]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                            <path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                            <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.04em] text-[var(--color-accent-gold)]">{{ $content['callout_title'] ?? '' }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-[var(--color-text-cream-muted)]">{{ $content['callout_body'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                @foreach ($companyStats as $stat)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" class="rounded-lg border border-white/10 bg-white/[0.03] p-6">
                        <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-cream-muted)]">
                            <span>{{ $stat->code_label }}</span>
                            <span>{{ sprintf('Sec // %02d', $loop->iteration) }}</span>
                        </div>

                        <p class="mt-4 font-heading text-4xl text-[var(--color-accent-gold)]">
                            <span data-count-up="{{ $stat->value }}">0</span>{{ $stat->suffix }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-[var(--color-text-cream)]">{{ $stat->label }}</p>

                        <p class="mt-3 border-t border-white/10 pt-3 text-xs leading-relaxed text-[var(--color-text-cream-muted)]">{{ $stat->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-16 flex flex-col gap-6 border-t border-white/10 pt-10 lg:flex-row lg:items-center lg:justify-between">
            <div data-aos="fade-up">
                <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-cream-muted)]">{{ $content['accreditation_label'] ?? '' }}</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    @foreach ($certifications as $certification)
                        <span class="rounded-md border border-[var(--color-accent-gold)]/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-accent-gold)]">
                            {{ $certification->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            <p class="text-xs text-[var(--color-text-cream-muted)]">{{ $content['document_ref'] ?? '' }}</p>
        </div>
    </div>
</section>
