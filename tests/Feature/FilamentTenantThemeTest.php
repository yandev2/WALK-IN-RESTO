<?php

namespace Tests\Feature;

use App\Http\Middleware\ApplyPlatformBrandTheme;
use App\Http\Middleware\ApplyRestaurantPanelTheme;
use App\Models\CmsProfile;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Support\FilamentTenantTheme;
use App\Support\RestaurantTheme;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class FilamentTenantThemeTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    public function test_apply_uses_tenant_cms_primary_color(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Theme Panel',
            'slug' => 'theme-panel',
            'is_active' => true,
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#112233',
            'accent_color' => '#AABBCC',
        ]);

        $restaurant->load('cmsProfile');

        FilamentTenantTheme::apply($restaurant);

        $this->assertSame(
            Color::hex('#112233'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_apply_falls_back_to_default_primary_without_tenant(): void
    {
        FilamentTenantTheme::apply(null);

        $this->assertSame(
            Color::hex(RestaurantTheme::DEFAULT_PRIMARY),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_apply_null_uses_platform_primary_color(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#334455']);

        FilamentTenantTheme::apply(null);

        $this->assertSame(
            Color::hex('#334455'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_platform_brand_middleware_skips_when_tenant_present(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#334455']);

        $restaurant = Restaurant::query()->create([
            'name' => 'Keep Tenant Color',
            'slug' => 'keep-tenant-color',
            'is_active' => true,
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#224466',
        ]);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant, isQuiet: true);

        FilamentTenantTheme::apply($restaurant);

        app(ApplyPlatformBrandTheme::class)->handle(
            request(),
            fn () => response('ok'),
        );

        $this->assertSame(
            Color::hex('#224466'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_middleware_applies_theme_for_current_tenant(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Middleware Theme',
            'slug' => 'middleware-theme',
            'is_active' => true,
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#224466',
            'accent_color' => '#AABBCC',
        ]);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant, isQuiet: true);

        app(ApplyRestaurantPanelTheme::class)->handle(
            request(),
            fn () => response('ok'),
        );

        $this->assertSame(
            Color::hex('#224466'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_platform_brand_middleware_uses_owner_restaurant_color_when_tenant_not_set(): void
    {
        $this->seed(RolePermissionSeeder::class);

        PlatformSetting::current()->update(['primary_color' => '#334455']);

        $restaurant = $this->makeRestaurant();
        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#112233',
        ]);
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');

        $this->assertNull(Filament::getTenant());
        $this->assertTrue($restaurant->is(FilamentTenantTheme::restaurantForCurrentPanel()));

        app(ApplyPlatformBrandTheme::class)->handle(
            request(),
            fn () => response('ok'),
        );

        $this->assertSame(
            Color::hex('#112233'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_owner_profile_page_uses_restaurant_cms_primary_color(): void
    {
        $this->seed(RolePermissionSeeder::class);

        PlatformSetting::current()->update(['primary_color' => '#334455']);

        $restaurant = $this->makeRestaurant();
        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#112233',
        ]);
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner)
            ->get('/admin/profile')
            ->assertOk();

        $this->assertSame(
            Color::hex('#112233'),
            FilamentColor::getColor('primary'),
        );
    }

    public function test_founder_panel_keeps_platform_primary_color(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#334455']);

        Filament::setCurrentPanel('founder');

        app(ApplyPlatformBrandTheme::class)->handle(
            request(),
            fn () => response('ok'),
        );

        $this->assertSame(
            Color::hex('#334455'),
            FilamentColor::getColor('primary'),
        );
    }
}
