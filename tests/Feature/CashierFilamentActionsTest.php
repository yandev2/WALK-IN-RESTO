<?php

namespace Tests\Feature;

use App\Filament\Pages\CreateCashierOrder;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\MenuVariant;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Services\CashierShiftService;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
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
            ->assertSee('Buat order kasir')
            ->assertSee('Semua menu')
            ->assertSee('Ringkasan pesanan')
            ->assertDontSee('Tambah item');
    }

    public function test_cashier_order_can_add_multiple_menu_items(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        $other = $this->extraMenuItem($world, 'Kentang Goreng', 15000);

        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosPaymentMethod', 'cash')
            ->call('addPosItem', $world['item']->id)
            ->call('addPosItem', $other->id)
            ->call('addPosItem', $other->id);

        $this->assertCount(2, $page->instance()->data['lines'] ?? []);

        $page->call('create')->assertHasNoErrors();

        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $other->id,
            'qty' => 2,
        ]);
        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $world['item']->id,
            'qty' => 1,
        ]);
    }

    public function test_cashier_order_totals_update_when_pos_line_is_deleted(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        $other = $this->extraMenuItem($world, 'Kentang Goreng', 15000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->call('addPosItem', $world['item']->id)
            ->call('addPosItem', $other->id);

        $this->assertCount(2, $page->instance()->data['lines'] ?? []);

        $page->call('removePosLine', 1);

        $lines = array_values($page->instance()->data['lines'] ?? []);
        $this->assertCount(1, $lines);
        $this->assertSame($world['item']->id, (int) $lines[0]['menu_item_id']);
    }

    public function test_cashier_order_can_be_created_without_whatsapp(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_wa', null)
            ->call('setPosField', 'customer_name', 'Walk-in')
            ->call('setPosField', 'send_receipt', false)
            ->call('setPosField', 'cash_received', '20.000')
            ->call('addPosItem', $world['item']->id)
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('visits', [
            'table_id' => $world['table']->id,
            'customer_name' => 'Walk-in',
            'customer_wa' => null,
        ]);
        $this->assertDatabaseHas('payments', [
            'method' => 'cash',
            'cash_received' => 20000,
        ]);
    }

    public function test_approve_cash_order_accepts_tender_amount(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create', 'order.verify_payment']);
        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            null,
            'Walk-in',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            20000,
        );

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ViewOrder::class, ['record' => $order->getKey()])
            ->assertOk()
            ->callAction('approve');

        $payment = $order->fresh()->payments()->first();
        $this->assertTrue($order->fresh()->isAccepted());
        $this->assertSame(20000, (int) $payment->cash_received);
        $this->assertSame(20000 - (int) $order->grand_payable, (int) $payment->change_amount);
    }

    public function test_cashier_order_renders_grid_without_toggle_action(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->assertOk()
            ->assertDontSee('Tampilan form')
            ->assertDontSee('Tampilan grid')
            ->assertActionVisible('openShift')
            ->assertActionHidden('closeShift')
            ->assertSee('Shift Belum Dibuka')
            ->assertSee('Buat order kasir')
            ->assertSee('Semua menu')
            ->assertSee('Ringkasan pesanan')
            ->assertDontSee('Tambah item');

        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        Livewire::test(CreateCashierOrder::class)
            ->assertOk()
            ->assertActionHidden('openShift')
            ->assertActionVisible('closeShift');
    }

    public function test_cashier_order_merges_qty_when_item_clicked_multiple_times(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->assertSee('Semua menu')
            ->assertDontSee('Tambah item')
            ->call('addPosItem', $world['item']->id)
            ->call('addPosItem', $world['item']->id);

        $lines = array_values($page->instance()->data['lines'] ?? []);

        $this->assertCount(1, $lines);
        $this->assertSame($world['item']->id, (int) $lines[0]['menu_item_id']);
        $this->assertSame(2, (int) $lines[0]['qty']);
    }

    public function test_cashier_order_grid_can_create_without_whatsapp(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Walk-in')
            ->call('setPosPaymentMethod', 'cash')
            ->call('setPosField', 'send_receipt', false)
            ->call('setPosField', 'cash_received', '20000')
            ->call('addPosItem', $world['item']->id)
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('visits', [
            'table_id' => $world['table']->id,
            'customer_name' => 'Walk-in',
            'customer_wa' => null,
        ]);
        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $world['item']->id,
            'qty' => 1,
        ]);
    }

    public function test_cashier_order_grid_change_qty_and_removes_when_zero(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->call('addPosItem', $world['item']->id)
            ->call('changePosQty', 0, 1);

        $this->assertSame(2, (int) $page->instance()->data['lines'][0]['qty']);

        $page->call('changePosQty', 0, -1);
        $this->assertSame(1, (int) $page->instance()->data['lines'][0]['qty']);

        $page->call('changePosQty', 0, -1);
        $this->assertEmpty($page->instance()->data['lines']);
    }

    public function test_cashier_order_grid_resets_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Budi')
            ->call('addPosItem', $world['item']->id)
            ->call('resetCashierForm')
            ->assertSet('data.table_id', null)
            ->assertSet('data.customer_name', null)
            ->assertSet('data.lines', [])
            ->assertDispatched('cashier-reset-form');
    }

    public function test_cashier_order_in_simple_mode_stays_on_page_and_shows_receipt_modal(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Budi Prasmanan')
            ->call('setPosField', 'customer_wa', '081234567890')
            ->call('setPosPaymentMethod', 'cash')
            ->call('addPosItem', $world['item']->id)
            ->call('create')
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $completedOrder = $test->get('simpleModeCompletedOrder');
        $this->assertNotNull($completedOrder);
        $this->assertSame('Tunai', $completedOrder['payment_method']);
        $this->assertSame($world['table']->code, $completedOrder['table_name']);
        $this->assertSame('Budi Prasmanan', $completedOrder['customer_name']);
        $this->assertStringContainsString('/receipts/', $completedOrder['print_url']);
        $this->assertStringContainsString('/print?auto=1', $completedOrder['print_url']);

        $test->assertSee('Pesanan Selesai')
            ->assertSee('Cetak Struk')
            ->assertSee('Tutup / Order Baru')
            ->assertDispatched('cashier-reset-form')
            ->assertSet('data.table_id', null)
            ->assertSet('data.customer_name', null)
            ->assertSet('data.customer_wa', null);

        $test->call('closeSimpleModeModal')
            ->assertSet('simpleModeCompletedOrder', null)
            ->assertSet('data.table_id', null)
            ->assertSet('data.customer_name', null)
            ->assertSet('data.customer_wa', null)
            ->assertDispatched('cashier-reset-form');
    }

    public function test_cashier_order_in_simple_mode_resets_form_in_pos_ui(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Budi POS')
            ->call('setPosField', 'customer_wa', '081299998888')
            ->call('setPosField', 'cash_received', '50000')
            ->call('addPosItem', $world['item']->id)
            ->call('create')
            ->assertHasNoErrors()
            ->assertNoRedirect();

        $completedOrder = $test->get('simpleModeCompletedOrder');
        $this->assertNotNull($completedOrder);
        $this->assertSame($world['table']->code, $completedOrder['table_name']);
        $this->assertSame('Budi POS', $completedOrder['customer_name']);

        $test->assertDispatched('cashier-reset-form')
            ->assertSet('data.table_id', null)
            ->assertSet('data.customer_name', null)
            ->assertSet('data.customer_wa', null)
            ->assertSet('data.lines', []);

        $test->call('closeSimpleModeModal')
            ->assertSet('simpleModeCompletedOrder', null)
            ->assertSet('data.table_id', null)
            ->assertSet('data.customer_name', null)
            ->assertDispatched('cashier-reset-form');
    }

    public function test_cashier_order_with_variant_in_pos_mode(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);
        $item = $world['item'];

        $variant = MenuVariant::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'name' => 'Jumbo',
            'price_delta' => 3000,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Tamu Jumbo')
            ->call('commitPosEditor', 'new', $item->id, null, $variant->id, [], null)
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $item->id,
            'variant_name_snapshot' => 'Jumbo',
            'qty' => 1,
        ]);
    }

    public function test_cashier_order_with_variant_and_increased_qty(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);
        $item = $world['item'];

        $variant = MenuVariant::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'name' => 'Large Form',
            'price_delta' => 4000,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'table_id', (string) $world['table']->id)
            ->call('setPosField', 'customer_name', 'Tamu Large')
            ->call('commitPosEditor', 'new', $item->id, null, $variant->id, [], null)
            ->call('changePosQty', 0, 1)
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $item->id,
            'variant_name_snapshot' => 'Large Form',
            'qty' => 2,
        ]);
    }

    public function test_cashier_pos_send_receipt_auto_checks_when_auto_print_receipt_is_active(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['auto_print_receipt' => true]);
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'customer_wa', '081234567890')
            ->assertSet('data.send_receipt', true);
    }

    public function test_cashier_pos_send_receipt_does_not_auto_check_when_auto_print_receipt_is_inactive(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['auto_print_receipt' => false]);
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $user = $this->staffUser($world['restaurant'], ['order.create']);
        app(CashierShiftService::class)->openShift($user, $world['outlet'], 50000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->call('setPosField', 'customer_wa', '081234567890')
            ->assertSet('data.send_receipt', false);

        // Cashier can still check it manually if requested
        $test->call('setPosField', 'send_receipt', true)
            ->assertSet('data.send_receipt', true);
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
