<?php

namespace Tests\Feature;

use App\Models\CashierShift;
use App\Models\CashierShiftMovement;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Services\CashierShiftService;
use App\Services\OrderPaymentService;
use App\Services\OrderVoidService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CashierShiftTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_cashier_can_open_shift_with_starting_cash(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $service = app(CashierShiftService::class);
        $shift = $service->openShift($user, $world['outlet'], 150000, 'Shift Pagi');

        $this->assertSame(CashierShift::STATUS_OPEN, $shift->status);
        $this->assertSame(150000, $shift->starting_cash);
        $this->assertSame(0, $shift->cash_sales);
        $this->assertSame(0, $shift->non_cash_sales);
        $this->assertSame(0, $shift->cash_in);
        $this->assertSame(0, $shift->cash_out);
        $this->assertNotNull($shift->opened_at);
        $this->assertNull($shift->closed_at);
        $this->assertSame('Shift Pagi', $shift->notes);
        $this->assertTrue($shift->isOpen());

        $current = $service->getCurrentOpenShift($user, $world['outlet']);
        $this->assertNotNull($current);
        $this->assertSame($shift->id, $current->id);
    }

    public function test_cashier_cannot_open_two_shifts_simultaneously_in_same_outlet(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $service = app(CashierShiftService::class);
        $service->openShift($user, $world['outlet'], 100000);

        $this->expectException(ValidationException::class);
        $service->openShift($user, $world['outlet'], 50000);
    }

    public function test_cashier_can_record_cash_in_and_cash_out_movements(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $service = app(CashierShiftService::class);
        $shift = $service->openShift($user, $world['outlet'], 100000);

        // Kas Masuk (tambah modal)
        $in = $service->recordCashMovement(
            shift: $shift,
            user: $user,
            type: CashierShiftMovement::TYPE_CASH_IN,
            amount: 50000,
            category: 'tambah_modal',
            notes: 'Dari brankas'
        );

        $this->assertSame(50000, $in->amount);
        $this->assertSame(50000, $shift->fresh()->cash_in);

        // Kas Keluar (beli es)
        $out = $service->recordCashMovement(
            shift: $shift,
            user: $user,
            type: CashierShiftMovement::TYPE_CASH_OUT,
            amount: 20000,
            category: 'operasional',
            notes: 'Beli es kristal'
        );

        $this->assertSame(20000, $out->amount);
        $this->assertSame(20000, $shift->fresh()->cash_out);

        $calc = $service->calculateExpectedCash($shift->fresh());
        // 100.000 + 0 sales + 50.000 in - 20.000 out = 130.000
        $this->assertSame(130000, $calc['expected_cash']);
    }

    public function test_cashier_order_payment_updates_shift_cash_and_non_cash_sales(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $shiftService = app(CashierShiftService::class);
        $shift = $shiftService->openShift($user, $world['outlet'], 100000);

        $cashierOrderService = app(CashierOrderService::class);

        // 1. Order Tunai
        $orderCash = $cashierOrderService->create(
            $user,
            $world['table'],
            '081234567890',
            'Tamu Cash',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            100000
        );

        $paymentCash = $orderCash->payments()->first();
        app(OrderPaymentService::class)->approve($orderCash, $user, cashReceived: 100000);

        $shift->refresh();
        $this->assertGreaterThan(0, $shift->cash_sales);
        $this->assertSame((int) $orderCash->grand_payable, $shift->cash_sales);
        $this->assertSame(0, $shift->non_cash_sales);

        // 2. Order QRIS
        $orderQris = $cashierOrderService->create(
            $user,
            $world['otherTable'],
            '081234567891',
            'Tamu QRIS',
            'qris',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]]
        );

        $paymentQris = $orderQris->payments()->first();
        app(OrderPaymentService::class)->approve($orderQris, $user);

        $shift->refresh();
        $this->assertSame((int) $orderCash->grand_payable, $shift->cash_sales);
        $this->assertSame((int) $orderQris->grand_payable, $shift->non_cash_sales);

        // Expected cash only factors in cash sales, not QRIS
        $calc = $shiftService->calculateExpectedCash($shift);
        $this->assertSame(100000 + (int) $orderCash->grand_payable, $calc['expected_cash']);
        $this->assertSame((int) $orderCash->grand_payable + (int) $orderQris->grand_payable, $calc['total_sales']);
    }

    public function test_cashier_can_close_shift_with_blind_cash_count(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $service = app(CashierShiftService::class);
        $shift = $service->openShift($user, $world['outlet'], 100000);

        $service->recordCashMovement($shift, $user, CashierShiftMovement::TYPE_CASH_IN, 25000, 'tambah_modal');
        // Expected: 125.000

        // Kasir hitung fisik pas 125.000
        $closed = $service->closeShift($shift, $user, 125000);

        $this->assertSame(CashierShift::STATUS_CLOSED, $closed->status);
        $this->assertNotNull($closed->closed_at);
        $this->assertSame(125000, $closed->expected_ending_cash);
        $this->assertSame(125000, $closed->actual_ending_cash);
        $this->assertSame(0, $closed->cash_difference);
        $this->assertSame($user->id, $closed->closed_by_user_id);
    }

    public function test_cash_difference_variance_calculated_correctly_when_short_or_over(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $service = app(CashierShiftService::class);

        // Skenario 1: Minus / Tekor
        $shift1 = $service->openShift($user, $world['outlet'], 200000);
        $closed1 = $service->closeShift($shift1, $user, 180000, 'Salah kembalian 20rb');

        $this->assertSame(200000, $closed1->expected_ending_cash);
        $this->assertSame(180000, $closed1->actual_ending_cash);
        $this->assertSame(-20000, $closed1->cash_difference);
        $this->assertSame('Salah kembalian 20rb', $closed1->difference_reason);

        // Skenario 2: Lebih / Surplus
        $shift2 = $service->openShift($user, $world['outlet'], 100000);
        $closed2 = $service->closeShift($shift2, $user, 115000, 'Tip pelanggan masuk laci');

        $this->assertSame(100000, $closed2->expected_ending_cash);
        $this->assertSame(115000, $closed2->actual_ending_cash);
        $this->assertSame(15000, $closed2->cash_difference);
        $this->assertSame('Tip pelanggan masuk laci', $closed2->difference_reason);
    }

    public function test_void_order_records_cash_out_adjustment_in_shift(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $shiftService = app(CashierShiftService::class);
        $shift = $shiftService->openShift($user, $world['outlet'], 100000);

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            'Tamu',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000
        );

        app(OrderPaymentService::class)->approve($order, $user, cashReceived: 50000);
        $shift->refresh();
        $cashSales = $shift->cash_sales;

        // Void order
        app(OrderVoidService::class)->voidOrder($order, $user, 'Salah input menu');

        $shift->refresh();
        $this->assertSame($cashSales, $shift->cash_out);

        $calc = $shiftService->calculateExpectedCash($shift);
        // Modal 100.000 + Penjualan 20.000 - Retur Void 20.000 = 100.000
        $this->assertSame(100000, $calc['expected_cash']);
    }

    public function test_shift_receipt_pdf_can_be_streamed(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id);

        $shift = app(CashierShiftService::class)->openShift($user, $world['outlet'], 100000);

        $response = $this->actingAs($user)->get(route('shifts.print', ['shift' => $shift->public_id]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_cashier_order_page_livewire_can_open_record_movement_and_close_shift(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create']);

        $this->actingAs($user);
        \Filament\Facades\Filament::setCurrentPanel('admin');
        \Filament\Facades\Filament::setTenant($world['restaurant']);

        $component = \Livewire\Livewire::test(\App\Filament\Pages\CreateCashierOrder::class);

        // 1. Open shift via Livewire
        $component->call('openShift', 100000, 'Shift Tes')
            ->assertNotified('Shift kasir berhasil dibuka');

        $this->assertNotNull($component->get('activeShift'));
        $this->assertSame(100000, $component->get('activeShift')['starting_cash']);

        // 2. Record cash movement via Livewire
        $component->call('recordCashMovement', 'cash_out', 15000, 'operasional', 'Beli lakban')
            ->assertNotified('Kas keluar berhasil dicatat');

        // Staff cashier: blind cash count enforced (expected_cash and breakdown are null)
        $this->assertNull($component->get('activeShift')['expected_cash']);
        $this->assertNull($component->get('activeShift')['cash_out']);

        // 3. Close shift via Livewire
        $component->call('closeShift', 85000, null, 'Tutup shift sore')
            ->assertNotified();

        $this->assertNull($component->get('activeShift'));
    }

    public function test_owner_can_see_expected_drawer_cash_balance_in_pos_shift(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $world = $this->createGuestRestaurant();
        $owner = $this->staffUser($world['restaurant'], ['order.create']);

        // Assign owner role to simulate restaurant owner
        \Spatie\Permission\Models\Role::query()->firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);
        $owner->assignRole('owner');

        $this->actingAs($owner);
        \Filament\Facades\Filament::setCurrentPanel('admin');
        \Filament\Facades\Filament::setTenant($world['restaurant']);

        $component = \Livewire\Livewire::test(\App\Filament\Pages\CreateCashierOrder::class);

        $component->call('openShift', 100000, 'Shift Owner');
        $component->call('recordCashMovement', 'cash_out', 15000, 'operasional', 'Beli lakban');

        // Owner/Admin bypass: can view drawer cash
        $this->assertSame(15000, $component->get('activeShift')['cash_out']);
        $this->assertSame(85000, $component->get('activeShift')['expected_cash']);
    }

    public function test_owner_can_view_cashier_shift_resource_table_and_detail(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.create', 'analytics.view']);

        $shift = app(CashierShiftService::class)->openShift($user, $world['outlet'], 100000, 'Shift Siang');

        $this->actingAs($user);
        \Filament\Facades\Filament::setCurrentPanel('admin');
        \Filament\Facades\Filament::setTenant($world['restaurant']);

        \Livewire\Livewire::test(\App\Filament\Resources\CashierShifts\Pages\ListCashierShifts::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$shift]);

        $viewTest = \Livewire\Livewire::test(\App\Filament\Resources\CashierShifts\Pages\ViewCashierShift::class, ['record' => $shift->getKey()])
            ->assertOk();

        /** @var \App\Filament\Resources\CashierShifts\Pages\ViewCashierShift $page */
        $page = $viewTest->instance();
        $this->assertSame("Detail Shift Kasir #{$shift->id}", $page->getTitle());
        $this->assertStringContainsString('Rekonsiliasi laci kas', (string) $page->getSubheading());
    }

    /**
     * @param  list<string>  $permissions
     */
    private function staffUser(\App\Models\Restaurant $restaurant, array $permissions): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = \Spatie\Permission\Models\Role::query()->create([
            'name' => 'kasir-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
