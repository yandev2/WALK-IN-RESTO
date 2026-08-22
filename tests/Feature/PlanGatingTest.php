<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\CmsBanners\CmsBannerResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Outlets\OutletResource;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class PlanGatingTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_landing_only_hides_menu_and_operations(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
        ]);
        $owner = $this->makeStaff($restaurant, ['cms.manage', 'settings.manage', 'menu.manage', 'order.create']);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(CmsBannerResource::canViewAny());
        $this->assertTrue(OutletResource::canViewAny());
        $this->assertFalse(MenuItemResource::canViewAny());
        $this->assertFalse(OrderResource::canViewAny());
    }

    public function test_management_kds_shows_menu_resources(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => now()->addMonth(),
        ]);
        $owner = $this->makeStaff($restaurant, ['menu.manage', 'order.create']);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(MenuItemResource::canViewAny());
        $this->assertTrue(OrderResource::canViewAny());
    }
}
