<?php

namespace Tests\Unit;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Support\SubscriptionGate;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class SubscriptionGateTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_expired_cannot_access_panel(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Expired,
        ]);

        $gate = app(SubscriptionGate::class);

        $this->assertFalse($gate->canAccessPanel($restaurant));
        $this->assertFalse($gate->isReadOnly($restaurant));
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));
        $this->assertTrue($gate->hasFeature($restaurant, 'cms'));
    }

    public function test_grace_is_read_only_and_keeps_features(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Grace,
        ]);

        $gate = app(SubscriptionGate::class);

        $this->assertTrue($gate->canAccessPanel($restaurant));
        $this->assertTrue($gate->isReadOnly($restaurant));
        $this->assertTrue($gate->hasFeature($restaurant, 'menu'));
    }

    public function test_landing_only_hides_operations(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Trial,
        ]);

        $gate = app(SubscriptionGate::class);

        $this->assertTrue($gate->hasFeature($restaurant, 'cms'));
        $this->assertTrue($gate->hasFeature($restaurant, 'settings'));
        $this->assertFalse($gate->hasFeature($restaurant, 'settings_full'));
        $this->assertFalse($gate->hasFeature($restaurant, 'menu'));
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));
    }

    public function test_effective_expiry_prefers_subscribed_until(): void
    {
        $restaurant = $this->makeRestaurant([
            'trial_ends_at' => now()->addDays(2),
            'subscribed_until' => now()->addDays(20),
        ]);

        $expiry = app(SubscriptionGate::class)->effectiveExpiryAt($restaurant);

        $this->assertTrue($expiry->equalTo($restaurant->subscribed_until));
    }

    public function test_should_auto_invoice_within_lead_window(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(3),
        ]);

        $this->assertTrue(app(SubscriptionGate::class)->shouldAutoInvoice($restaurant));
    }

    public function test_should_not_auto_invoice_when_expiry_is_far(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(20),
        ]);

        $this->assertFalse(app(SubscriptionGate::class)->shouldAutoInvoice($restaurant));
    }
}
