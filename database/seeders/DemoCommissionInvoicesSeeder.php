<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Models\Visit;
use App\Services\CashierCommissionBillingService;
use App\Services\SubscriptionPlanSync;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoCommissionInvoicesSeeder extends Seeder
{
    public function run(): void
    {
        $previousMonth = now()->subMonth()->format('Y-m');
        $prevMonthDue = now()->startOfMonth()->subDays(1);

        // 1. Resto 1: Kopi Nusantara (Paid Invoice, Standard 10% commission)
        $kopi = Restaurant::query()->where('slug', 'kopi-nusantara')->first();
        if ($kopi) {
            $kopi->update([
                'plan_code' => PlanCode::ManagementKds->value,
                'commission_percentage' => null, // Global default 10%
                'trial_ends_at' => now()->subMonths(2),
            ]);

            $this->setupStaffAndPermissions($kopi, 'owner-kopi@resto.test', 'Owner Kopi Nusantara');

            // Seed Paid Commission Invoice for previous month
            SubscriptionInvoice::query()->updateOrCreate(
                [
                    'restaurant_id' => $kopi->id,
                    'period_month' => $previousMonth,
                    'invoice_type' => InvoiceType::CashierCommission->value,
                ],
                [
                    'invoice_number' => 'INV-COMM-'.strtoupper(Str::random(6)),
                    'plan_code' => PlanCode::ManagementKds->value,
                    'requested_plan_code' => PlanCode::ManagementKds->value,
                    'billing_months' => 1,
                    'total_omzet' => 25000000,
                    'commission_percentage' => 10.00,
                    'amount' => 2500000,
                    'status' => InvoiceStatus::Paid->value,
                    'paid_at' => now()->startOfMonth()->subDays(2),
                    'due_at' => $prevMonthDue,
                ]
            );

            // Seed real-time order for current month
            $this->seedPaidOrder($kopi, 1500000);
            app(CashierCommissionBillingService::class)->syncRealtimeMonthInvoice($kopi, now());
        }

        // 2. Resto 2: Sushi Haru (Paid Invoice, Custom Override 7.5% commission)
        $sushi = Restaurant::query()->where('slug', 'sushi-haru')->first();
        if ($sushi) {
            $sushi->update([
                'plan_code' => PlanCode::ManagementKds->value,
                'commission_percentage' => 7.50, // Custom override 7.5%
                'trial_ends_at' => now()->subMonths(2),
            ]);

            $this->setupStaffAndPermissions($sushi, 'owner-sushi@resto.test', 'Owner Sushi Haru');

            // Seed Paid Commission Invoice for previous month
            SubscriptionInvoice::query()->updateOrCreate(
                [
                    'restaurant_id' => $sushi->id,
                    'period_month' => $previousMonth,
                    'invoice_type' => InvoiceType::CashierCommission->value,
                ],
                [
                    'invoice_number' => 'INV-COMM-'.strtoupper(Str::random(6)),
                    'plan_code' => PlanCode::ManagementKds->value,
                    'requested_plan_code' => PlanCode::ManagementKds->value,
                    'billing_months' => 1,
                    'total_omzet' => 60000000,
                    'commission_percentage' => 7.50,
                    'amount' => 4500000,
                    'status' => InvoiceStatus::Paid->value,
                    'paid_at' => now()->startOfMonth()->subDays(1),
                    'due_at' => $prevMonthDue,
                ]
            );

            // Seed real-time order for current month
            $this->seedPaidOrder($sushi, 3200000);
            app(CashierCommissionBillingService::class)->syncRealtimeMonthInvoice($sushi, now());
        }

        // 3. Resto 3: Warung Nusantara (OVERDUE Unpaid Invoice, Standard 10%)
        $warung = Restaurant::query()->where('slug', 'warung-nusantara')->first();
        if ($warung) {
            $warung->update([
                'plan_code' => PlanCode::ManagementKds->value,
                'commission_percentage' => null,
                'trial_ends_at' => now()->subMonths(2),
            ]);

            $this->setupStaffAndPermissions($warung, 'owner-warung@resto.test', 'Owner Warung Nusantara');

            // Seed UNPAID (Overdue) Commission Invoice for previous month
            SubscriptionInvoice::query()->updateOrCreate(
                [
                    'restaurant_id' => $warung->id,
                    'period_month' => $previousMonth,
                    'invoice_type' => InvoiceType::CashierCommission->value,
                ],
                [
                    'invoice_number' => 'INV-COMM-'.strtoupper(Str::random(6)),
                    'plan_code' => PlanCode::ManagementKds->value,
                    'requested_plan_code' => PlanCode::ManagementKds->value,
                    'billing_months' => 1,
                    'total_omzet' => 18000000,
                    'commission_percentage' => 10.00,
                    'amount' => 1800000,
                    'status' => InvoiceStatus::Sent->value, // Unpaid
                    'paid_at' => null,
                    'due_at' => $prevMonthDue,
                ]
            );

            // Seed real-time order for current month
            $this->seedPaidOrder($warung, 950000);
            app(CashierCommissionBillingService::class)->syncRealtimeMonthInvoice($warung, now());
        }
    }

    private function setupStaffAndPermissions(Restaurant $restaurant, string $ownerEmail, string $ownerName): void
    {
        $outlet = Outlet::query()->where('restaurant_id', $restaurant->id)->first();

        // Dedicated owner for this restaurant
        $owner = User::query()->firstOrCreate(
            ['email' => $ownerEmail],
            [
                'name' => $ownerName,
                'username' => Str::slug($ownerName, ''),
                'password' => 'password',
                'is_active' => true,
            ]
        );

        app()[PermissionRegistrar::class]->setPermissionsTeamId($restaurant->id);

        $ownerRole = Role::query()->firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);

        $restaurant->users()->syncWithoutDetaching([$owner->id => ['is_active' => true]]);

        if ($outlet) {
            DB::table('outlet_users')->updateOrInsert(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'user_id' => $owner->id,
                ],
                ['created_at' => now()]
            );
        }

        $owner->assignRole($ownerRole);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );
    }

    private function seedPaidOrder(Restaurant $restaurant, int $amount): void
    {
        $outlet = Outlet::query()->where('restaurant_id', $restaurant->id)->first();
        if (! $outlet) {
            return;
        }

        $table = DiningTable::query()->where('restaurant_id', $restaurant->id)->first();
        $menuItem = MenuItem::query()->where('restaurant_id', $restaurant->id)->first();

        $visit = Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table?->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'customer_name' => 'Pelanggan Demo',
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
            'paid_at' => now()->subHours(2),
        ]);

        if ($menuItem) {
            OrderItem::query()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'station_id' => $menuItem->station_id,
                'name_snapshot' => $menuItem->name,
                'unit_price' => $amount,
                'qty' => 1,
                'notes' => 'Demo order',
                'kds_status' => 'served',
                'queued_at' => now()->subHours(2),
                'served_at' => now()->subHours(2),
            ]);
        }
    }
}
