<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Certification;
use App\Models\Partner;
use App\Models\Product;
use Filament\Widgets\Widget;

class ContentHealthWidget extends Widget
{
    protected static ?int $sort = 0;

    protected string $view = 'filament.widgets.content-health-widget';

    /**
     * @return array<int, array{label: string, count: int, url: string}>
     */
    public function getIssues(): array
    {
        return collect([
            ['label' => 'Produk aktif belum punya foto', 'count' => Product::active()->missingContentImage()->count(), 'url' => ProductResource::getUrl()],
            ['label' => 'Produk aktif belum punya spesifikasi', 'count' => Product::active()->doesntHave('specifications')->count(), 'url' => ProductResource::getUrl()],
            ['label' => 'Mitra aktif belum punya logo', 'count' => Partner::active()->missingContentImage()->count(), 'url' => PartnerResource::getUrl()],
            ['label' => 'Sertifikasi aktif belum punya logo', 'count' => Certification::active()->missingContentImage()->count(), 'url' => CertificationResource::getUrl()],
        ])
            ->filter(fn (array $issue): bool => $issue['count'] > 0)
            ->values()
            ->all();
    }
}
