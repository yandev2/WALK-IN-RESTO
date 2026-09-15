<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Filament\Pages\KitchenDisplay;
use App\Filament\Pages\SubscriptionStatus;
use App\Filament\Resources\KdsStations\KdsStationResource;
use App\Filament\Resources\MenuCategories\MenuCategoryResource;
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
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class KdsSubscriptionLockTest extends TestCase
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

    public function test_active_restaurant_can_access_kds_and_kds_stations(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->addDays(20),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        $gate = app(SubscriptionGate::class);
        $this->assertTrue($gate->hasFeature($restaurant, 'kds'));
        $this->assertTrue($gate->hasFeature($restaurant, 'operations'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertTrue(KitchenDisplay::canAccess());
        $this->assertTrue(KdsStationResource::canViewAny());
        $this->assertTrue(KdsStationResource::canCreate());

        $page = new KitchenDisplay();
        $this->assertTrue($page->canAdvance());
        $this->assertTrue($page->canMarkServed());
    }

    public function test_overdue_commission_invoice_locks_kds_and_stations_while_leaving_menu_accessible(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        // Seed overdue commission invoice
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-OVERDUE-01',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::CashierCommission->value,
            'billing_months' => 1,
            'total_omzet' => 15000000,
            'commission_percentage' => 10.00,
            'amount' => 1500000,
            'status' => InvoiceStatus::Sent->value,
            'period_month' => now()->subMonth()->format('Y-m'),
            'due_at' => now()->subDays(3),
        ]);

        $this->assertTrue($restaurant->hasOverdueCashierInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restaurant, 'kds'));
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));
        // Menu editing remains allowed so the owner can manage menu
        $this->assertTrue($gate->hasFeature($restaurant, 'menu'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        // KDS page and KDS stations resource are strictly locked
        $this->assertFalse(KitchenDisplay::canAccess());
        $this->assertFalse(KdsStationResource::canViewAny());
        $this->assertFalse(KdsStationResource::canCreate());

        // Regular menu category resource is still accessible
        $this->assertTrue(MenuCategoryResource::canViewAny());

        // KDS action mutations are strictly blocked
        $page = new KitchenDisplay();
        $this->assertFalse($page->canAdvance());
        $this->assertFalse($page->canMarkServed());
    }

    public function test_overdue_subscription_billing_invoice_locks_kds_and_stations(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        // Seed overdue subscription billing invoice
        SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-SUB-OVERDUE-01',
            'restaurant_id' => $restaurant->id,
            'plan_code' => PlanCode::ManagementKds->value,
            'requested_plan_code' => PlanCode::ManagementKds->value,
            'invoice_type' => InvoiceType::MonthlyFlat->value,
            'billing_months' => 1,
            'amount' => 499000,
            'status' => InvoiceStatus::Sent->value,
            'due_at' => now()->subDays(2),
        ]);

        $this->assertTrue($restaurant->hasUnpaidOverdueInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restaurant, 'kds'));
        $this->assertFalse($gate->hasFeature($restaurant, 'operations'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->assertFalse(KitchenDisplay::canAccess());
        $this->assertFalse(KdsStationResource::canViewAny());
        $this->assertFalse(KdsStationResource::canCreate());
    }

    public function test_paying_overdue_invoice_unlocks_kds_and_stations_immediately(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'trial_ends_at' => now()->subMonths(2),
        ]);

        $owner = $this->makeOwner($restaurant);
        app(SubscriptionPlanSync::class)->syncOwnerPermissions($restaurant, PlanCode::ManagementKds->value);

        $invoice = SubscriptionInvoice::query()->create([
            'invoice_number' => 'INV-COMM-OVERDUE-02',
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
            'due_at' => now()->subDays(5),
        ]);

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restaurant, 'kds'));

        // Mark invoice as paid
        $invoice->update([
            'status' => InvoiceStatus::Paid->value,
            'paid_at' => now(),
        ]);

        $this->assertFalse($restaurant->fresh()->hasOverdueCashierInvoice());
        $this->assertTrue($gate->hasFeature($restaurant->fresh(), 'kds'));

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant->fresh());

        $this->assertTrue(KitchenDisplay::canAccess());
        $this->assertTrue(KdsStationResource::canViewAny());
    }

    public function test_overdue_tenant_demo_seeder_locks_kds(): void
    {
        $this->seed(OverdueTenantDemoSeeder::class);

        $restoMangkir = Restaurant::query()->where('slug', 'resto-mangkir')->first();
        $this->assertNotNull($restoMangkir);
        $this->assertTrue($restoMangkir->hasOverdueCashierInvoice());

        $gate = app(SubscriptionGate::class);
        $this->assertFalse($gate->hasFeature($restoMangkir, 'kds'));
        $this->assertFalse($gate->hasFeature($restoMangkir, 'operations'));
        // CMS & menu should be accessible
        $this->assertTrue($gate->hasFeature($restoMangkir, 'cms'));
        $this->assertTrue($gate->hasFeature($restoMangkir, 'menu'));

        $dapurUser = User::query()->where('email', 'dapur.mangkir@resto.test')->first();
        $this->assertNotNull($dapurUser);

        $this->actingAs($dapurUser);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restoMangkir);

        $this->assertFalse(KitchenDisplay::canAccess());
    }

    public function test_kitchen_only_user_redirects_to_subscription_status_when_kds_is_locked(): void
    {
        $this->seed(OverdueTenantDemoSeeder::class);

        $restoMangkir = Restaurant::query()->where('slug', 'resto-mangkir')->first();
        $dapurUser = User::query()->where('email', 'dapur.mangkir@resto.test')->first();

        $this->actingAs($dapurUser);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restoMangkir);

        $panel = Filament::getCurrentPanel();
        $homeUrl = $panel->getHomeUrl();

        $this->assertSame(SubscriptionStatus::getUrl(tenant: $restoMangkir), $homeUrl);
    }
}
