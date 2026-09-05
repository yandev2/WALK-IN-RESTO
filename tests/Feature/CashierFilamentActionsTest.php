<?php

namespace Tests\Feature;

use App\Filament\Pages\CreateCashierOrder;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Support\CashierOrderPreview;
use App\Support\CmsMedia;
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
            ->assertSee('Buat order kasir')
            ->assertSee('Opsional')
            ->assertSee('Ringkasan pembayaran')
            ->assertSee('Tambah item');
    }

    public function test_cashier_order_can_add_another_menu_line_after_selecting_item(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        $other = $this->extraMenuItem($world, 'Kentang Goreng', 15000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->fillForm([
                'table_id' => $world['table']->id,
                'payment_method' => 'cash',
                'lines' => [
                    [
                        'menu_item_id' => $world['item']->id,
                        'qty' => 1,
                    ],
                ],
            ]);

        $page->assertSee('Uang diterima')
            ->assertDontSee('raw: ""', false)
            ->callFormComponentAction('lines', 'add');

        $this->assertCount(2, $page->instance()->data['lines'] ?? []);

        $page->fillForm([
            'lines' => [
                [
                    'menu_item_id' => $world['item']->id,
                    'qty' => 1,
                ],
                [
                    'menu_item_id' => $other->id,
                    'qty' => 2,
                ],
            ],
        ])->call('create')->assertHasNoFormErrors();

        $this->assertDatabaseHas('order_items', [
            'menu_item_id' => $other->id,
            'qty' => 2,
        ]);
    }

    public function test_cashier_order_totals_update_when_repeater_line_is_deleted(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);
        $other = $this->extraMenuItem($world, 'Kentang Goreng', 15000);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $twoLines = [
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
            ['menu_item_id' => $other->id, 'qty' => 2],
        ];
        $oneLine = [
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ];

        $totalForTwo = CmsMedia::formatIdr(
            CashierOrderPreview::estimateFromLines($twoLines, $world['outlet'], 'cash')['grand_payable'],
        );
        $totalForOne = CmsMedia::formatIdr(
            CashierOrderPreview::estimateFromLines($oneLine, $world['outlet'], 'cash')['grand_payable'],
        );

        $page = Livewire::test(CreateCashierOrder::class)
            ->fillForm([
                'table_id' => $world['table']->id,
                'payment_method' => 'cash',
                'lines' => $twoLines,
            ])
            ->assertSee($totalForTwo)
            ->assertSee('2 baris');

        $itemKey = array_key_last($page->instance()->data['lines'] ?? []);

        $page->callFormComponentAction('lines', 'delete', [], ['item' => $itemKey]);

        $this->assertCount(1, $page->instance()->data['lines'] ?? []);
        $this->assertNotSame($totalForTwo, $totalForOne);

        $partialHtml = implode("\n", invade($page)->lastState->getEffects()['partials'] ?? []);

        $this->assertStringContainsString($totalForOne, $partialHtml);
        $this->assertStringContainsString('1 baris', $partialHtml);
    }

    public function test_cashier_order_can_be_created_without_whatsapp(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->fillForm([
                'table_id' => $world['table']->id,
                'customer_wa' => null,
                'customer_name' => 'Walk-in',
                'payment_method' => 'cash',
                'send_receipt' => false,
                'cash_received' => '20.000',
                'lines' => [
                    [
                        'menu_item_id' => $world['item']->id,
                        'qty' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

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

    public function test_cashier_order_defaults_to_form_ui_with_grid_toggle(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->assertOk()
            ->assertSet('cashierUi', 'form')
            ->assertActionVisible('toggleCashierUi')
            ->assertSee('Tambah item')
            ->assertDontSee('Semua menu');
    }

    public function test_cashier_order_can_switch_to_grid_and_merge_qty(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->callAction('toggleCashierUi')
            ->assertSet('cashierUi', 'pos')
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

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->callAction('toggleCashierUi')
            ->set('data.table_id', $world['table']->id)
            ->set('data.customer_name', 'Walk-in')
            ->set('data.payment_method', 'cash')
            ->set('data.send_receipt', false)
            ->set('data.cash_received', '20000')
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

    public function test_cashier_order_ui_roundtrip_keeps_selected_menu(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(CreateCashierOrder::class)
            ->fillForm([
                'table_id' => $world['table']->id,
                'payment_method' => 'cash',
                'lines' => [
                    [
                        'menu_item_id' => $world['item']->id,
                        'qty' => 1,
                    ],
                ],
            ])
            ->callAction('toggleCashierUi')
            ->assertSet('cashierUi', 'pos')
            ->assertSee('Es Teh')
            ->callAction('toggleCashierUi')
            ->assertSet('cashierUi', 'form')
            ->assertSee('Tambah item');

        $lines = array_values($page->instance()->data['lines'] ?? []);
        $this->assertSame($world['item']->id, (int) ($lines[0]['menu_item_id'] ?? 0));

        $page->callFormComponentAction('lines', 'add');
        $this->assertCount(2, $page->instance()->data['lines'] ?? []);
    }

    public function test_cashier_order_grid_ui_persists_in_session(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(CreateCashierOrder::class)
            ->callAction('toggleCashierUi')
            ->assertSet('cashierUi', 'pos');

        $this->assertSame('pos', session('cashier_order_ui'));

        Livewire::test(CreateCashierOrder::class)
            ->assertSet('cashierUi', 'pos')
            ->assertSee('Semua menu')
            ->assertDontSee('Tambah item');
    }

    public function test_cashier_order_in_simple_mode_stays_on_page_and_shows_receipt_modal(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->fillForm([
                'table_id' => $world['table']->id,
                'customer_name' => 'Budi Prasmanan',
                'customer_wa' => '081234567890',
                'payment_method' => 'cash',
                'lines' => [
                    [
                        'menu_item_id' => $world['item']->id,
                        'qty' => 1,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
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

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $test = Livewire::test(CreateCashierOrder::class)
            ->callAction('toggleCashierUi')
            ->assertSet('cashierUi', 'pos')
            ->call('setPosField', 'table_id', $world['table']->id)
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
