<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Services\FounderAnalyticsService;
use App\Services\SubscriptionInvoiceService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FounderDashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_founder_can_access_dashboard_and_see_all_analytics_widgets(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/founder');
        $response->assertSuccessful();

        // Check Header & Title
        $response->assertSee('Ringkasan Platform');

        // Check KPI Stats Cards
        $response->assertSee('Total Restoran Terdaftar');
        $response->assertSee('Pendapatan Sewa Bulan Ini');
        $response->assertSee('Restoran Mangkir / Overdue');
        $response->assertSee('Invoice Perlu Konfirmasi');

        // Check Charts
        $response->assertSee('Tren Pendapatan');
        $response->assertSee('Kesehatan Langganan');

        // Check Table Widgets
        $response->assertSee('Invoice Baru Menunggu Konfirmasi');
        $response->assertSee('Restoran Mangkir & Perlu Perhatian');
        $response->assertSee('Restoran Baru Terdaftar');
    }

    public function test_founder_analytics_service_kpi_calculation(): void
    {
        // 1. Active Restaurant
        $activeResto = Restaurant::create([
            'name' => 'Resto Berlangganan Aktif',
            'slug' => 'resto-aktif',
            'subscription_status' => SubscriptionStatus::Active,
            'subscribed_until' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 2. Overdue / Grace Restaurant
        $graceResto = Restaurant::create([
            'name' => 'Resto Grace Period',
            'slug' => 'resto-grace',
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(3),
            'is_active' => true,
        ]);

        // 3. Paid Invoice (Revenue)
        SubscriptionInvoice::create([
            'invoice_number' => 'INV-TEST-001',
            'restaurant_id' => $activeResto->id,
            'plan_code' => 'management_kds',
            'invoice_type' => InvoiceType::MonthlyFlat,
            'amount' => 500000,
            'status' => InvoiceStatus::Paid,
            'paid_at' => now(),
        ]);

        // 4. Overdue Unpaid Invoice
        SubscriptionInvoice::create([
            'invoice_number' => 'INV-TEST-OVERDUE',
            'restaurant_id' => $graceResto->id,
            'plan_code' => 'management_kds',
            'invoice_type' => InvoiceType::MonthlyFlat,
            'amount' => 350000,
            'status' => InvoiceStatus::Sent,
            'due_at' => now()->subDays(2),
        ]);

        // 5. Awaiting Verification Invoice
        SubscriptionInvoice::create([
            'invoice_number' => 'INV-TEST-PENDING',
            'restaurant_id' => $activeResto->id,
            'plan_code' => 'management_kds',
            'invoice_type' => InvoiceType::CashierCommission,
            'amount' => 150000,
            'status' => InvoiceStatus::AwaitingVerification,
            'payment_submitted_at' => now(),
        ]);

        $service = app(FounderAnalyticsService::class);
        $kpi = $service->getKpiData();

        $this->assertEquals(2, $kpi['totalRestaurants']);
        $this->assertEquals(2, $kpi['activeRestaurants']);
        $this->assertEquals(500000, $kpi['revenueThisMonth']);
        $this->assertEquals(500000, $kpi['revenueFlatThisMonth']);
        $this->assertEquals(1, $kpi['overdueRestaurantsCount']);
        $this->assertEquals(350000, $kpi['totalOverdueDebt']);
        $this->assertEquals(1, $kpi['pendingVerificationCount']);
        $this->assertEquals(150000, $kpi['pendingVerificationAmount']);
    }

    public function test_pending_invoice_can_be_approved_and_updates_restaurant_subscription(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $restaurant = Restaurant::create([
            'name' => 'Resto Bayar Sewa',
            'slug' => 'resto-bayar',
            'subscription_status' => SubscriptionStatus::Grace,
            'subscribed_until' => now()->subDay(),
            'is_active' => true,
        ]);

        $invoice = SubscriptionInvoice::create([
            'invoice_number' => 'INV-APPROVAL-001',
            'restaurant_id' => $restaurant->id,
            'plan_code' => 'management_kds',
            'invoice_type' => InvoiceType::MonthlyFlat,
            'billing_months' => 2,
            'amount' => 1000000,
            'status' => InvoiceStatus::AwaitingVerification,
            'payment_proof_path' => 'proofs/test-transfer.jpg',
            'payment_submitted_at' => now(),
        ]);

        $service = app(SubscriptionInvoiceService::class);
        $service->approve($invoice, $founder);

        $invoice->refresh();
        $restaurant->refresh();

        $this->assertEquals(InvoiceStatus::Paid, $invoice->status);
        $this->assertNotNull($invoice->paid_at);
        $this->assertEquals(SubscriptionStatus::Active, $restaurant->subscription_status);
        $this->assertTrue($restaurant->subscribed_until->isFuture());
    }
}
