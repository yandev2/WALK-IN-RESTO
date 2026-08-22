<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestOrderGatingTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_landing_only_blocks_web_order_scan(): void
    {
        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Active,
            'landing_enabled' => true,
        ]);

        $this->get('/order/t/'.$world['token'])
            ->assertRedirect(route('landing.show', $world['restaurant']));
    }

    public function test_landing_only_blocks_api_claim(): void
    {
        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Active,
        ]);

        $device = $this->newDeviceToken();

        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertForbidden()
            ->assertJsonPath('code', 'operations_unavailable');
    }

    public function test_management_plan_still_allows_scan_page(): void
    {
        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active,
        ]);

        $this->get('/order/t/'.$world['token'])->assertOk();
    }
}
