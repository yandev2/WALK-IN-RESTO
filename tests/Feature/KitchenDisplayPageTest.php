<?php

namespace Tests\Feature;

use App\Filament\Pages\KitchenDisplay;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderPaymentService;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class KitchenDisplayPageTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_kitchen_page_renders_filament_table_tabs(): void
    {
        [$user, $restaurant] = $this->kitchenUser();

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(KitchenDisplay::class)
            ->assertOk()
            ->assertSee('Batch')
            ->assertSee('Siap antar')
            ->assertSee('Timer dari kasir terima')
            ->assertSee('Antrian kosong');
    }

    public function test_kitchen_page_renders_queued_items_after_cashier_confirm(): void
    {
        [$user, $restaurant, $world] = $this->kitchenUser();
        $this->paidOrder($world);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(KitchenDisplay::class)
            ->assertOk()
            ->assertSee('Es Teh')
            ->assertSee('Mulai masak')
            ->assertDontSee('Antrian kosong');
    }

    /**
     * @return array{0: User, 1: \App\Models\Restaurant, 2: array{restaurant: \App\Models\Restaurant, outlet: \App\Models\Outlet, table: \App\Models\DiningTable, item: \App\Models\MenuItem, token: string}}
     */
    private function kitchenUser(): array
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        $user = User::factory()->create([
            'username' => 'dapur-test',
        ]);
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = Role::query()->create([
            'name' => 'dapur',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions(['kds.view', 'kds.update_status']);
        $user->assignRole($role);

        return [$user, $restaurant, $world];
    }

    /**
     * @param  array{restaurant: \App\Models\Restaurant, item: \App\Models\MenuItem, token: string}  $world
     */
    private function paidOrder(array $world): Order
    {
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
            ])
            ->assertCreated();

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => 'kds-page-order-1',
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh(['items']);
    }
}
