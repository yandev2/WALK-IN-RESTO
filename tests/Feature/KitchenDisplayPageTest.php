<?php

namespace Tests\Feature;

use App\Filament\Pages\KitchenDisplay;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\TableQrToken;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
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
            ->assertSee('Siap antar')
            ->assertSee('Meja')
            ->assertSee('Pesanan')
            ->assertSee('Cari nomor pesanan atau nama menu')
            ->assertSee('Antrian kosong')
            ->assertDontSee('Batch');
    }

    public function test_kitchen_page_renders_queued_items_after_cashier_confirm(): void
    {
        [$user, $restaurant, $world] = $this->kitchenUser();
        $this->paidGuestOrder($world, 'kds-page-order-1');

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(KitchenDisplay::class)
            ->assertOk()
            ->assertSee('Es Teh')
            ->assertSee('Mulai masak')
            ->assertDontSee('Antrian kosong');
    }

    public function test_kitchen_page_can_search_order_number_and_filter_by_table(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        [$user, $restaurant, $world] = $this->kitchenUser();

        $orderA = $this->paidGuestOrder($world, 'kds-page-order-a');

        $worldB = $world;
        $worldB['token'] = TableQrToken::make($world['otherTable']);
        $orderB = $this->paidGuestOrder($worldB, 'kds-page-order-b');

        $itemA = $orderA->items()->firstOrFail();
        $itemB = $orderB->items()->firstOrFail();

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $page = Livewire::test(KitchenDisplay::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$itemA, $itemB]);

        $page->searchTable((string) $orderA->number)
            ->assertCanSeeTableRecords([$itemA])
            ->assertCanNotSeeTableRecords([$itemB]);

        $page->searchTable(null)
            ->filterTable('table_id', $world['table']->id)
            ->assertCanSeeTableRecords([$itemA])
            ->assertCanNotSeeTableRecords([$itemB]);
    }

    /**
     * @return array{0: User, 1: Restaurant, 2: array{restaurant: Restaurant, outlet: Outlet, table: DiningTable, otherTable: DiningTable, item: MenuItem, token: string}}
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
}
