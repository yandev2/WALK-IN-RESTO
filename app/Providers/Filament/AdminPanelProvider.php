<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\KitchenDisplay;
use App\Filament\Pages\SubscriptionStatus;
use App\Filament\Profile\EditProfile;
use App\Http\Middleware\ApplyPlatformBrandTheme;
use App\Http\Middleware\ApplyRestaurantPanelTheme;
use App\Http\Middleware\EnsureTenantSubscription;
use App\Http\Middleware\SetPermissionsTeamId;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\FilamentTenantTheme;
use App\Support\RestaurantTheme;
use App\Support\SubscriptionGate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Hammadzafar05\FilamentMobilePreset\FilamentMobilePresetPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leek\FilamentRightClick\FilamentRightClickPlugin;
use Zvizvi\FilamentNotificationsTabs\FilamentNotificationsTabsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->brandName('Resto Admin')
            ->colors(fn (): array => [
                'primary' => Color::hex(
                    RestaurantTheme::for(FilamentTenantTheme::restaurantForCurrentPanel())['primary']
                ),
            ])
            ->tenant(Restaurant::class, slugAttribute: 'slug')
            ->tenantMenu(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->homeUrl(function (): ?string {
                $tenant = Filament::getTenant();
                $user = auth()->user();

                if (! $tenant || ! $user instanceof User) {
                    return null;
                }

                if (! $user->isPlatformOperator() && ! app(SubscriptionGate::class)->canAccessPanel($tenant)) {
                    return SubscriptionStatus::getUrl(tenant: $tenant);
                }

                $kitchenOnly = ! $user->isSuperAdmin()
                    && $user->can('kds.view')
                    && ! $user->can('order.verify_payment')
                    && ! $user->can('cms.manage')
                    && ! $user->can('settings.manage');

                if ($kitchenOnly) {
                    return KitchenDisplay::getUrl(tenant: $tenant);
                }

                return Dashboard::getUrl(tenant: $tenant);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->databaseNotifications()
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentMobilePresetPlugin::make(),
                FilamentRightClickPlugin::make(),
                EditProfile::plugin()
                    ->group('Pengaturan')
                    ->sort(99)
                    ->visible(fn (): bool => auth()->user() instanceof User
                        && auth()->user()->isRestaurantOwner()),
                FilamentNotificationsTabsPlugin::make()
                    ->confirmDelete(),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <style>
                        .fi-page-dashboard .fi-wi-analytics-kpi,
                        .fi-page-dashboard .fi-wi-analytics-period-summary,
                        .fi-page-dashboard .fi-wi-analytics-revenue,
                        .fi-page-dashboard .fi-wi-analytics-sidebar,
                        .fi-page-dashboard .fi-wi-analytics-top-menu,
                        .fi-page-dashboard .fi-wi-welcome-banner {
                            align-self: stretch;
                        }

                        .fi-page-dashboard .fi-wi-analytics-kpi > .ad-dashboard,
                        .fi-page-dashboard .fi-wi-analytics-period-summary > .ad-dashboard,
                        .fi-page-dashboard .fi-wi-analytics-revenue > .ad-dashboard,
                        .fi-page-dashboard .fi-wi-analytics-sidebar > .ad-dashboard,
                        .fi-page-dashboard .fi-wi-analytics-top-menu > .ad-dashboard {
                            height: 100%;
                        }

                        .fi-page-dashboard .fi-wi-analytics-revenue > .ad-dashboard > .ad-card,
                        .fi-page-dashboard .fi-wi-analytics-sidebar > .ad-dashboard > .ad-card,
                        .fi-page-dashboard .fi-wi-analytics-period-summary > .ad-dashboard > .ad-card,
                        .fi-page-dashboard .fi-wi-analytics-top-menu > .ad-dashboard > .ad-card {
                            height: 100%;
                        }

                        .fi-dashboard-analytics-toolbar {
                            border-radius: 1.25rem;
                            border: 1px solid rgb(226 232 240);
                            background: rgb(255 255 255);
                            box-shadow: 0 10px 30px -18px rgb(15 23 42 / 0.28);
                            padding: 1rem 1.15rem;
                            margin-bottom: 0.125rem;
                        }

                        .dark .fi-dashboard-analytics-toolbar {
                            border-color: rgb(55 65 81);
                            background: rgb(17 24 39);
                            box-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.55);
                        }

                        .fi-dashboard-analytics-toolbar .fi-sc-actions {
                            height: auto;
                        }

                        .fi-dashboard-analytics-toolbar .fi-sc-actions .fi-ac {
                            flex-wrap: nowrap;
                        }

                        .fi-page-dashboard .fi-wi-widget.fi-wi-analytics-kpi,
                        .fi-page-dashboard .fi-wi-widget.fi-wi-analytics-period-summary,
                        .fi-page-dashboard .fi-wi-widget.fi-wi-analytics-revenue,
                        .fi-page-dashboard .fi-wi-widget.fi-wi-analytics-sidebar,
                        .fi-page-dashboard .fi-wi-widget.fi-wi-analytics-top-menu,
                        .fi-page-dashboard .fi-wi-widget.fi-wi-welcome-banner {
                            --tw-ring-shadow: 0 0 #0000;
                            --tw-shadow: 0 0 #0000;
                        }
                    </style>
                    HTML),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                function (): HtmlString {
                    $tenant = Filament::getTenant();
                    $user = auth()->user();

                    if (! $tenant instanceof Restaurant || ! $user instanceof User || $user->isPlatformOperator()) {
                        return new HtmlString('');
                    }

                    if (! app(SubscriptionGate::class)->isReadOnly($tenant)) {
                        return new HtmlString('');
                    }

                    return new HtmlString(view('filament.hooks.subscription-grace-banner', [
                        'message' => 'Masa langganan dalam mode lihat saja. Segera lunasi invoice untuk membuka akses penuh.',
                        'url' => SubscriptionStatus::getUrl(tenant: $tenant),
                    ])->render());
                },
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
            ->tenantMiddleware([
                SetPermissionsTeamId::class,
                ApplyRestaurantPanelTheme::class,
                EnsureTenantSubscription::class,
            ], isPersistent: true);
    }
}
