<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Filament\Pages\CommissionReconciliation;
use App\Filament\Pages\CustomerAnalytics;
use App\Filament\Pages\CustomerSatisfactionAnalytics;
use App\Filament\Pages\GenerateReport;
use App\Filament\Resources\CustomerReviews\CustomerReviewResource;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\ExportFiles\ExportFileResource;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Services\SubscriptionPlanSync;
use App\Support\SubscriptionGate;
use Database\Seeders\OverdueTenantDemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class OverdueTenantAccessRestrictionTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        PlatformSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'trial_days' => 30,
                'cashier_commission_percentage' => 10.00,
            ]
        );
    }

    public function test_active_restaurant_can_access_crm_and_all_reports(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->addDays(20),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        $gate = app(SubscriptionGate::class);
        $this->assertTrue($gate->hasFeature($restaurant, 'crm'));
        $this->assertTrue($gate->hasFeature($restaurant, 'analytics'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // CRM items
        $this->assertTrue(CustomerResource::canViewAny());
        $this->assertTrue(CustomerReviewResource::canViewAny());
        $this->assertTrue(CustomerSatisfactionAnalytics::canAccess());
        $this->assertTrue(CustomerAnalytics::canAccess());

        // Report items
        $this->assertTrue(GenerateReport::canAccess());
        $this->assertTrue(ExportFileResource::canViewAny());
        $this->assertTrue(CommissionReconciliation::canAccess());
    }

    public function test_overdue_restaurant_locks_and_hides_crm_and_reports_except_commission_reconciliation(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        // Seed overdue commission invoice
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-TEST-COMM-OVERDUE',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 10000000,
            'commission_percentage' => 10.00,
            'amount' => 1000000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->subMonth()->format('Y-m'),
            'due_at' => now()->subDays(3),
        ]);

        $this->assertTrue($restaurant->hasOverdueCashierInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restaurant, 'crm'));
        $this->assertFalse($gate->hasFeature($restaurant, 'analytics'));
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));
        $this->assertFalse($gate->hasFeature($restaurant, 'kds'));

        // CMS & menu remain accessible
        $this->assertTrue($gate->hasFeature($restaurant, 'cms'));
        $this->assertTrue($gate->hasFeature($restaurant, 'menu'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // CRM items are ALL locked/hidden
        $this->assertFalse(CustomerResource::canViewAny());
        $this->assertFalse(CustomerReviewResource::canViewAny());
        $this->assertFalse(CustomerSatisfactionAnalytics::canAccess());
        $this->assertFalse(CustomerAnalytics::canAccess());

        // Report items: General report and export history are locked/hidden
        $this->assertFalse(GenerateReport::canAccess());
        $this->assertFalse(ExportFileResource::canViewAny());

        // CRITICAL EXCEPTION: Commission Reconciliation remains ACCESSIBLE to the owner
        $this->assertTrue(CommissionReconciliation::canAccess());
    }

    public function test_overdue_tenant_demo_seeder_resto_mangkir_restricts_access(): void
    {
        $this->seed(OverdueTenantDemoSeeder::class);

        $restoMangkir = Restaurant::query()->where('slug', 'resto-mangkir')->first();
        $this->assertNotNull($restoMangkir);
        $this->assertTrue($restoMangkir->hasOverdueCashierInvoice());

        $ownerUser = User::query()->where('email', 'mangkir@resto.test')->first();
        $this->assertNotNull($ownerUser);

        $this->actingAs($ownerUser);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restoMangkir);

        // CRM items must be hidden
        $this->assertFalse(CustomerResource::canViewAny());
        $this->assertFalse(CustomerReviewResource::canViewAny());
        $this->assertFalse(CustomerSatisfactionAnalytics::canAccess());
        $this->assertFalse(CustomerAnalytics::canAccess());

        // Report items must be hidden except Commission Reconciliation
        $this->assertFalse(GenerateReport::canAccess());
        $this->assertFalse(ExportFileResource::canViewAny());

        // Owner can access Commission Reconciliation to see bill details
        $this->assertTrue(CommissionReconciliation::canAccess());

        Livewire::test(CommissionReconciliation::class)
            ->assertOk()
            ->assertSee('Rekonsiliasi Penjualan & Komisi')
            ->assertSee('#101');
    }

    public function test_paying_invoice_restores_all_crm_and_report_access_immediately(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-RESTORE-01',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 10000000,
            'commission_percentage' => 10.00,
            'amount' => 1000000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->subMonth()->format('Y-m'),
            'due_at' => now()->subDays(4),
        ]);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertFalse(CustomerResource::canViewAny());
        $this->assertFalse(GenerateReport::canAccess());

        // Pay the invoice
        $invoice->update([
            'status' => InvoiceStatus::Paid->value,
            'paid_at' => now(),
        ]);

        $fresh = $restaurant->fresh();
        Filament::setTenant($fresh);

        // Access restored
        $this->assertTrue(CustomerResource::canViewAny());
        $this->assertTrue(CustomerReviewResource::canViewAny());
        $this->assertTrue(CustomerSatisfactionAnalytics::canAccess());
        $this->assertTrue(CustomerAnalytics::canAccess());
        $this->assertTrue(GenerateReport::canAccess());
        $this->assertTrue(ExportFileResource::canViewAny());
        $this->assertTrue(CommissionReconciliation::canAccess());
    }
}
