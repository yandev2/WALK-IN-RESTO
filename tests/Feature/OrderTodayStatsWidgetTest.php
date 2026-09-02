<?php

namespace Tests\Feature;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Widgets\OrderTodayStatsWidget;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderTodayStatsWidgetTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    /**
     * @param  list<string>  $permissions
     */
    private function staffUser(Restaurant $restaurant, array $permissions): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = Role::query()->create([
            'name' => 'test-role-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }

    public function test_order_today_stats_widget_computes_correct_counts(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $user = $this->staffUser($restaurant, ['order.verify_payment']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $cashierService = app(\App\Services\CashierOrderService::class);
        $createTestOrder = function (string $status, ?\Illuminate\Support\Carbon $createdAt = null) use ($cashierService, $user, $world, $restaurant) {
            $table = \App\Models\DiningTable::query()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $world['outlet']->id,
                'code' => 'T-'.uniqid(),
                'capacity' => 4,
                'qr_version' => 1,
                'qr_secret' => \Illuminate\Support\Str::random(64),
            ]);

            $order = $cashierService->create(
                $user,
                $table,
                '081234567890',
                'Walk-in',
                'cash',
                false,
                [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            );
            $order->status = $status;
            if ($createdAt) {
                $order->created_at = $createdAt;
            }
            $order->save();

            return $order;
        };

        // Create orders with various statuses for today
        $createTestOrder('awaiting_cashier');
        $createTestOrder('awaiting_cashier');
        $createTestOrder('paid');
        $createTestOrder('in_production');
        $createTestOrder('completed');
        $createTestOrder('rejected');
        $createTestOrder('cancelled');
        $createTestOrder('pending_payment');
        $createTestOrder('voided');

        // Yesterday order (should NOT be counted)
        $createTestOrder('paid', now()->subDay());

        $widget = new OrderTodayStatsWidget;
        $cards = collect($widget->getCards())->keyBy('status');

        $this->assertSame('2', $cards['awaiting_cashier']['value']);
        $this->assertSame('1', $cards['paid']['value']);
        $this->assertSame('1', $cards['in_production']['value']);
        $this->assertSame('1', $cards['completed']['value']);
        $this->assertSame('1', $cards['rejected']['value']);
        $this->assertSame('1', $cards['cancelled']['value']);
        $this->assertSame('1', $cards['pending_payment']['value']);
        $this->assertSame('1', $cards['voided']['value']);

        // Test Livewire component rendering
        Livewire::test(OrderTodayStatsWidget::class)
            ->assertOk()
            ->assertSee('Menunggu Kasir')
            ->assertSee('Lunas (Antrian)')
            ->assertSee('Sedang Dimasak')
            ->assertSee('Selesai')
            ->assertSee('Ditolak')
            ->assertSee('Batal')
            ->assertSee('Pending')
            ->assertSee('Void');
    }

    public function test_list_orders_page_includes_header_widget(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $user = $this->staffUser($restaurant, ['order.verify_payment']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(ListOrders::class)
            ->assertOk()
            ->assertSee('Menunggu Kasir')
            ->assertSee('Lunas (Antrian)')
            ->assertSee('Sedang Dimasak')
            ->assertSee('Selesai')
            ->assertSee('Ditolak')
            ->assertSee('Batal')
            ->assertSee('Pending')
            ->assertSee('Void');
    }

    public function test_list_orders_page_can_filter_by_date_range_and_has_date_grouping(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $user = $this->staffUser($restaurant, ['order.verify_payment']);

        $cashierService = app(\App\Services\CashierOrderService::class);

        // Order 1: today
        $orderToday = $cashierService->create(
            $user,
            $world['table'],
            '081234567890',
            'Walk-in',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(ListOrders::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$orderToday])
            ->filterTable('created_at', [
                'created_from' => now()->subDays(5)->format('Y-m-d'),
                'created_until' => now()->addDay()->format('Y-m-d'),
            ])
            ->assertCanSeeTableRecords([$orderToday])
            ->filterTable('created_at', [
                'created_from' => now()->subDays(5)->format('Y-m-d'),
                'created_until' => now()->subDays(2)->format('Y-m-d'),
            ])
            ->assertCanNotSeeTableRecords([$orderToday]);
    }
}
