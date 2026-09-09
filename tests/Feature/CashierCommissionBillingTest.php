<?php

namespace Tests\Feature;

use App\Console\Commands\FinalizeCashierCommissionCommand;
use App\Console\Commands\SendCashierCommissionReminderCommand;
use App\Enums\BillingType;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Filament\Pages\SubscriptionStatus;
use App\Filament\Resources\CmsBanners\CmsBannerResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\Visit;
use App\Services\CashierCommissionBillingService;
use App\Filament\Widgets\AnalyticsKpiWidget;
use App\Filament\Widgets\PendingPaymentsWidget;
use App\Filament\Widgets\RestaurantReadinessWidget;
use App\Filament\Widgets\WelcomeBannerWidget;
use App\Services\SubscriptionInvoiceService;
use App\Services\SubscriptionPlanSync;
use Livewire\Livewire;
use Illuminate\Validation\ValidationException;
use App\Support\SubscriptionGate;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class CashierCommissionBillingTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        // Ensure PlatformSetting exists with 10% cashier commission
        PlatformSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'trial_days' => 30,
                'cashier_commission_percentage' => 10.00,
            ]
        );

        // Ensure plans exist
        SubscriptionPlan::query()->updateOrCreate(
            ['code' => PlanCode::ManagementKds->value],
            [
                'name' => 'Layanan Kasir & KDS',
                'price_monthly' => 0,
                'billing_type' => BillingType::Commission->value,
                'commission_percentage' => 10.00,
                'features' => [
                    'cms' => true,
                    'menu' => true,
                    'operations' => true,
                    'analytics' => true,
                    'settings' => 'full',
                ],
                'is_active' => true,
            ]
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => PlanCode::LandingOnly->value],
            [
                'name' => 'Landing Page Saja',
                'price_monthly' => 99000,
                'billing_type' => BillingType::FixedMonthly->value,
                'features' => [
                    'cms' => true,
                    'menu' => false,
                    'operations' => false,
                    'analytics' => false,
                    'settings' => 'limited',
                ],
                'is_active' => true,
            ]
        );
    }

    public function test_cashier_order_paid_hook_updates_commission_invoice_in_realtime(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subDay(), // trial ended
            'commission_percentage' => null, // use default 10%
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Outlet Utama',
        ]);

        $order1 = $this->createPaidOrder($restaurant, $outlet, 1000000);

        $billingService = app(CashierCommissionBillingService::class);
        $billingService->recordOrderPaidHook($order1);

        $currentMonth = now()->format('Y-m');
        $invoice = SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('invoice_type', InvoiceType::CashierCommission->value)
            ->where('period_month', $currentMonth)
            ->first();

        $this->assertNotNull($invoice);
        $this->assertSame(1000000, $invoice->total_omzet);
        $this->assertEquals(10.00, $invoice->commission_percentage);
        $this->assertSame(100000, $invoice->amount);
        $this->assertSame(InvoiceStatus::Sent, $invoice->status);

        // Add second order of Rp 500.000
        $order2 = $this->createPaidOrder($restaurant, $outlet, 500000);
        $billingService->recordOrderPaidHook($order2);

        $invoice->refresh();
        $this->assertSame(1500000, $invoice->total_omzet);
        $this->assertSame(150000, $invoice->amount);
    }

    public function test_orders_during_trial_are_exempt_from_commission(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->addDays(20), // trial active
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Outlet Utama',
        ]);

        $order = $this->createPaidOrder($restaurant, $outlet, 2000000);

        $billingService = app(CashierCommissionBillingService::class);
        $billingService->recordOrderPaidHook($order);

        $currentMonth = now()->format('Y-m');
        $invoice = SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('invoice_type', InvoiceType::CashierCommission->value)
            ->where('period_month', $currentMonth)
            ->first();

        $this->assertNull($invoice);
    }

    public function test_custom_commission_override_is_used_when_configured(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subDay(),
            'commission_percentage' => 7.50, // custom 7.5%
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Outlet Utama',
        ]);

        $order = $this->createPaidOrder($restaurant, $outlet, 2000000);

        $billingService = app(CashierCommissionBillingService::class);
        $billingService->recordOrderPaidHook($order);

        $currentMonth = now()->format('Y-m');
        $invoice = SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('invoice_type', InvoiceType::CashierCommission->value)
            ->where('period_month', $currentMonth)
            ->first();

        $this->assertNotNull($invoice);
        $this->assertSame(2000000, $invoice->total_omzet);
        $this->assertEquals(7.50, $invoice->commission_percentage);
        $this->assertSame(150000, $invoice->amount); // 7.5% of 2.000.000
    }

    public function test_overdue_commission_invoice_hides_cashier_and_role_menus_and_blocks_access(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        // Seed overdue unpaid commission invoice from last month
        $previousMonth = now()->subMonth()->format('Y-m');
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-TEST-001',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 10000000,
            'commission_percentage' => 10.00,
            'amount' => 1000000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => $previousMonth,
            'due_at' => now()->startOfMonth()->subDays(1),
        ]);

        $this->assertTrue($restaurant->hasOverdueCashierInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));
        $this->assertFalse($gate->hasFeature($restaurant, 'roles'));
        // CMS remains accessible as free bonus
        $this->assertTrue($gate->hasFeature($restaurant, 'cms'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // Cashier resource is blocked
        $this->assertFalse(OrderResource::canViewAny());

        // Role viewAny policy returns false
        $this->assertFalse(Gate::forUser($owner)->allows('viewAny', Role::class));

        // CMS banner remains viewable
        $this->assertTrue(CmsBannerResource::canViewAny());
    }

    public function test_founder_approval_of_overdue_invoice_restores_cashier_and_role_access(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        $previousMonth = now()->subMonth()->format('Y-m');
        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-TEST-002',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 10000000,
            'commission_percentage' => 10.00,
            'amount' => 1000000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => $previousMonth,
            'due_at' => now()->startOfMonth()->subDays(1),
        ]);

        $this->assertTrue($restaurant->hasOverdueCashierInvoice());

        $founder = $this->makeFounder();
        $invoiceService = app(SubscriptionInvoiceService::class);
        $invoiceService->submitProof($invoice, PlanCode::ManagementKds->value, 1, 'subscription-proofs/test.jpg');
        $invoiceService->approve($invoice->fresh(), $founder);

        $restaurant->refresh();
        $this->assertFalse($restaurant->hasOverdueCashierInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertTrue($gate->hasFeature($restaurant, 'operations'));
        $this->assertTrue($gate->hasFeature($restaurant, 'roles'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(OrderResource::canViewAny());
        $this->assertTrue(Gate::forUser($owner)->allows('viewAny', Role::class));
    }

    public function test_zero_omzet_month_end_is_automatically_marked_paid(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        // Run finalize at the start of current month to process previous month
        $previousMonthString = now()->subMonth()->format('Y-m');
        $finalizedCount = app(CashierCommissionBillingService::class)->finalizeMonthEndInvoices(now());

        $this->assertGreaterThanOrEqual(1, $finalizedCount);

        $lastMonthInvoice = SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('invoice_type', InvoiceType::CashierCommission->value)
            ->where('period_month', $previousMonthString)
            ->first();

        $this->assertNotNull($lastMonthInvoice);
        $this->assertSame(0, $lastMonthInvoice->total_omzet);
        $this->assertSame(0, $lastMonthInvoice->amount);
        $this->assertSame(InvoiceStatus::Paid, $lastMonthInvoice->status);

        // Ensure it doesn't trigger overdue
        $this->assertFalse($restaurant->hasOverdueCashierInvoice());
    }

    public function test_reminder_command_runs_without_sending_whatsapp(): void
    {
        $this->artisan('subscription:remind-commission')
            ->assertExitCode(0);
    }

    public function test_custom_403_page_renders_with_message_and_billing_action(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $view = $this->view('errors.403', [
            'exception' => new \Symfony\Component\HttpKernel\Exception\HttpException(
                403,
                'Layanan kasir dinonaktifkan sementara karena tagihan komisi kasir bulan lalu belum diselesaikan.'
            ),
        ]);

        $view->assertSee('Akses Dibatasi');
        $view->assertSee('Layanan kasir dinonaktifkan sementara karena tagihan komisi kasir bulan lalu belum diselesaikan.');
        $view->assertSee('Buka Billing & Pembayaran', false);
        $view->assertSee('subscription-status');
    }

    public function test_current_month_commission_invoice_cannot_upload_proof_before_month_end(): void
    {
        // Freeze time to mid-month (e.g. 15th)
        $this->travelTo(now()->startOfMonth()->addDays(14));

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-MID',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 5000000,
            'commission_percentage' => 10.00,
            'amount' => 500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->format('Y-m'),
            'due_at' => now()->endOfMonth()->startOfDay(),
        ]);

        $page = new SubscriptionStatus();
        $this->assertFalse($page->canUploadProof($invoice));

        $this->expectException(ValidationException::class);
        app(SubscriptionInvoiceService::class)->submitProof(
            $invoice,
            PlanCode::ManagementKds->value,
            1,
            'subscription-proofs/test.jpg'
        );
    }

    public function test_current_month_commission_invoice_can_upload_proof_on_month_end(): void
    {
        // Set time to the end of month
        $this->travelTo(now()->endOfMonth()->startOfDay());

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-END',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 5000000,
            'commission_percentage' => 10.00,
            'amount' => 500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->format('Y-m'),
            'due_at' => now()->endOfMonth()->startOfDay(),
        ]);

        $page = new SubscriptionStatus();
        $this->assertTrue($page->canUploadProof($invoice));

        // Submit proof succeeds
        $service = app(SubscriptionInvoiceService::class);
        $updated = $service->submitProof(
            $invoice,
            PlanCode::ManagementKds->value,
            1,
            'subscription-proofs/test.jpg'
        );

        $this->assertSame(InvoiceStatus::AwaitingVerification, $updated->status);
    }

    public function test_past_month_commission_invoice_allows_immediate_upload_proof(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $previousMonth = now()->subMonth()->format('Y-m');
        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-PAST',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 5000000,
            'commission_percentage' => 10.00,
            'amount' => 500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => $previousMonth,
            'due_at' => now()->startOfMonth()->subDays(1),
        ]);

        $page = new SubscriptionStatus();
        $this->assertTrue($page->canUploadProof($invoice));
    }

    public function test_views_render_qr_lightbox_modal_and_updated_billing_guide(): void
    {
        $transferWidget = $this->view('filament.pages.partials.subscription-transfer-widget', [
            'bankName' => 'BCA Test',
            'bankAccount' => '1234567890',
            'bankHolder' => 'PT Test',
            'contactEmail' => 'help@test.com',
            'qrUrl' => 'https://example.com/qr.png',
        ]);

        $transferWidget->assertSee('qrModalOpen');
        $transferWidget->assertSee('QR Code Pembayaran');
        $transferWidget->assertSee('Perbesar');
        $transferWidget->assertSee('1234567890');

        $billingGuide = $this->view('filament.pages.info-billing', [
            'trialDays' => 30,
            'commissionPercent' => 10.00,
        ]);

        $billingGuide->assertSee('komisi omzet kasir');
        $billingGuide->assertSee('10%');
        $billingGuide->assertSee('30 Hari');
        $billingGuide->assertSee('Omzet Rp 0 = Otomatis Lunas');
        $billingGuide->assertSee('Konsekuensi Keterlambatan (Overdue)');
        $billingGuide->assertSee('100% Gratis Selamanya');
    }

    private function createPaidOrder(Restaurant $restaurant, Outlet $outlet, int $amount): Order
    {
        $table = \App\Models\DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T-'.uniqid(),
            'capacity' => 4,
            'qr_secret' => Str::random(64),
        ]);

        $visit = Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'customer_name' => 'Tamu Test',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
            'closed_at' => now()->subMinutes(10),
        ]);

        $nextNumber = ((int) Order::query()->where('outlet_id', $outlet->id)->max('number')) + 1;

        $order = Order::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'number' => $nextNumber,
            'idempotency_key' => (string) Str::uuid(),
            'status' => Order::STATUS_COMPLETED,
            'source' => 'cashier',
            'payment_method' => 'cash',
            'currency' => $restaurant->currency ?: 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => $amount,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => $amount,
            'grand_payable' => $amount,
            'send_receipt' => false,
            'paid_at' => now(),
        ]);

        $station = \App\Models\KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'kitchen-'.uniqid(),
            'name' => 'Dapur',
        ]);

        OrderItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'station_id' => $station->id,
            'name_snapshot' => 'Test Item',
            'unit_price' => $amount,
            'qty' => 1,
            'kds_status' => 'served',
            'queued_at' => now(),
            'served_at' => now(),
        ]);

        return $order;
    }

    public function test_dashboard_hides_kds_widgets_when_billing_invoice_unpaid_overdue(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);
        $owner = $this->makeOwner($restaurant);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        // Create overdue unpaid cashier commission invoice
        $previousMonth = now()->subMonth()->format('Y-m');
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-OVERDUE-001',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 5000000,
            'commission_percentage' => 10.00,
            'amount' => 500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => $previousMonth,
            'due_at' => now()->startOfMonth()->subDays(1),
        ]);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // PendingPaymentsWidget (Antrian Kasir) must be hidden
        $this->assertFalse(PendingPaymentsWidget::canView());

        // RestaurantReadinessWidget dynamically spans full width when PendingPaymentsWidget is hidden
        $readinessWidget = new RestaurantReadinessWidget();
        $this->assertSame('full', $readinessWidget->getColumnSpan());

        // WelcomeBannerWidget reflects KDS inactive and offers pay billing action
        Livewire::test(WelcomeBannerWidget::class)
            ->assertOk()
            ->assertSee('Layanan Kasir & KDS Non-Aktif (Ada Tunggakan)', false)
            ->assertSee('Bayar Tagihan Billing')
            ->assertDontSee('+ Buat Pesanan Baru');

        // AnalyticsKpiWidget reflects non-aktif kasir with link to billing
        Livewire::test(AnalyticsKpiWidget::class)
            ->assertOk()
            ->assertSee('LAYANAN KASIR')
            ->assertSee('Ada Tunggakan')
            ->assertSee('Bayar Tagihan →');
    }
}
