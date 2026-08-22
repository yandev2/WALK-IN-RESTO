<?php

namespace Tests\Feature;

use App\Enums\SubscriptionStatus;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class ExpiredPanelAccessTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_expired_tenant_is_redirected_to_subscription_page(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Expired,
        ]);
        $owner = $this->makeStaff($restaurant, ['cms.manage']);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');

        $this->get(Dashboard::getUrl(tenant: $restaurant))
            ->assertRedirect(SubscriptionStatusPage::getUrl(tenant: $restaurant));

        $this->get(SubscriptionStatusPage::getUrl(tenant: $restaurant))
            ->assertOk();
    }
}
