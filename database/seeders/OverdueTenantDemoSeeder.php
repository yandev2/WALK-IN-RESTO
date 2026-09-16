<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PlanCode;
use App\Models\DiningTable;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Models\Visit;
use App\Services\SubscriptionPlanSync;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class OverdueTenantDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or update the demo restaurant with overdue status
        $restaurant = Restaurant::query()->updateOrCreate(
            ['slug' => 'resto-mangkir'],
            [
                'name' => 'Resto Mangkir Bayar',
                'plan_code' => PlanCode::ManagementKds->value,
                'commission_percentage' => null,
                'trial_ends_at' => now()->subMonths(2),
                'subscribed_until' => null,
                'currency' => 'IDR',
                'is_active' => true,
                'listed_in_directory' => false,
            ]
        );

        // 2. Outlet
        $outlet = Outlet::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'code' => 'MAIN',
            ],
            [
                'name' => 'Cabang Utama',
                'address' => 'Jl. Mangkir No. 13, Jakarta',
                'phone' => '081299998888',
                'is_default' => true,
                'is_open' => true,
                'is_active' => true,
                'pb1_pct' => 10,
                'service_pct' => 0,
                'tax_mode' => 'exclusive',
            ]
        );

        // 3. KDS Stations
        $kitchenStation = KdsStation::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'slug' => 'dapur-utama',
            ],
            [
                'outlet_id' => $outlet->id,
                'name' => 'Stasiun Dapur Utama',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $barStation = KdsStation::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'slug' => 'minuman',
            ],
            [
                'outlet_id' => $outlet->id,
                'name' => 'Stasiun Minuman',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 4. Dining Tables
        foreach (range(1, 4) as $number) {
            DiningTable::query()->updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'code' => (string) $number,
                ],
                [
                    'capacity' => 4,
                ]
            );
        }

        // 5. Menu Categories & Items
        $foodCategory = MenuCategory::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'name' => 'Makanan',
            ],
            [
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $drinkCategory = MenuCategory::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'name' => 'Minuman',
            ],
            [
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        $foodItem = MenuItem::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'name' => 'Mie Goreng Spesial',
            ],
            [
                'category_id' => $foodCategory->id,
                'station_id' => $kitchenStation->id,
                'price' => 25000,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $drinkItem = MenuItem::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'name' => 'Es Jeruk Segar',
            ],
            [
                'category_id' => $drinkCategory->id,
                'station_id' => $barStation->id,
                'price' => 10000,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // 6. Users & Roles
        app()[PermissionRegistrar::class]->setPermissionsTeamId($restaurant->id);

        $ownerRole = Role::query()->firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);

        $kasirRole = Role::query()->firstOrCreate([
            'name' => 'kasir',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $kasirRole->syncPermissions([
            'order.create',
            'order.verify_payment',
            'kds.view',
        ]);

        $dapurRole = Role::query()->firstOrCreate([
            'name' => 'dapur',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $dapurRole->syncPermissions([
            'kds.view',
            'kds.update_status',
        ]);

        $owner = User::query()->updateOrCreate(
            ['email' => 'mangkir@resto.test'],
            [
                'name' => 'Owner Resto Mangkir',
                'username' => 'ownermangkir',
                'password' => 'password',
                'is_active' => true,
            ]
        );

        $kasir = User::query()->updateOrCreate(
            ['email' => 'kasir.mangkir@resto.test'],
            [
                'name' => 'Kasir Resto Mangkir',
                'username' => 'kasirmangkir',
                'password' => 'password',
                'is_active' => true,
            ]
        );

        $dapur = User::query()->updateOrCreate(
            ['email' => 'dapur.mangkir@resto.test'],
            [
                'name' => 'Dapur Resto Mangkir',
                'username' => 'dapurmangkir',
                'password' => 'password',
                'is_active' => true,
            ]
        );

        foreach ([$owner, $kasir, $dapur] as $staff) {
            $restaurant->users()->syncWithoutDetaching([$staff->id => ['is_active' => true]]);
            DB::table('outlet_users')->updateOrInsert(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'user_id' => $staff->id,
                ],
                ['created_at' => now()]
            );
        }

        app()[PermissionRegistrar::class]->setPermissionsTeamId($restaurant->id);
        $owner->assignRole($ownerRole);
        $kasir->assignRole($kasirRole);
        $dapur->assignRole($dapurRole);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );

        // 7. Seed Overdue Cashier Commission Invoice (past due date, unpaid)
        $previousMonth = now()->subMonth()->format('Y-m');
        $overdueDate = now()->subDays(5);

        SubscriptionInvoice::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'period_month' => $previousMonth,
                'invoice_type' => InvoiceType::CashierCommission->value,
            ],
            [
                'invoice_number' => 'INV-COMM-MANGKIR-001',
                'plan_code' => PlanCode::ManagementKds->value,
                'requested_plan_code' => PlanCode::ManagementKds->value,
                'billing_months' => 1,
                'total_omzet' => 25000000,
                'commission_percentage' => 10.00,
                'amount' => 2500000,
                'status' => InvoiceStatus::Sent->value,
                'paid_at' => null,
                'due_at' => $overdueDate,
            ]
        );

        // 8. Seed sample order queued for kitchen
        $table1 = DiningTable::query()->where('restaurant_id', $restaurant->id)->where('code', '1')->first();
        if ($table1) {
            $visit = Visit::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'table_id' => $table1->id,
                    'status' => 'open',
                ],
                [
                    'outlet_id' => $outlet->id,
                    'public_id' => (string) Str::ulid(),
                    'join_pin' => '4321',
                    'customer_name' => 'Budi Mangkir',
                    'customer_wa' => '6281122334455',
                    'claimed_at' => now()->subMinutes(20),
                    'claim_expires_at' => now()->addHour(),
                ]
            );

            $order = Order::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'visit_id' => $visit->id,
                ],
                [
                    'outlet_id' => $outlet->id,
                    'number' => 101,
                    'idempotency_key' => (string) Str::uuid(),
                    'status' => Order::STATUS_PAID,
                    'source' => 'cashier',
                    'payment_method' => 'cash',
                    'currency' => 'IDR',
                    'pb1_pct_snapshot' => 0,
                    'service_pct_snapshot' => 0,
                    'tax_mode_snapshot' => 'exclusive',
                    'subtotal' => 35000,
                    'discount_amount' => 0,
                    'service_amount' => 0,
                    'pb1_amount' => 0,
                    'grand_before' => 35000,
                    'grand_payable' => 35000,
                    'send_receipt' => false,
                    'paid_at' => now()->subMinutes(15),
                ]
            );

            OrderItem::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'order_id' => $order->id,
                    'menu_item_id' => $foodItem->id,
                ],
                [
                    'outlet_id' => $outlet->id,
                    'station_id' => $kitchenStation->id,
                    'name_snapshot' => $foodItem->name,
                    'unit_price' => 25000,
                    'qty' => 1,
                    'kds_status' => 'queued',
                    'queued_at' => now()->subMinutes(15),
                ]
            );

            OrderItem::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'order_id' => $order->id,
                    'menu_item_id' => $drinkItem->id,
                ],
                [
                    'outlet_id' => $outlet->id,
                    'station_id' => $barStation->id,
                    'name_snapshot' => $drinkItem->name,
                    'unit_price' => 10000,
                    'qty' => 1,
                    'kds_status' => 'queued',
                    'queued_at' => now()->subMinutes(15),
                ]
            );
        }
    }
}
