@props([
    'content' => [],
    'footer' => [],
    'brand' => [],
    'commodityOptions' => null,
    'volumeOptions' => [],
])

<section id="kontak" class="bg-[var(--color-bg-cream)] px-6 py-20 sm:py-24">
    <div class="mx-auto max-w-(--container-heritage)">
        <div data-aos="fade-up" class="inline-flex items-center gap-2 rounded-full border border-[var(--color-border-hairline)] px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[0.08em] text-[var(--color-primary)]">
            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
                <path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" stroke="currentColor" stroke-width="1.5" />
            </svg>
            {{ $content['eyebrow'] ?? '' }}
        </div>

        <h2 data-aos="fade-up" class="mt-5 max-w-2xl font-heading text-3xl leading-tight text-[var(--color-text-ink)] sm:text-4xl">
            {{ $content['headline'] ?? '' }}
        </h2>

        <p data-aos="fade-up" class="mt-4 max-w-2xl text-[15px] leading-relaxed text-[var(--color-text-muted)]">
            {{ $content['body'] ?? '' }}
        </p>

        <div class="mt-12 grid grid-cols-1 gap-10 lg:grid-cols-12">
            {{-- Inquiry form --}}
            <div data-aos="fade-up" class="lg:col-span-7">
                <form method="POST" action="{{ route('inquiry.store') }}" class="rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-6 sm:p-8" novalidate>
                    @csrf

                    @if (session('success'))
                        <div class="mb-6 rounded-md border border-[var(--color-success)]/40 bg-[var(--color-success)]/10 px-4 py-3 text-sm text-[var(--color-success)]" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-md border border-[var(--color-error)]/40 bg-[var(--color-error)]/10 px-4 py-3 text-sm text-[var(--color-error)]" role="alert">
                            Mohon periksa kembali kolom yang ditandai di bawah ini.
                        </div>
                    @endif

                    {{-- Honeypot: hidden from real users, left empty; bots that auto-fill it are silently rejected server-side --}}
                    <div class="absolute left-[-9999px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
                        <label for="website">Jangan diisi</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-[0.06em] text-[var(--color-text-muted)]">
                        <span>{{ $content['form_eyebrow'] ?? '' }}</span>
                        <span>{{ $content['form_ref'] ?? '' }}</span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="full_name" class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">Nama Lengkap &amp; Gelar *</label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                placeholder="e.g. Dr. Julian Vance"
                                aria-describedby="full_name-error"
                                class="mt-2 w-full border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] placeholder:text-[var(--color-text-muted)]/60 focus:border-[var(--color-primary)] focus:outline-none"
                            >
                            @error('full_name')
                                <p id="full_name-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <span class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">Perusahaan &amp; Negara Tujuan *</span>
                            <div class="mt-2 flex gap-3">
                                <div class="w-1/2">
                                    <label for="company_name" class="sr-only">Nama Perusahaan</label>
                                    <input
                                        type="text"
                                        id="company_name"
                                        name="company_name"
                                        value="{{ old('company_name') }}"
                                        placeholder="e.g. Batavia Botanicals Ltd."
                                        aria-describedby="company_name-error"
                                        class="w-full border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] placeholder:text-[var(--color-text-muted)]/60 focus:border-[var(--color-primary)] focus:outline-none"
                                    >
                                    @error('company_name')
                                        <p id="company_name-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-1/2">
                                    <label for="destination_country" class="sr-only">Negara Tujuan</label>
                                    <input
                                        type="text"
                                        id="destination_country"
                                        name="destination_country"
                                        value="{{ old('destination_country') }}"
                                        placeholder="e.g. Rotterdam, NL"
                                        aria-describedby="destination_country-error"
                                        class="w-full border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] placeholder:text-[var(--color-text-muted)]/60 focus:border-[var(--color-primary)] focus:outline-none"
                                    >
                                    @error('destination_country')
                                        <p id="destination_country-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="commodity_interest" class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">Komoditas Rempah yang Diminati *</label>
                            <select
                                id="commodity_interest"
                                name="commodity_interest"
                                aria-describedby="commodity_interest-error"
                                class="mt-2 w-full border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] focus:border-[var(--color-primary)] focus:outline-none"
                            >
                                <option value="">Pilih Varian Komoditas</option>
                                @foreach ($commodityOptions as $option)
                                    <option value="{{ $option }}" @selected(old('commodity_interest') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('commodity_interest')
                                <p id="commodity_interest-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="volume_estimate" class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">Estimasi Volume per Pengapalan *</label>
                            <select
                                id="volume_estimate"
                                name="volume_estimate"
                                aria-describedby="volume_estimate-error"
                                class="mt-2 w-full border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] focus:border-[var(--color-primary)] focus:outline-none"
                            >
                                <option value="">Pilih Skala Pengapalan</option>
                                @foreach ($volumeOptions as $option)
                                    <option value="{{ $option }}" @selected(old('volume_estimate') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('volume_estimate')
                                <p id="volume_estimate-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="additional_notes" class="text-xs font-semibold uppercase tracking-[0.04em] text-[var(--color-text-ink)]">Pesan Tambahan / Permintaan Sampel Lab</label>
                            <textarea
                                id="additional_notes"
                                name="additional_notes"
                                rows="3"
                                placeholder="Cantumkan standar kadar air, sertifikasi yang dibutuhkan (USDA/EU Organic, Kosher, Halal), atau spesifikasi potongan..."
                                aria-describedby="additional_notes-error"
                                class="mt-2 w-full resize-none border-b border-[var(--color-border-hairline)] bg-transparent pb-2 text-sm text-[var(--color-text-ink)] placeholder:text-[var(--color-text-muted)]/60 focus:border-[var(--color-primary)] focus:outline-none"
                            >{{ old('additional_notes') }}</textarea>
                            @error('additional_notes')
                                <p id="additional_notes-error" class="mt-1 text-xs text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-4 border-t border-[var(--color-border-hairline)] pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="flex items-center gap-2 text-xs text-[var(--color-text-muted)]">
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0">
                                <path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                            </svg>
                            {{ $content['form_trust_note'] ?? '' }}
                        </p>

                        <button
                            type="submit"
                            class="whitespace-nowrap rounded-md bg-[var(--color-primary)] px-6 py-3 text-xs font-semibold uppercase tracking-[0.08em] text-[var(--color-text-cream)] transition-all duration-200 hover:scale-[1.02] hover:opacity-90"
                        >
                            {{ $content['submit_label'] ?? 'Kirim' }} &rarr;
                        </button>
                    </div>
                </form>
            </div>

            {{-- Contact details --}}
            <div data-aos="fade-up" data-aos-delay="100" class="flex flex-col gap-6 lg:col-span-5">
                <div class="rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-5">
                    <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                            <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z" stroke="currentColor" stroke-width="1.5" />
                            <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        {{ $content['coordinates_label'] ?? '' }}
                    </p>
                    <p class="mt-2 font-heading text-lg text-[var(--color-text-ink)]">{{ $content['coordinates_value'] ?? '' }}</p>
                    <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ $content['coordinates_sub'] ?? '' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">Head Office</p>
                    <p class="mt-1 text-sm leading-relaxed text-[var(--color-text-ink)]">{{ $content['head_office_address'] ?? '' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">Processing &amp; Sorting Facilities</p>
                    <p class="mt-1 text-sm leading-relaxed text-[var(--color-text-ink)]">{{ $content['processing_facilities'] ?? '' }}</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">Direct Desk</p>
                        <p class="mt-1 text-sm text-[var(--color-text-ink)]">{{ $content['email'] ?? '' }}</p>
                        <p class="text-sm text-[var(--color-text-ink)]">{{ $content['phone'] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">Commercial WhatsApp</p>
                        <p class="mt-1 text-sm text-[var(--color-text-ink)]">{{ $content['whatsapp'] ?? '' }}</p>
                        <p class="text-xs text-[var(--color-text-muted)]">{{ $content['whatsapp_caption'] ?? '' }}</p>
                    </div>
                </div>

                <p class="text-sm text-[var(--color-text-ink)]">
                    <span class="font-semibold uppercase tracking-[0.04em] text-[var(--color-primary)]">Jam Operasional:</span>
                    {{ $content['operational_hours'] ?? '' }}
                </p>

                <div class="flex items-center justify-between rounded-lg border border-[var(--color-border-hairline)] bg-[var(--color-bg-beige)] p-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.06em] text-[var(--color-primary)]">{{ $content['audit_label'] ?? '' }}</p>
                        <p class="mt-1 text-sm text-[var(--color-text-ink)]">{{ $content['audit_value'] ?? '' }}</p>
                    </div>
                    <span class="rounded border border-[var(--color-border-hairline)] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.04em] text-[var(--color-text-muted)]">
                        {{ $content['audit_badge'] ?? '' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-20 -mx-6 bg-[var(--color-bg-dark)] px-6 py-10">
        <div class="mx-auto flex max-w-(--container-heritage) flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-heading text-lg tracking-[0.06em] text-[var(--color-accent-gold)]">REFI SPICE</p>
                <p class="mt-2 max-w-md text-xs leading-relaxed text-[var(--color-text-cream-muted)]">{{ $footer['copyright'] ?? '' }}</p>
            </div>

            <nav class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium uppercase tracking-[0.04em] text-[var(--color-text-cream-muted)]" aria-label="Navigasi footer">
                <a href="#" class="transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Spesifikasi Komoditas</a>
                <a href="#" class="transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Sertifikasi Fitosanitari</a>
                <a href="#" class="transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Ketentuan Ekspor</a>
                <a href="#" class="transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Dokumentasi Asal Usul</a>
                <a href="#" class="transition-colors duration-200 hover:text-[var(--color-accent-gold)]">Kebijakan Privasi</a>
            </nav>
        </div>
    </footer>
</section>
