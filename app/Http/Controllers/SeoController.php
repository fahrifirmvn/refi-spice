<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Minimal sitemap for the single-page site (PRD Section 10.1).
     */
    public function sitemap(): Response
    {
        $lastmod = PageContent::query()->max('updated_at');
        $lastmod = $lastmod ? date('Y-m-d', strtotime($lastmod)) : now()->format('Y-m-d');

        $xml = view('sitemap', ['lastmod' => $lastmod])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * robots.txt served via a route (not a static public/ file) so the
     * Sitemap directive can use the real app URL without hardcoding a
     * production hostname in source (PRD Section 10.1 / 18).
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain');
    }
}
