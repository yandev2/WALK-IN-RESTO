<?php

namespace Tests\Feature;

use App\Livewire\CashierOrderSoundAlert;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Visit;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CashierOrderSoundAlertTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_cashier_order_sound_alert_mounts_and_does_not_alert_for_existing_orders(): void
    {
        [$user, $restaurant, $world] = $this->cashierUser();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // Pre-existing order before cashier opens panel
        $this->createAwaitingOrder($world, 'pre-existing-1');

        Livewire::test(CashierOrderSoundAlert::class)
            ->assertOk()
            ->call('checkNewOrders')
            ->assertNotDispatched('cashier-order-sound');

        $this->assertSame(0, DB::table('notifications')->count());
    }

    public function test_cashier_order_sound_alert_dispatches_sound_and_transient_notification_on_new_order(): void
    {
        [$user, $restaurant, $world] = $this->cashierUser();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $component = Livewire::test(CashierOrderSoundAlert::class)
            ->assertOk();

        // New order arrives while cashier is on the page
        $newOrder = $this->createAwaitingOrder($world, 'incoming-order-1');

        $component->call('checkNewOrders')
            ->assertDispatched('cashier-order-sound', count: 1)
            ->assertNotified('Pesanan Baru Masuk!');

        // Polling again without new orders should not trigger another sound
        $component->call('checkNewOrders')
            ->assertNotDispatched('cashier-order-sound');

        // Verify transient: NO database notification records created
        $this->assertSame(0, DB::table('notifications')->count());
    }

    public function test_cashier_order_sound_alert_consolidates_batch_orders_during_rush_hours(): void
    {
        [$user, $restaurant, $world] = $this->cashierUser();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $component = Livewire::test(CashierOrderSoundAlert::class)
            ->assertOk();

        // Simulate 4 concurrent orders entering at the exact same polling window
        for ($i = 1; $i <= 4; $i++) {
            $this->createAwaitingOrder($world, "rush-order-{$i}");
        }

        $component->call('checkNewOrders')
            ->assertDispatched('cashier-order-sound', count: 4)
            ->assertNotified('4 Pesanan Baru Masuk!');

        $this->assertSame(0, DB::table('notifications')->count());
    }

    public function test_cashier_order_sound_alert_does_not_alert_for_orders_created_directly_by_cashier(): void
    {
        [$user, $restaurant, $world] = $this->cashierUser();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $component = Livewire::test(CashierOrderSoundAlert::class)
            ->assertOk();

        // Order created directly by cashier at counter
        $this->createAwaitingOrder($world, 'cashier-direct-1', source: 'cashier');

        $component->call('checkNewOrders')
            ->assertNotDispatched('cashier-order-sound');
    }

    public function test_cashier_order_sound_alert_alerts_for_guest_orders_regardless_of_simple_mode(): void
    {
        [$user, $restaurant, $world] = $this->cashierUser();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // Test in simple mode
        $world['outlet']->update(['simple_mode' => true]);

        $component = Livewire::test(CashierOrderSoundAlert::class)
            ->assertOk();

        $this->createAwaitingOrder($world, 'guest-simple-1', source: 'guest');

        $component->call('checkNewOrders')
            ->assertDispatched('cashier-order-sound', count: 1)
            ->assertNotified('Pesanan Baru Masuk!');
    }

    private function createAwaitingOrder(array $world, string $idempotencyKey, string $source = 'guest_qr'): Order
    {
        $visit = Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'active',
            'join_pin' => '1234',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        return Order::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $visit->id,
            'number' => rand(100, 999),
            'status' => Order::STATUS_AWAITING_CASHIER,
            'source' => $source,
            'payment_method' => 'cash',
            'idempotency_key' => $idempotencyKey,
            'currency' => 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 10000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 10000,
            'grand_payable' => 10000,
        ]);
    }

    /**
     * @return array{0: User, 1: Restaurant, 2: array{restaurant: Restaurant, outlet: Outlet, table: DiningTable, otherTable: DiningTable, item: mixed, token: string}}
     */
    private function cashierUser(): array
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        $user = User::factory()->create([
            'username' => 'kasir-test',
        ]);
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        return [$user, $restaurant, $world];
    }
}
