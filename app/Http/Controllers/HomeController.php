<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\CompanyStat;
use App\Models\Milestone;
use App\Models\PageContent;
use App\Models\Partner;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Render the single-page company profile with all 7 sections
     * (PRD Section 4 & 16 — data ditarik dari database, bukan hardcode).
     */
    public function index(): View
    {
        $pageContents = PageContent::query()->with('media')->get();
        $contentsBySection = $pageContents
            ->groupBy('section_key')
            ->map(fn ($sectionContents) => $sectionContents->pluck('field_value', 'field_key'));
        $seo = $contentsBySection->get('seo', collect());
        $openGraphImage = $pageContents->first(
            fn (PageContent $pageContent): bool => $pageContent->isOpenGraphImageField()
        );

        $seo->put('og_image_url', $openGraphImage?->openGraphImageUrl());

        $brand = $contentsBySection->get('brand', collect());
        $brand->put('logo_url', $pageContents->first(
            fn (PageContent $pageContent): bool => $pageContent->isLogoField()
        )?->logoUrl());

        return view('home', [
            'brand' => $brand,
            'seo' => $seo,
            'hero' => $contentsBySection->get('hero', collect()),
            'ceritaKami' => $contentsBySection->get('cerita_kami', collect()),
            'koleksiProduk' => $contentsBySection->get('koleksi_produk', collect()),
            'kualitas' => $contentsBySection->get('kualitas', collect()),
            'rantaiPasok' => $contentsBySection->get('rantai_pasok', collect()),
            'kemitraan' => $contentsBySection->get('kemitraan', collect()),
            'contact' => $contentsBySection->get('contact', collect()),
            'footer' => $contentsBySection->get('footer', collect()),
            'milestones' => Milestone::active()->ordered()->get(),
            'featuredProducts' => Product::active()
                ->ordered()
                ->with([
                    'media',
                    'specifications',
                    'tags' => fn ($query) => $query->active()->ordered(),
                ])
                ->where('is_featured', true)
                ->get(),
            'otherProducts' => Product::active()->ordered()->where('is_featured', false)->get(),
            'products' => Product::active()->ordered()->with('specifications')->get(),
            'companyStats' => CompanyStat::active()->ordered()->get(),
            'certifications' => Certification::active()->approvedForPublic()->ordered()->with('media')->get(),
            'processSteps' => ProcessStep::active()->ordered()->with('media')->get(),
            'testimonials' => Testimonial::active()->approvedForPublic()->ordered()->get(),
            'partners' => Partner::active()->ordered()->with('media')->get(),
            'commodityOptions' => Product::active()->ordered()->pluck('name'),
            'volumeOptions' => ['1-5 MT', '5-20 MT', '20+ MT'],
        ]);
    }
}
