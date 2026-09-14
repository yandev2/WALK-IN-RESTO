<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Filament\Pages\CommissionReconciliation;
use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Visit;
use App\Services\CashierCommissionBillingService;
use App\Services\CommissionReconciliationService;
use App\Services\Export\CommissionReconciliationExport;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SubscriptionPlanSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class CommissionReconciliationTest extends TestCase
{
    use CreatesGuestRestaurant;
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
            SubscriptionPlanSeeder::class,
        ]);
    }

    public function test_summary_and_order_details_match_cashier_commission_billing_service_math(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->subMonths(2), // Trial already expired
        ]);

        $outlet = $world['outlet'];
        $table = $world['table'];
        $visit = $this->createTestVisit($restaurant, $outlet, $table);

        // Order 1: Cash, Subtotal 100,000, Discount 10,000 -> Net 90,000 -> Commission 9,000
        $order1 = $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-001',
            'status' => Order::STATUS_COMPLETED,
            'payment_method' => 'cash',
            'subtotal' => 100000,
            'discount_amount' => 10000,
            'grand_before' => 90000,
            'grand_payable' => 90000,
            'paid_at' => now()->startOfMonth()->addDays(2),
        ]);
        OrderItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order1->id,
            'station_id' => $world['item']->station_id,
            'name_snapshot' => 'Nasi Goreng Special',
            'unit_price' => 100000,
            'qty' => 1,
            'kds_status' => 'served',
        ]);

        // Order 2: QRIS, Subtotal 50,000, Discount 0 -> Net 50,000 -> Commission 5,000
        $order2 = $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-002',
            'status' => Order::STATUS_PAID,
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'discount_amount' => 0,
            'grand_before' => 50000,
            'grand_payable' => 50000,
            'paid_at' => now()->startOfMonth()->addDays(5),
        ]);
        OrderItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order2->id,
            'station_id' => $world['item']->station_id,
            'name_snapshot' => 'Ayam Bakar Madu',
            'unit_price' => 25000,
            'qty' => 2,
            'kds_status' => 'ready',
        ]);

        $service = app(CommissionReconciliationService::class);
        $billingService = app(CashierCommissionBillingService::class);

        $from = now()->startOfMonth();
        $to = now()->endOfMonth();

        $summary = $service->summaryForPeriod($restaurant, $from, $to);
        $billingNetOmzet = $billingService->calculateMonthNetOmzet($restaurant, now());

        // Mathematical parity: Net Sales in Reconciliation MUST 100% match Billing Service Net Omzet
        $this->assertSame(140000, $summary['net_sales']);
        $this->assertSame($billingNetOmzet, $summary['net_sales']);

        // Gross, Discount, and Net Breakdown
        $this->assertSame(150000, $summary['gross_sales']);
        $this->assertSame(10000, $summary['discount_amount']);
        $this->assertSame(0, $summary['void_cut_amount']);

        // Commission math: 10% of 140,000 = 14,000
        $this->assertSame(14000, $summary['commission_amount']);
        $this->assertSame(126000, $summary['net_payout']);

        // Cash vs QRIS collection parity
        $this->assertSame(90000, $summary['cash_collected']);
        $this->assertSame(50000, $summary['qris_collected']);

        // Per-order details check
        $detail1 = $service->orderCommissionDetail($order1, $restaurant);
        $this->assertSame(90000, $detail1['net_sales']);
        $this->assertSame(9000, $detail1['commission_amount']);
        $this->assertSame(81000, $detail1['net_resto']);
        $this->assertFalse($detail1['is_exempt']);

        $detail2 = $service->orderCommissionDetail($order2, $restaurant);
        $this->assertSame(50000, $detail2['net_sales']);
        $this->assertSame(5000, $detail2['commission_amount']);
        $this->assertSame(45000, $detail2['net_resto']);
        $this->assertFalse($detail2['is_exempt']);
    }

    public function test_voided_cut_orders_are_exempt_from_commission_with_clear_reason(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->subMonths(1),
        ]);

        $outlet = $world['outlet'];
        $table = $world['table'];
        $visit = $this->createTestVisit($restaurant, $outlet, $table);

        // Voided order: Customer ordered 80,000 but cancelled before cooking (cut policy)
        $order = $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-VOID',
            'status' => Order::STATUS_VOIDED,
            'payment_method' => 'cash',
            'subtotal' => 80000,
            'discount_amount' => 0,
            'grand_before' => 80000,
            'grand_payable' => 0,
            'paid_at' => now()->subDay(),
            'voided_at' => now()->subDay(),
        ]);

        OrderItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'station_id' => $world['item']->station_id,
            'name_snapshot' => 'Steak Daging Sapi',
            'unit_price' => 80000,
            'qty' => 1,
            'kds_status' => 'voided',
            'void_omzet_policy' => 'cut',
        ]);

        $service = app(CommissionReconciliationService::class);
        $detail = $service->orderCommissionDetail($order, $restaurant);

        // Verification: Void cut means 0 Net Sales and 0 Commission
        $this->assertSame(0, $detail['net_sales']);
        $this->assertSame(80000, $detail['void_cut']);
        $this->assertSame(0, $detail['commission_amount']);
        $this->assertSame(0, $detail['net_resto']);
        $this->assertTrue($detail['is_exempt']);
        $this->assertStringContainsString('Void', (string) $detail['exempt_reason']);

        // Summary for period must reflect 0 net sales and 0 commission
        $summary = $service->summaryForPeriod($restaurant, now()->startOfMonth(), now()->endOfMonth());
        $this->assertSame(80000, $summary['void_cut_amount']);
        $this->assertSame(0, $summary['net_sales']);
        $this->assertSame(0, $summary['commission_amount']);
    }

    public function test_orders_during_trial_are_exempt_from_commission(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        // Restaurant is currently in 30-day active trial
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->addDays(15),
        ]);

        $outlet = $world['outlet'];
        $table = $world['table'];
        $visit = $this->createTestVisit($restaurant, $outlet, $table);

        $order = $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-TRIAL',
            'status' => Order::STATUS_COMPLETED,
            'payment_method' => 'cash',
            'subtotal' => 200000,
            'discount_amount' => 0,
            'grand_before' => 200000,
            'grand_payable' => 200000,
            'paid_at' => now()->subDay(),
        ]);
        OrderItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'station_id' => $world['item']->station_id,
            'name_snapshot' => 'Paket Family',
            'unit_price' => 200000,
            'qty' => 1,
            'kds_status' => 'served',
        ]);

        $service = app(CommissionReconciliationService::class);
        $detail = $service->orderCommissionDetail($order, $restaurant);

        // During trial: Net sales is 200k, but Commission is 0, Net Resto is 100% (200k)
        $this->assertSame(200000, $detail['net_sales']);
        $this->assertSame(0, $detail['commission_amount']);
        $this->assertSame(200000, $detail['net_resto']);
        $this->assertTrue($detail['is_exempt']);
        $this->assertStringContainsString('Uji Coba', (string) $detail['exempt_reason']);

        $summary = $service->summaryForPeriod($restaurant, now()->startOfMonth(), now()->endOfMonth());
        $this->assertSame(200000, $summary['net_sales']);
        $this->assertSame(0, $summary['commission_amount']);
        $this->assertSame(200000, $summary['net_payout']);
    }

    public function test_owner_can_view_commission_reconciliation_page_with_kpi_and_table(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->subMonths(1),
        ]);

        $outlet = $world['outlet'];
        $table = $world['table'];
        $visit = $this->createTestVisit($restaurant, $outlet, $table);

        $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-PAGE-1',
            'status' => Order::STATUS_COMPLETED,
            'payment_method' => 'qris',
            'subtotal' => 100000,
            'discount_amount' => 10000,
            'grand_before' => 90000,
            'grand_payable' => 90000,
            'paid_at' => now(),
        ]);

        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(CommissionReconciliation::class)
            ->assertOk()
            ->assertSee('Rekonsiliasi Penjualan & Komisi')
            ->assertSee('Penjualan Kotor')
            ->assertSee('Penjualan Bersih')
            ->assertSee('Komisi Platform')
            ->assertSee('Hak Bersih Restoran')
            ->assertSee('Realisasi Kas Masuk')
            ->assertSee('#ORD-PAGE-1');
    }

    public function test_owner_can_export_commission_reconciliation_excel_and_csv(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->subMonths(1),
        ]);

        $outlet = $world['outlet'];
        $table = $world['table'];
        $visit = $this->createTestVisit($restaurant, $outlet, $table);

        $this->createTestOrder([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'source' => 'cashier',
            'number' => 'ORD-EXP-1',
            'status' => Order::STATUS_COMPLETED,
            'payment_method' => 'cash',
            'subtotal' => 75000,
            'discount_amount' => 0,
            'grand_before' => 75000,
            'grand_payable' => 75000,
            'paid_at' => now(),
        ]);

        $export = app(CommissionReconciliationExport::class);
        $from = now()->startOfMonth();
        $to = now()->endOfMonth();

        $payload = $export->buildPayload($restaurant, $from, $to);
        $this->assertCount(1, $payload['rows']);
        $this->assertSame('ORD-EXP-1', $payload['rows'][0]['number']);
        $this->assertSame(75000, $payload['rows'][0]['net_sales']);
        $this->assertSame(7500, $payload['rows'][0]['commission_amount']);
        $this->assertSame(67500, $payload['rows'][0]['net_resto']);

        $excelResponse = $export->downloadExcel($restaurant, $from, $to);
        $this->assertSame(200, $excelResponse->getStatusCode());
        $this->assertStringContainsString('Rekonsiliasi-Penjualan-Komisi', (string) $excelResponse->headers->get('content-disposition'));

        $csvResponse = $export->downloadCsv($restaurant, $from, $to);
        $this->assertSame(200, $csvResponse->getStatusCode());
        $this->assertStringContainsString('.csv', (string) $csvResponse->headers->get('content-disposition'));
    }

    public function test_subscription_status_page_has_reconciliation_link_on_commission_invoice(): void
    {
        Carbon::setTestNow('2026-09-14 12:00:00');

        $world = $this->createGuestRestaurant();
        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $restaurant->update([
            'plan_code' => PlanCode::ManagementKds->value,
            'commission_percentage' => 10.00,
            'trial_ends_at' => now()->subMonths(1),
        ]);

        // Create commission invoice
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-TEST',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'status' => InvoiceStatus::Sent->value,
            'amount' => 150000,
            'total_omzet' => 1500000,
            'period_month' => '2026-09',
            'due_at' => now()->endOfMonth(),
        ]);

        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(SubscriptionStatusPage::class)
            ->assertOk()
            ->assertSee('INV-COMM-TEST')
            ->assertSee('Rincian transaksi');
    }

    private function createTestVisit(Restaurant $restaurant, Outlet $outlet, DiningTable $table): Visit
    {
        return Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'active',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1234',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->addHours(2),
        ]);
    }

    private function createTestOrder(array $attributes): Order
    {
        return Order::query()->create(array_merge([
            'source' => 'cashier',
            'idempotency_key' => (string) Str::uuid(),
            'currency' => 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'service_amount' => 0,
            'pb1_amount' => 0,
            'send_receipt' => false,
        ], $attributes));
    }
}
