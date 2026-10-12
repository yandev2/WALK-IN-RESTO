<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Pages\CreateCashierOrder;
use App\Filament\Pages\KitchenDisplay;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\SubscriptionInvoice;
use App\Support\SubscriptionAccess;
use App\Support\TableQrToken;
use App\Support\TenantContext;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class OverdueSubscriptionLockEnforcementTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_qr_scan_shows_friendly_paused_message_when_restaurant_is_locked(): void
    {
        $restaurant = $this->makeRestaurant([
            'name' => 'Resto Sedap Rasa',
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Expired->value,
            'subscribed_until' => now()->subDay(),
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Pusat',
            'is_active' => true,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'Meja-01',
            'capacity' => 4,
            'qr_version' => 1,
            'qr_secret' => 'secret-test-token',
        ]);

        $token = TableQrToken::make($table);

        $response = $this->get(route('guest.scan', ['token' => $token]));

        // Status 200 dengan view yang ramah
        $response->assertOk();
        $response->assertViewIs('guest.operations-paused');
        $response->assertSee('Layanan Meja Sedang Diistirahatkan Sejenak');
        $response->assertSee('Resto Sedap Rasa');
        $response->assertSee('meja kasir');
        // Pastikan tidak ada kata tunggakan/belum bayar yang kasar untuk pelanggan
        $response->assertDontSee('tunggakan');
        $response->assertDontSee('belum bayar');
        $response->assertDontSee('invoice');
    }

    public function test_is_locked_detects_past_expiry_and_overdue_commission(): void
    {
        // 1. Kasus langganan habis (subscribed_until sudah lewat)
        $expiredResto = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active->value,
            'subscribed_until' => now()->subHour(),
        ]);

        $this->assertTrue(SubscriptionAccess::isLocked($expiredResto));

        // 2. Kasus ada tagihan komisi kasir overdue
        $activeResto = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Active->value,
            'subscribed_until' => now()->addDays(30),
        ]);

        $this->assertFalse(SubscriptionAccess::isLocked($activeResto));

        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-LOCK-01',
            'restaurant_id' => $activeResto->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 5000000,
            'commission_percentage' => 10.00,
            'amount' => 500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->subMonth()->format('Y-m'),
            'due_at' => now()->subDays(2),
        ]);

        $this->assertTrue(SubscriptionAccess::isLocked($activeResto));
    }

    public function test_cashier_order_server_methods_disallow_execution_when_locked(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Expired->value,
            'subscribed_until' => now()->subDay(),
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Pusat',
            'is_default' => true,
            'is_active' => true,
        ]);

        $owner = $this->makeOwner($restaurant);
        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(SubscriptionAccess::isLocked($restaurant));

        $page = new CreateCashierOrder();
        // Memanggil openShift harus mengembalikan null saat terkunci
        $result = $page->openShift(100000);
        $this->assertNull($result);

        // Memanggil recordCashMovement harus mengembalikan null saat terkunci
        $movementResult = $page->recordCashMovement('cash_in', 50000);
        $this->assertNull($movementResult);

        // Memanggil closeShift harus mengembalikan null saat terkunci
        $closeResult = $page->closeShift(100000);
        $this->assertNull($closeResult);
    }

    public function test_kds_run_action_blocked_when_locked(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Expired->value,
            'subscribed_until' => now()->subDay(),
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Pusat',
            'is_default' => true,
            'is_active' => true,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T1',
            'capacity' => 2,
        ]);

        $visit = \App\Models\Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'open',
            'join_pin' => '1234',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        $order = Order::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'public_id' => 'ord-kds-test-123',
            'number' => 1,
            'idempotency_key' => 'idemp-kds-test-01',
            'status' => 'paid',
            'source' => 'dine_in',
            'payment_method' => 'cash',
            'currency' => 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 25000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 25000,
            'grand_payable' => 25000,
            'send_receipt' => false,
        ]);

        $station = \App\Models\KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Dapur Utama',
            'slug' => 'dapur-utama',
            'is_active' => true,
        ]);

        $owner = $this->makeOwner($restaurant);
        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(SubscriptionAccess::isLocked($restaurant));

        $page = new KitchenDisplay();
        $this->assertFalse($page->canAdvance());
        $this->assertFalse($page->canMarkServed());
    }
}
