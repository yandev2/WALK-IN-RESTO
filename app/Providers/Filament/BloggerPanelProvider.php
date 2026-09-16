<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Blogger\Pages\Dashboard;
use App\Filament\Profile\EditProfile;
use App\Http\Middleware\ApplyPlatformBrandTheme;
use App\Models\PlatformSetting;
use App\Support\AuthGlass;
use App\Support\RestaurantTheme;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Zvizvi\FilamentNotificationsTabs\FilamentNotificationsTabsPlugin;

class BloggerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('blogger')
            ->path('blogger')
            ->login(Login::class)
            ->brandName('Citarasa Kita - Blog')
            ->favicon(function (): ?string {
                $platformFavicon = PlatformSetting::homeViewData()['favicon_url'] ?? asset('favicon.ico');
                return filled($platformFavicon) && ! str_starts_with($platformFavicon, 'http://') && ! str_starts_with($platformFavicon, 'https://')
                    ? url($platformFavicon)
                    : $platformFavicon;
            })
            ->colors([
                'primary' => Color::hex(RestaurantTheme::DEFAULT_PRIMARY),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Blogger/Resources'), for: 'App\\Filament\\Blogger\\Resources')
            ->discoverPages(in: app_path('Filament/Blogger/Pages'), for: 'App\\Filament\\Blogger\\Pages')
            ->discoverWidgets(in: app_path('Filament/Blogger/Widgets'), for: 'App\\Filament\\Blogger\\Widgets')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Blog'),
                NavigationGroup::make('Penulis & Pengguna'),
                NavigationGroup::make('Pengaturan'),
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->databaseNotifications()
            ->plugins([
                EditProfile::plugin()
                    ->group('Pengaturan')
                    ->sort(99),
                FilamentNotificationsTabsPlugin::make()
                    ->confirmDelete(),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(
                    AuthGlass::headHtml(includeVite: true)->toHtml().
                    '<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>'
                ),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): View => view('filament.components.panel-switcher'),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                ApplyPlatformBrandTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web');
    }
}
