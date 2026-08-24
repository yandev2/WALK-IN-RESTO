<?php

namespace Tests\Feature;

use App\Filament\Pages\CreateCashierOrder;
use App\Filament\Resources\Orders\Pages\ViewOrder;
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

class CashierFilamentActionsTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_view_order_void_action_voids_paid_order(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'void-ui-1');
        $user = $this->staffUser($world['restaurant'], ['order.void', 'order.verify_payment']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ViewOrder::class, ['record' => $order->getKey()])
            ->assertOk()
            ->callAction('voidOrder', ['reason' => 'salah pesan']);

        $this->assertSame('voided', $order->fresh()->status);
        $this->assertSame('voided', $order->items()->first()->kds_status);
    }

    public function test_print_receipt_action_is_visible_on_paid_order(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-ui-1');
        $user = $this->staffUser($world['restaurant'], ['order.verify_payment']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ViewOrder::class, ['record' => $order->getKey()])
            ->assertOk()
            ->assertActionVisible('printReceipt')
            ->assertActionVisible('downloadReceipt');
    }

    public function test_cashier_order_page_renders(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->assertOk()
            ->assertSee('Buat order kasir');
    }

    /**
     * @param  list<string>  $permissions
     */
    private function staffUser(Restaurant $restaurant, array $permissions): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = Role::query()->create([
            'name' => 'kasir-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
