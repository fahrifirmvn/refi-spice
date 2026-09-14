@props(['content' => [], 'featuredProducts' => null, 'otherProducts' => null])

<section id="koleksi-produk" class="bg-[var(--color-bg-beige)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div data-aos="fade-up" class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.1em] text-[var(--color-primary)]">
            <span>{{ $content['eyebrow_label'] ?? '' }}</span>
            <span class="h-px flex-1 bg-[var(--color-accent-gold)]/50"></span>
            <span class="text-[var(--color-text-muted)]">{{ $content['eyebrow_sub'] ?? '' }}</span>
        </div>

        <h2 data-aos="fade-up" class="mt-5 font-heading text-3xl leading-tight text-[var(--color-text-ink)] sm:text-4xl">
            {{ $content['headline'] ?? '' }}
        </h2>

        <p data-aos="fade-up" class="mt-4 max-w-2xl text-[15px] leading-relaxed text-[var(--color-text-muted)]">
            {{ $content['body'] ?? '' }}
        </p>

        <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredProducts as $product)
                <article data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}" class="group flex flex-col rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-cream)] p-5 transition-colors duration-300 hover:border-[var(--color-primary)]">
                    <div class="flex items-center justify-between rounded bg-[var(--color-bg-beige)] px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">
                        <span>{{ sprintf('Lot %02d // %s', $loop->iteration, $product->origin_label) }}</span>
                    </div>

                    <p class="mt-4 text-[11px] font-semibold uppercase tracking-[0.08em] text-[var(--color-text-muted)]">Spesies Primer</p>
                    <h3 class="mt-1 font-heading text-2xl text-[var(--color-text-ink)]">{{ $product->name }}</h3>
                    <p class="font-heading text-sm italic text-[var(--color-text-muted)]">{{ $product->latin_name }}</p>

                    <div class="relative mt-4 overflow-hidden rounded-md">
                        <x-ui.placeholder-image
                            :label="$product->name"
                            class="aspect-[4/3] photo-heritage-grade rounded-md transition-transform duration-[400ms] group-hover:scale-[1.03]"
                        />
                        <span class="absolute bottom-3 left-3 rounded bg-[var(--color-bg-cream)]/90 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">
                            Origin: {{ strtoupper($product->origin_region) }}
                        </span>
                    </div>

                    <dl class="mt-4 divide-y divide-[var(--color-border-hairline)] text-sm">
                        @foreach ($product->specifications as $spec)
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-muted)]">{{ $spec->spec_key }}</dt>
                                <dd class="font-medium text-[var(--color-text-ink)]">{{ $spec->spec_value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="mt-3 rounded-md border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-3">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">Profil Sensorik</p>
                        <p class="mt-1 text-sm leading-relaxed text-[var(--color-text-ink)]">{{ $product->sensory_profile }}</p>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-[var(--color-border-hairline)] pt-4 text-xs">
                        <span class="uppercase tracking-[0.04em] text-[var(--color-text-muted)]">Kesiapan Kontainer: <br>{{ $product->export_readiness }}</span>
                        <a href="#kontak" class="font-semibold uppercase tracking-[0.04em] text-[var(--color-primary)] transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Spesifikasi Batch Ekspor &rarr;</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div data-aos="fade-up" class="mt-10 flex flex-col gap-6 rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-cream)] p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['available_also_label'] ?? 'Tersedia Juga:' }}</span>
                @foreach ($otherProducts as $product)
                    <span class="rounded-full border border-[var(--color-border-hairline)] px-3 py-1.5 text-xs font-medium text-[var(--color-text-ink)]">{{ $product->name }}</span>
                @endforeach
            </div>

            <div class="flex flex-col items-start gap-3 border-t border-[var(--color-border-hairline)] pt-4 lg:flex-row lg:items-center lg:border-l lg:border-t-0 lg:pl-6 lg:pt-0">
                <div class="text-xs">
                    <p class="font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">{{ $content['lab_doc_label'] ?? '' }}</p>
                    <p class="mt-0.5 text-[var(--color-text-ink)]">{{ $content['lab_doc_value'] ?? '' }}</p>
                </div>
                <a href="#kontak" class="whitespace-nowrap rounded-md border border-[var(--color-border-hairline)] px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-text-ink)] transition-colors hover:border-[var(--color-primary)] hover:text-[var(--color-primary)]">
                    {{ $content['lab_cta_label'] ?? '' }}
                </a>
            </div>
        </div>
    </div>
</section>
