<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Refi Spice')
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/brand/favicon-32.png'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => [
                    50 => '#f2f7ec',
                    100 => '#e4edd8',
                    200 => '#c9dbb3',
                    300 => '#a5c183',
                    400 => '#7da25a',
                    500 => '#4a7a34',
                    600 => '#2f5224',
                    700 => '#27441e',
                    800 => '#1f3618',
                    900 => '#1b2b15',
                    950 => '#0f180b',
                ],
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Akun & Pengaturan')
                    ->icon(Heroicon::OutlinedCog6Tooth)
                    ->collapsed(),
            ])
            ->renderHook(PanelsRenderHook::PAGE_START, fn (): string => $this->backLinkForChildPages())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    private function backLinkForChildPages(): string
    {
        $route = request()->route()?->getName() ?? '';

        $parents = [
            'milestones' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'company-stats' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'process-steps' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'certifications' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'partners' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'testimonials' => ['isi-halaman-depan', 'Isi Halaman Depan'],
            'product-tags' => ['products', 'Daftar Produk'],
        ];

        foreach ($parents as $slug => [$target, $label]) {
            if (str_starts_with($route, "filament.admin.resources.{$slug}.")) {
                $url = $target === 'products'
                    ? route('filament.admin.resources.products.index')
                    : route('filament.admin.pages.isi-halaman-depan');

                return view('filament.back-link', ['url' => $url, 'label' => $label])->render();
            }
        }

        return '';
    }
}
