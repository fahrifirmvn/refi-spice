<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Inquiry;
use App\Models\Partner;
use App\Models\Product;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteSummaryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pesan Baru Belum Ditindaklanjuti', Inquiry::query()->where('status', 'new')->count())
                ->description('Segera hubungi calon pembeli yang mengirim inquiry')
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->color('warning')
                ->url(InquiryResource::getUrl()),
            Stat::make('Produk Aktif Tampil di Website', Product::query()->where('is_active', true)->count())
                ->description('Produk yang saat ini muncul di halaman Koleksi Produk')
                ->icon(Heroicon::OutlinedCube)
                ->color('success')
                ->url(ProductResource::getUrl()),
            Stat::make('Mitra Aktif Tampil di Website', Partner::query()->where('is_active', true)->count())
                ->description('Logo mitra yang saat ini muncul di halaman Kemitraan')
                ->icon(Heroicon::OutlinedUserGroup)
                ->color('info')
                ->url(PartnerResource::getUrl()),
        ];
    }
}
