<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\CompanyStat;
use App\Models\Milestone;
use App\Models\PageContent;
use App\Models\Partner;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\ProductTag;
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
        return view('home', [
            'brand' => PageContent::query()->where('section_key', 'brand')->pluck('field_value', 'field_key'),
            'seo' => PageContent::query()->where('section_key', 'seo')->pluck('field_value', 'field_key'),
            'hero' => PageContent::query()->where('section_key', 'hero')->pluck('field_value', 'field_key'),
            'ceritaKami' => PageContent::query()->where('section_key', 'cerita_kami')->pluck('field_value', 'field_key'),
            'koleksiProduk' => PageContent::query()->where('section_key', 'koleksi_produk')->pluck('field_value', 'field_key'),
            'kualitas' => PageContent::query()->where('section_key', 'kualitas')->pluck('field_value', 'field_key'),
            'rantaiPasok' => PageContent::query()->where('section_key', 'rantai_pasok')->pluck('field_value', 'field_key'),
            'kemitraan' => PageContent::query()->where('section_key', 'kemitraan')->pluck('field_value', 'field_key'),
            'contact' => PageContent::query()->where('section_key', 'contact')->pluck('field_value', 'field_key'),
            'footer' => PageContent::query()->where('section_key', 'footer')->pluck('field_value', 'field_key'),
            'milestones' => Milestone::active()->ordered()->get(),
            'featuredProducts' => Product::active()->ordered()->with('specifications')->where('is_featured', true)->get(),
            'otherProducts' => Product::active()->ordered()->where('is_featured', false)->get(),
            'products' => Product::active()->ordered()->with('specifications')->get(),
            'productTags' => ProductTag::active()->ordered()->get(),
            'companyStats' => CompanyStat::active()->ordered()->get(),
            'certifications' => Certification::active()->ordered()->get(),
            'processSteps' => ProcessStep::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'partners' => Partner::active()->ordered()->get(),
            'commodityOptions' => Product::active()->ordered()->pluck('name'),
            'volumeOptions' => ['1-5 MT', '5-20 MT', '20+ MT'],
        ]);

    }
}
