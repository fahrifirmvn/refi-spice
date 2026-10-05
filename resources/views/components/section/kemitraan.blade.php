@props(['content' => [], 'testimonials' => null, 'partners' => null])

<section id="kemitraan" class="bg-[var(--color-bg-cream)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div data-aos="fade-up" class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-[var(--color-primary)]">
            <span class="h-px w-8 bg-[var(--color-primary)]/40"></span>
            <span>{{ $content['eyebrow_label'] ?? '' }}</span>
        </div>

        <h2 data-aos="fade-up" class="mt-5 max-w-2xl font-heading text-3xl leading-tight text-[var(--color-text-ink)] sm:text-4xl">
            {{ $content['headline'] ?? '' }}
        </h2>

        <p data-aos="fade-up" class="mt-4 max-w-2xl text-[15px] leading-relaxed text-[var(--color-text-muted)]">
            {{ $content['body'] ?? '' }}
        </p>

        <div class="mt-10 rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)]/60 p-1">
            <div class="rounded-t-md border-b border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] px-5 py-2 text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">
                {{ $content['lot_ref'] ?? '' }}
            </div>

            @foreach ($testimonials as $testimonial)
                <div data-aos="fade-up" class="flex flex-col gap-6 p-8 lg:flex-row lg:items-start">
                    <span class="font-heading text-6xl leading-none text-[var(--color-primary)]/30">&ldquo;</span>

                    <div class="flex-1">
                        <blockquote class="font-heading text-xl italic leading-snug text-[var(--color-text-ink)] sm:text-2xl">
                            &ldquo;{{ $testimonial->quote }}&rdquo;
                        </blockquote>

                        <div class="mt-6 flex flex-col gap-4 border-t border-[var(--color-border-hairline)] pt-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="h-2 w-2 rounded-sm bg-[var(--color-primary)]"></span>
                                <div>
                                    <p class="text-sm font-semibold text-[var(--color-text-ink)]">{{ $testimonial->author_name }}</p>
                                    <p class="text-xs text-[var(--color-text-muted)]">{{ $testimonial->author_title }}, {{ $testimonial->author_company }}</p>
                                </div>
                            </div>

                            <div class="text-right text-[11px] uppercase tracking-[0.04em] text-[var(--color-text-muted)]">
                                <p>Standar Inspeksi</p>
                                <p class="font-semibold text-[var(--color-primary)]">{{ $testimonial->standard_badge }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['partners_intro'] ?? '' }}</p>
            <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['partners_note'] ?? '' }}</p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($partners as $partner)
                @php
                    [$partnerName, $partnerLocation] = array_pad(explode(' — ', $partner->name, 2), 2, null);
                @endphp
                <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 90 }}" class="rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ sprintf('Stamp %02d', $loop->iteration) }}</p>
                    @if ($partner->contentImageUrl('thumbnail'))
                        <x-ui.placeholder-image
                            :src="$partner->contentImageUrl('thumbnail')"
                            :srcset="$partner->contentImageSrcset('thumbnail')"
                            sizes="160px"
                            :label="'Logo '.$partnerName"
                            class="mt-3 h-16 w-full object-contain"
                            loading="lazy"
                            :width="400"
                            :height="400"
                        />
                    @endif
                    <h3 class="mt-3 font-heading text-lg leading-snug text-[var(--color-text-ink)]">{{ $partnerName }}</h3>
                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ $partnerLocation }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 flex flex-col gap-4 border-t border-[var(--color-border-hairline)] pt-6 text-xs text-[var(--color-text-muted)] lg:flex-row lg:items-center lg:justify-between">
            <p class="max-w-xl leading-relaxed">{{ $content['footer_note'] ?? '' }}</p>
            <div class="flex flex-col gap-1 uppercase tracking-[0.04em] lg:items-end">
                <span>{{ $content['footer_port'] ?? '' }}</span>
                <span class="font-semibold text-[var(--color-primary)]">{{ $content['footer_doc'] ?? '' }}</span>
            </div>
        </div>
    </div>
</section>
