<x-filament-widgets::widget>
    <x-filament::section class="refi-admin-welcome">
        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
            <div class="space-y-2">
                <p class="text-sm font-semibold tracking-wide text-amber-800 uppercase">
                    Ruang kerja
                </p>
                <h2 class="text-2xl font-semibold tracking-tight text-stone-950">
                    Kelola website dan buyer dari satu tempat.
                </h2>
                <p class="max-w-2xl text-sm leading-6 text-stone-600">
                    Mulai dari tugas yang paling penting: tindak lanjuti inquiry baru, perbarui konten, atau cek katalog produk yang tampil di website.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-filament::button
                    tag="a"
                    :href="\App\Filament\Resources\Inquiries\InquiryResource::getUrl()"
                    icon="heroicon-o-envelope-open"
                >
                    Tinjau pesan masuk
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    :href="\App\Filament\Resources\PageContents\PageContentResource::getUrl()"
                    icon="heroicon-o-pencil-square"
                    color="gray"
                >
                    Ubah isi website
                </x-filament::button>
            </div>
        </div>

        <div class="mt-5 grid gap-3 border-t border-stone-200 pt-5 text-sm md:grid-cols-3">
            <div class="rounded-xl bg-white/70 p-3 text-stone-600">
                <span class="font-semibold text-stone-900">Pesan & Follow-up</span>
                <p class="mt-1 leading-5">Tinjau inquiry, perbarui status, dan jadwalkan tindak lanjut buyer.</p>
            </div>
            <div class="rounded-xl bg-white/70 p-3 text-stone-600">
                <span class="font-semibold text-stone-900">Website & Konten</span>
                <p class="mt-1 leading-5">Setiap field menjelaskan bagian halaman yang akan berubah.</p>
            </div>
            <div class="rounded-xl bg-white/70 p-3 text-stone-600">
                <span class="font-semibold text-stone-900">Katalog Produk</span>
                <p class="mt-1 leading-5">Tambah produk, foto, label, spesifikasi, dan status tampilnya.</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
