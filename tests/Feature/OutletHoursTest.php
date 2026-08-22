<?php

namespace Tests\Feature;

use App\Models\OutletOperatingHour;
use App\Models\User;
use App\Services\CashierOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OutletHoursTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_is_open_now_follows_toggle_when_hours_are_missing(): void
    {
        $world = $this->createGuestRestaurant();

        $this->assertTrue($world['outlet']->fresh(['restaurant', 'operatingHours', 'closedDates'])->isOpenNow());

        $world['outlet']->update(['is_open' => false]);

        $this->assertFalse($world['outlet']->fresh(['restaurant', 'operatingHours', 'closedDates'])->isOpenNow());
    }

    public function test_guest_claim_is_rejected_when_today_is_closed(): void
    {
        $world = $this->createGuestRestaurant();

        OutletOperatingHour::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'day_of_week' => now('Asia/Jakarta')->dayOfWeek,
            'opens_at' => '10:00:00',
            'closes_at' => '22:00:00',
            'is_closed' => true,
        ]);

        $this->assertFalse($world['outlet']->fresh(['restaurant', 'operatingHours', 'closedDates'])->isOpenNow());

        $device = $this->newDeviceToken();
        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertUnprocessable();
    }

    public function test_cashier_can_still_place_order_outside_hours_if_toggle_is_open(): void
    {
        $world = $this->createGuestRestaurant();

        OutletOperatingHour::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'day_of_week' => now('Asia/Jakarta')->dayOfWeek,
            'opens_at' => '10:00:00',
            'closes_at' => '22:00:00',
            'is_closed' => true,
        ]);

        $order = app(CashierOrderService::class)->create(
            User::factory()->create(),
            $world['table'],
            '081234567890',
            'Walk-in',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        $this->assertSame('awaiting_cashier', $order->status);
        $this->assertSame('cashier', $order->source);
    }
}
