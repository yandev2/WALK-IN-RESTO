<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Founder\Pages\Dashboard;
use App\Filament\Profile\EditProfile;
use App\Filament\Resources\RestaurantCategories\RestaurantCategoryResource;
use App\Http\Middleware\ApplyPlatformBrandTheme;
use App\Models\User;
use App\Support\AuthGlass;
use App\Support\RestaurantTheme;
use Bityukov\CommandCenter\Filament\CommandCenterPlugin;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Zvizvi\FilamentNotificationsTabs\FilamentNotificationsTabsPlugin;

class FounderPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('founder')
            ->path('founder')
            ->login(Login::class)
            ->brandName('RestoTerdekat Founder')
            ->colors([
                'primary' => Color::hex(RestaurantTheme::DEFAULT_PRIMARY),
            ])
            ->discoverResources(in: app_path('Filament/Founder/Resources'), for: 'App\\Filament\\Founder\\Resources')
            ->discoverPages(in: app_path('Filament/Founder/Pages'), for: 'App\\Filament\\Founder\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Founder/Widgets'), for: 'App\\Filament\\Founder\\Widgets')
            ->resources([
                RestaurantCategoryResource::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Platform'),
                NavigationGroup::make('Langganan'),
                NavigationGroup::make('Sistem'),
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->databaseNotifications()
            ->plugins([
                EditProfile::plugin()
                    ->group('Platform')
                    ->sort(99),
                CommandCenterPlugin::make()
                    ->group('Sistem')
                    ->navigationSort(80)
                    ->authorize(fn ($user): bool => $user instanceof User && $user->isPlatformOperator()),
                FilamentNotificationsTabsPlugin::make()
                    ->confirmDelete(),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => AuthGlass::headHtml(includeVite: true),
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
