<?php

namespace Tests\Feature;

use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Models\Order;
use App\Models\OrderItemModifier;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class MenuModifierTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_required_modifier_blocks_add_and_paid_extra_joins_unit_price(): void
    {
        $world = $this->createGuestRestaurant();
        $item = $this->extraMenuItem($world);
        $group = ModifierGroup::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Extra',
            'min_select' => 1,
            'max_select' => 1,
            'is_required' => true,
        ]);
        $telur = Modifier::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'modifier_group_id' => $group->id,
            'name' => 'Extra Telur',
            'price' => 5000,
            'sort_order' => 1,
        ]);
        $item->modifierGroups()->attach($group->id, [
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
        ]);

        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $item->id,
            ])
            ->assertUnprocessable();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $item->id,
                'modifier_ids' => [$telur->id],
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/cart')
            ->assertOk()
            ->assertJsonPath('data.subtotal', 25000);

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => 'mod-1',
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        $line = $order->items()->first();

        $this->assertSame(25000, (int) $line->unit_price);
        $this->assertTrue(OrderItemModifier::query()->where('order_item_id', $line->id)->where('name_snapshot', 'Extra Telur')->exists());
        $this->assertStringContainsString('Extra Telur', $line->fresh(['modifiers'])->displayName());

        app(OrderPaymentService::class)->approve($order, \App\Models\User::factory()->create());
        $this->assertTrue($order->fresh()->isAccepted());
    }
}
