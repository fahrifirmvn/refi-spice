<x-filament-widgets::widget>
    <x-filament::section class="refi-admin-welcome" compact>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-stone-950">
                    Halo, {{ auth()->user()?->name }}
                </h2>
                <p class="text-sm text-stone-600">Pilih tugas yang ingin dikerjakan.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-filament::button tag="a" :href="\App\Filament\Resources\Inquiries\InquiryResource::getUrl()" icon="heroicon-o-envelope-open">
                    Pesan masuk
                </x-filament::button>
                <x-filament::button tag="a" :href="\App\Filament\Pages\EditHomepageContent::getUrl()" icon="heroicon-o-pencil-square" color="gray">
                    Ubah isi halaman depan
                </x-filament::button>
                <x-filament::button tag="a" :href="\App\Filament\Resources\Products\ProductResource::getUrl('create')" icon="heroicon-o-plus" color="gray">
                    Tambah produk
                </x-filament::button>
                <x-filament::button tag="a" :href="route('home')" target="_blank" icon="heroicon-o-arrow-top-right-on-square" color="gray">
                    Lihat website
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
