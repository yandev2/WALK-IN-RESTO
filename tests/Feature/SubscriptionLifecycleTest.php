<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Services\SubscriptionInvoiceService;
use App\Services\SubscriptionLifecycleService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class SubscriptionLifecycleTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_trial_moves_to_grace_then_expired(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->subMinute(),
        ]);

        $lifecycle = app(SubscriptionLifecycleService::class);

        $this->assertSame('grace', $lifecycle->transition($restaurant));
        $restaurant->refresh();
        $this->assertSame(SubscriptionStatus::Grace, $restaurant->subscription_status);
        $this->assertNotNull($restaurant->grace_ends_at);

        $restaurant->forceFill(['grace_ends_at' => now()->subMinute()])->save();
        $this->assertSame('expired', $lifecycle->transition($restaurant->fresh()));
        $this->assertSame(SubscriptionStatus::Expired, $restaurant->fresh()->subscription_status);
    }

    public function test_paid_invoice_interrupts_grace(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(2),
            'trial_ends_at' => now()->subDay(),
        ]);

        $founder = $this->makeFounder();
        $service = app(SubscriptionInvoiceService::class);
        $invoice = $service->createManual($restaurant, PlanCode::ManagementKds->value, $founder, 1);
        $service->submitProof($invoice, PlanCode::ManagementKds->value, 2, 'subscription-proofs/demo.jpg');
        $service->approve($invoice->fresh(), $founder);

        $restaurant->refresh();
        $this->assertSame(SubscriptionStatus::Active, $restaurant->subscription_status);
        $this->assertSame(PlanCode::ManagementKds->value, $restaurant->plan_code);
        $this->assertNull($restaurant->grace_ends_at);
        $this->assertTrue($restaurant->subscribed_until->gt(now()->addMonth()));

        $this->assertNull(app(SubscriptionLifecycleService::class)->transition($restaurant));
        $this->assertSame(SubscriptionStatus::Active, $restaurant->fresh()->subscription_status);
    }

    public function test_active_moves_to_grace_when_subscribed_until_passes(): void
    {
        $restaurant = $this->makeRestaurant([
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => now()->subMinute(),
            'trial_ends_at' => null,
        ]);

        $this->assertSame('grace', app(SubscriptionLifecycleService::class)->transition($restaurant));
        $this->assertSame(SubscriptionStatus::Grace, $restaurant->fresh()->subscription_status);
    }
}
