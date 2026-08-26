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
