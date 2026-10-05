@props([
    'label' => 'Foto konten',
    'watermarkText' => 'Placeholder — Menunggu Aset Klien',
    'src' => null,
    'srcset' => null,
    'sizes' => null,
    'loading' => 'lazy',
    'width' => null,
    'height' => null,
])

@if ($src)
    <img
        src="{{ $src }}"
        @if ($srcset) srcset="{{ $srcset }}" @endif
        @if ($sizes) sizes="{{ $sizes }}" @endif
        alt="{{ $label }}"
        loading="{{ $loading }}"
        @if ($width) width="{{ $width }}" @endif
        @if ($height) height="{{ $height }}" @endif
        {{ $attributes }}
    >
@else
    <div
        {{ $attributes->class(['relative flex items-center justify-center overflow-hidden bg-[var(--color-bg-beige)]']) }}
        role="img"
        aria-label="{{ $label }}"
    >
        <svg viewBox="0 0 48 48" fill="none" class="h-10 w-10 text-[var(--color-text-muted)]/30 sm:h-14 sm:w-14">
            <path d="M24 6C16 12 12 20 12 27a12 12 0 0 0 24 0c0-7-4-15-12-21Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
            <path d="M24 14v22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        </svg>

        @if (config('assets.placeholder_watermark'))
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center bg-[var(--color-text-ink)]/5 p-4">
                <span class="-rotate-6 rounded border border-dashed border-[var(--color-text-muted)]/50 bg-[var(--color-bg-cream)]/70 px-3 py-1 text-center text-[10px] font-semibold uppercase leading-tight tracking-[0.1em] text-[var(--color-text-muted)]/80">
                    {{ $watermarkText }}
                </span>
            </div>
        @endif
    </div>
@endif
