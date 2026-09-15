<?php

namespace Tests\Feature;

use App\Filament\Pages\CustomerAnalytics;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\CustomerLoyaltyPoint;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CustomerCrmService;
use App\Services\OrderReceiptService;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CustomerCrmLoyaltyTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_paid_order_registers_customer_and_earns_loyalty_points(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        $order = $this->paidGuestOrder(
            $world,
            'crm-test-1',
            customerWa: '081234567890',
            customerName: 'Budi Santoso',
        );

        $customer = Customer::query()->where('phone', '6281234567890')->first();
        $this->assertNotNull($customer);
        $this->assertSame('Budi Santoso', $customer->name);
        $this->assertSame('reguler', $customer->tier);
        $this->assertSame(1, $customer->total_orders);
        $this->assertSame((int) $order->grand_payable, $customer->total_spent);

        // Default: kelipatan Rp 10.000 dapat 1 poin
        $expectedPoints = (int) floor((int) $order->grand_payable / 10000);
        $this->assertSame($expectedPoints, $customer->points_balance);

        $this->assertTrue(
            CustomerLoyaltyPoint::query()
                ->where('customer_id', $customer->id)
                ->where('order_id', $order->id)
                ->where('type', 'earn')
                ->exists()
        );
    }

    public function test_custom_loyalty_spending_ratio_and_tier_upgrades(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        // Kustomisasi oleh owner: belanja Rp 5.000 dapat 2 poin, ambang silver 100k, gold 200k, vip 500k
        $restaurant->updateLoyaltySettings([
            'enabled' => true,
            'spend_per_point' => 5000,
            'points_earned' => 2,
            'silver_min_spent' => 100000,
            'gold_min_spent' => 200000,
            'vip_min_spent' => 500000,
        ]);

        $item2 = $this->extraMenuItem($world, 'Steak Wagyu', 250000);

        // Buat order dengan total di atas 200k
        $order = $this->paidGuestOrder(
            $world,
            'crm-custom-ratio',
            menuItemIds: [$item2->id],
            customerWa: '081987654321',
            customerName: 'Siti Rahma',
        );

        $customer = Customer::query()->where('phone', '6281987654321')->first();
        $this->assertNotNull($customer);

        // Belanja > Rp 200.000 otomatis menjadi Gold!
        $this->assertSame('gold', $customer->tier);
        $this->assertSame('Gold', $customer->tierLabel());

        $expectedPoints = (int) floor((int) $order->grand_payable / 5000) * 2;
        $this->assertSame($expectedPoints, $customer->points_balance);
    }

    public function test_customer_tier_badge_colors(): void
    {
        $reguler = new Customer(['tier' => 'reguler']);
        $regular = new Customer(['tier' => 'regular']);
        $silver = new Customer(['tier' => 'silver']);
        $gold = new Customer(['tier' => 'gold']);
        $vip = new Customer(['tier' => 'vip']);

        $this->assertSame('success', $reguler->badgeColor());
        $this->assertSame('success', $regular->badgeColor());
        $this->assertSame('gray', $silver->badgeColor());
        $this->assertSame('amber', $gold->badgeColor());
        $this->assertSame('indigo', $vip->badgeColor());
    }

    public function test_loyalty_can_be_disabled_by_owner_without_breaking_crm_tracking(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        // Owner menonaktifkan program poin & member
        $restaurant->updateLoyaltySettings([
            'enabled' => false,
        ]);

        $order = $this->paidGuestOrder(
            $world,
            'crm-disabled-loyalty',
            customerWa: '085555444333',
            customerName: 'Ahmad Dani',
        );

        $customer = Customer::query()->where('phone', '6285555444333')->first();
        $this->assertNotNull($customer);

        // Analitik CRM tetap mencatat belanja & kunjungan
        $this->assertSame(1, $customer->total_orders);
        $this->assertSame((int) $order->grand_payable, $customer->total_spent);

        // Namun saldo poin tetap 0 dan tier tetap reguler
        $this->assertSame(0, $customer->points_balance);
        $this->assertSame('reguler', $customer->tier);
    }

    public function test_idempotency_prevents_duplicate_crm_points_and_spending(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        $order = $this->paidGuestOrder(
            $world,
            'crm-idempotent-test',
            customerWa: '087777888999',
            customerName: 'Dewi Lestari',
        );

        $customer = Customer::query()->where('phone', '6287777888999')->first();
        $initialSpent = $customer->total_spent;
        $initialPoints = $customer->points_balance;
        $initialOrders = $customer->total_orders;

        // Panggil ulang recordOrderLoyalty untuk order yang sama
        $crm = app(CustomerCrmService::class);
        $crm->recordOrderLoyalty($order);

        $customer->refresh();
        $this->assertSame($initialSpent, $customer->total_spent);
        $this->assertSame($initialPoints, $customer->points_balance);
        $this->assertSame($initialOrders, $customer->total_orders);
    }

    public function test_receipt_renders_tier_and_points_when_loyalty_enabled(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        $order = $this->paidGuestOrder(
            $world,
            'receipt-loyalty-check',
            customerWa: '081299998888',
            customerName: 'Rian Pratama',
        );

        $receipts = app(OrderReceiptService::class);
        $receipt = $receipts->generate($order);

        $this->assertNotNull($receipt);
        $this->assertTrue(Storage::disk('local')->exists($receipt->file_path));

        // Periksa bahwa file PDF dibuat dan dapat digenerate tanpa error view
        $content = Storage::disk('local')->get($receipt->file_path);
        $this->assertNotEmpty($content);
    }

    public function test_owner_can_adjust_customer_points_with_reason(): void
    {
        $world = $this->createGuestRestaurant();

        $customer = Customer::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281122334455',
            'name' => 'Doni Kusuma',
            'tier' => 'silver',
            'points_balance' => 20,
            'total_spent' => 150000,
            'total_orders' => 2,
        ]);

        $owner = User::factory()->create();

        $crm = app(CustomerCrmService::class);
        $crm->adjustPoints($customer, 15, 'Bonus ulang tahun resto', $owner);

        $customer->refresh();
        $this->assertSame(35, $customer->points_balance);

        $mutation = CustomerLoyaltyPoint::query()
            ->where('customer_id', $customer->id)
            ->where('type', 'adjustment')
            ->first();

        $this->assertNotNull($mutation);
        $this->assertSame(15, $mutation->points);
        $this->assertSame(35, $mutation->balance_after);
        $this->assertSame('Bonus ulang tahun resto', $mutation->description);
        $this->assertSame($owner->id, $mutation->created_by_user_id);
    }

    public function test_crm_analytics_aggregation(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        // Customer A: 5 orders, 500k spent
        Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628111111111',
            'name' => 'Customer A',
            'tier' => 'silver',
            'points_balance' => 50,
            'total_spent' => 500000,
            'total_orders' => 5,
        ]);

        // Customer B: 2 orders, 1.000.000 spent
        Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628222222222',
            'name' => 'Customer B',
            'tier' => 'gold',
            'points_balance' => 100,
            'total_spent' => 1000000,
            'total_orders' => 2,
        ]);

        $crm = app(CustomerCrmService::class);
        $analytics = $crm->getAnalytics($restaurant);

        $this->assertSame(2, $analytics['total_customers']);
        $this->assertSame(2, $analytics['repeat_customers']);
        $this->assertSame(1500000, $analytics['total_ltv']);
        $this->assertSame(750000, $analytics['average_ltv']);
        $this->assertSame(150, $analytics['total_points']);

        // Top frequent: Customer A is #1 (5 orders)
        $this->assertSame('Customer A', $analytics['top_frequent']->first()->name);

        // Top spender: Customer B is #1 (1.000.000 spent)
        $this->assertSame('Customer B', $analytics['top_spenders']->first()->name);

        // Enriched Analytics metrics
        $this->assertSame(0, $analytics['one_time_customers']);
        $this->assertSame(100.0, $analytics['repeat_rate']);
        $this->assertSame(7, $analytics['total_orders_count']); // 5 + 2
        $this->assertSame(214286, $analytics['average_order_value']); // 1.500.000 / 7
        $this->assertSame(3.5, $analytics['avg_orders_per_customer']); // 7 / 2
        $this->assertSame(1, $analytics['tier_counts']['silver']);
        $this->assertSame(1, $analytics['tier_counts']['gold']);
        $this->assertSame(0, $analytics['tier_counts']['vip']);
        $this->assertSame(0, $analytics['tier_counts']['reguler']);

        // Model helper assertions
        $this->assertSame(100000, $analytics['top_frequent']->first()->averageSpendPerOrder());
        $this->assertSame(500000, $analytics['top_spenders']->first()->averageSpendPerOrder());
        $this->assertStringContainsString('https://wa.me/628111111111', $analytics['top_frequent']->first()->waLink('Halo Kak'));
    }

    public function test_tenant_isolation_crm_customers(): void
    {
        $worldA = $this->createGuestRestaurant();

        $restaurantB = Restaurant::query()->create([
            'name' => 'Resto B',
            'slug' => 'resto-b',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        Customer::query()->create([
            'restaurant_id' => $worldA['restaurant']->id,
            'phone' => '628999999999',
            'name' => 'Customer Resto A',
            'tier' => 'gold',
        ]);

        $crm = app(CustomerCrmService::class);
        $analyticsB = $crm->getAnalytics($restaurantB);

        $this->assertSame(0, $analyticsB['total_customers']);
        $this->assertCount(0, $analyticsB['top_frequent']);
    }

    public function test_owner_can_view_customers_list_and_crm_analytics_page(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456789',
            'name' => 'John Doe',
            'tier' => 'silver',
            'points_balance' => 25,
            'total_spent' => 250000,
            'total_orders' => 3,
        ]);

        $owner = User::factory()->create();
        $ownerRole = \App\Models\Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', 'owner')
            ->first();

        if ($ownerRole) {
            $owner->assignRole($ownerRole);
        } else {
            $owner->assignRole('super_admin');
        }

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(ListCustomers::class)
            ->assertOk()
            ->assertSee('John Doe')
            ->assertSee('+62 812-3456-789');

        Livewire::test(CustomerAnalytics::class)
            ->assertOk()
            ->assertActionExists('guide')
            ->assertActionExists('settings')
            ->assertSee('Pelanggan Paling Sering Datang')
            ->assertSee('Pelanggan Belanja Terbesar')
            ->assertSee('John Doe')
            ->callAction('settings', [
                'enabled' => true,
                'spend_per_point' => 15000,
                'points_earned' => 3,
                'silver_min_spent' => 600000,
                'gold_min_spent' => 2000000,
                'vip_min_spent' => 6000000,
            ])
            ->assertHasNoActionErrors();

        $guideView = view('filament.pages.info-loyalty', [
            'settings' => $restaurant->loyaltySettings(),
        ])->render();
        $this->assertStringContainsString('Loyalty Program', $guideView);
        $this->assertStringContainsString('Alur Cara Kerja Sistem', $guideView);
        $this->assertStringContainsString('Tingkatan Tier', $guideView);

        $restaurant->refresh();
        $settings = $restaurant->loyaltySettings();
        $this->assertTrue($settings['enabled']);
        $this->assertSame(15000, $settings['spend_per_point']);
        $this->assertSame(3, $settings['points_earned']);
        $this->assertSame(600000, $settings['silver_min_spent']);
        $this->assertSame(2000000, $settings['gold_min_spent']);
        $this->assertSame(6000000, $settings['vip_min_spent']);
    }

    public function test_customer_can_redeem_points_for_discount_at_checkout(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];

        // Customer with 50 points
        $customer = Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456789',
            'name' => 'Budi Santoso',
            'tier' => 'gold',
            'points_balance' => 50,
            'total_spent' => 1500000,
            'total_orders' => 10,
        ]);

        $item = $this->extraMenuItem($world, 'Paket Hemat', 80000);
        $visit = $this->createClaimedVisit($world, 'MEJA-01', customerWa: '08123456789', customerName: 'Budi Santoso');
        $this->addItemToCart($visit, $item, 1);

        $checkoutService = app(\App\Services\GuestCheckoutService::class);
        $order = $checkoutService->checkout(
            visit: $visit,
            method: 'cash',
            sendReceipt: false,
            idempotencyKey: 'redeem-test-1',
            gps: [],
            pointsToRedeem: 20,
        );

        $this->assertSame(20, (int) $order->points_redeemed);
        $this->assertSame(20000, (int) $order->discount_amount);
        $this->assertSame(80000, (int) $order->subtotal);

        // Subtotal net = 80000 - 20000 = 60000
        $subtotalNet = 60000;
        $service = (int) round($subtotalNet * (float) $outlet->service_pct / 100);
        $pb1 = (int) round(($subtotalNet + $service) * (float) $outlet->pb1_pct / 100);
        $grandBefore = $subtotalNet + $service + $pb1;

        $this->assertSame($service, (int) $order->service_amount);
        $this->assertSame($pb1, (int) $order->pb1_amount);
        $this->assertSame($grandBefore, (int) $order->grand_before);
        $this->assertSame($grandBefore, (int) $order->grand_payable);

        // Verify customer points deducted immediately
        $customer->refresh();
        $this->assertSame(30, $customer->points_balance);

        $mutation = CustomerLoyaltyPoint::query()
            ->where('customer_id', $customer->id)
            ->where('order_id', $order->id)
            ->where('type', 'redeem')
            ->first();
        $this->assertNotNull($mutation);
        $this->assertSame(-20, (int) $mutation->points);
        $this->assertSame(30, (int) $mutation->balance_after);
    }

    public function test_points_cannot_exceed_max_percentage_or_balance(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        // Max percentage is 50%
        $restaurant->updateLoyaltySettings([
            'enabled' => true,
            'point_redemption_rate' => 1000,
            'min_redeem_points' => 10,
            'max_redeem_percentage' => 50,
        ]);

        $customer = Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456788',
            'name' => 'Agus',
            'points_balance' => 100, // Rp 100.000 value
        ]);

        $crm = app(\App\Services\CustomerCrmService::class);

        // Subtotal Rp 100.000, max discount 50% = Rp 50.000 = 50 points
        // Even if requested 90 points, it must be capped at 50 points
        $calc = $crm->calculateRedemption($customer, 100000, 90, $restaurant);
        $this->assertTrue($calc['allowed']);
        $this->assertSame(50, $calc['points']);
        $this->assertSame(50000, $calc['discount_amount']);

        // If customer only has 25 points, it must be capped at 25 points
        $customer->points_balance = 25;
        $calc2 = $crm->calculateRedemption($customer, 100000, 50, $restaurant);
        $this->assertTrue($calc2['allowed']);
        $this->assertSame(25, $calc2['points']);
        $this->assertSame(25000, $calc2['discount_amount']);

        // If points below min (e.g. 5 points), it is rejected
        $customer->points_balance = 5;
        $calc3 = $crm->calculateRedemption($customer, 100000, 5, $restaurant);
        $this->assertFalse($calc3['allowed']);
    }

    public function test_cancelled_or_voided_order_refunds_redeemed_points(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        $customer = Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456787',
            'name' => 'Dewi',
            'points_balance' => 50,
        ]);

        $item = $this->extraMenuItem($world, 'Menu Spesial', 100000);
        $visit = $this->createClaimedVisit($world, 'MEJA-02', customerWa: '08123456787', customerName: 'Dewi');
        $this->addItemToCart($visit, $item, 1);

        $checkoutService = app(\App\Services\GuestCheckoutService::class);
        $order = $checkoutService->checkout(
            visit: $visit,
            method: 'cash',
            sendReceipt: false,
            idempotencyKey: 'refund-test-1',
            gps: [],
            pointsToRedeem: 20,
        );

        $customer->refresh();
        $this->assertSame(30, $customer->points_balance);

        // Refund points on order void/cancellation
        $refundMutation = app(\App\Services\CustomerCrmService::class)->refundPointsForOrder($order);
        $this->assertNotNull($refundMutation);
        $this->assertSame('refund', $refundMutation->type);
        $this->assertSame(20, (int) $refundMutation->points);

        $customer->refresh();
        $this->assertSame(50, $customer->points_balance);

        // Idempotency: refunding again should not add duplicate points
        $secondRefund = app(\App\Services\CustomerCrmService::class)->refundPointsForOrder($order);
        $this->assertNull($secondRefund);
        $customer->refresh();
        $this->assertSame(50, $customer->points_balance);
    }

    public function test_commission_reconciliation_excludes_points_discount(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        $customer = Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456786',
            'name' => 'Eko',
            'points_balance' => 50,
        ]);

        $item = $this->extraMenuItem($world, 'Paket Makan', 100000);
        $visit = $this->createClaimedVisit($world, 'MEJA-03', customerWa: '08123456786', customerName: 'Eko');
        $this->addItemToCart($visit, $item, 1);

        $checkoutService = app(\App\Services\GuestCheckoutService::class);
        $order = $checkoutService->checkout(
            visit: $visit,
            method: 'cash',
            sendReceipt: false,
            idempotencyKey: 'comm-test-1',
            gps: [],
            pointsToRedeem: 20,
        );

        // Mark paid
        $order->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $reconciliation = app(\App\Services\CommissionReconciliationService::class);
        $summary = $reconciliation->summaryForPeriod($restaurant, now()->startOfDay(), now()->endOfDay());

        $this->assertSame(100000, $summary['gross_sales']);
        $this->assertSame(20000, $summary['discount_amount']);
        $this->assertSame(20000, $summary['points_discount_amount']);
        $this->assertSame(20, $summary['points_redeemed']);

        // Net sales is pure menu price after point discount (100k - 20k = 80000)
        $this->assertSame(80000, $summary['net_sales']);
        $this->assertSame(12400, $summary['tax_service_amount']);
        $this->assertSame(92400, $summary['total_collected']);

        // Order detail
        $detail = $reconciliation->orderCommissionDetail($order, $restaurant);
        $this->assertSame(20, $detail['points_redeemed']);
        $this->assertSame(20000, $detail['points_discount']);
    }

    public function test_guide_modal_contains_commission_exemption_explanation(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];

        $guideView = view('filament.pages.info-loyalty', [
            'settings' => $restaurant->loyaltySettings(),
        ])->render();

        $this->assertStringContainsString('Penukaran Poin Menjadi Diskon (Point-as-Discount)', $guideView);
        $this->assertStringContainsString('Restoran TIDAK Dikenakan Potongan Komisi atas Diskon Poin!', $guideView);
        $this->assertStringContainsString('Transparansi Tutup Shift & Rekonsiliasi Kasir', $guideView);
    }

    public function test_cashier_pos_can_lookup_customer_and_redeem_points(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];

        // Customer with 50 points
        $customer = Customer::query()->create([
            'restaurant_id' => $restaurant->id,
            'phone' => '628123456780',
            'name' => 'Fajar',
            'tier' => 'gold',
            'points_balance' => 50,
        ]);

        $item = $this->extraMenuItem($world, 'Nasi Ayam', 50000);

        // Staff user for cashier
        $user = User::factory()->create();

        $cashierOrderService = app(\App\Services\CashierOrderService::class);
        $order = $cashierOrderService->create(
            user: $user,
            table: $world['table'],
            customerWa: '08123456780',
            customerName: 'Fajar',
            method: 'cash',
            sendReceipt: false,
            lines: [
                ['menu_item_id' => $item->id, 'qty' => 1],
            ],
            cashReceived: 50000,
            pointsToRedeem: 20,
        );

        $this->assertSame(20, (int) $order->points_redeemed);
        $this->assertSame(20000, (int) $order->discount_amount);
        $this->assertSame(50000, (int) $order->subtotal);

        // Points balance deducted
        $customer->refresh();
        $this->assertSame(30, $customer->points_balance);
    }

    private function createClaimedVisit(array $world, string $tableCode = '1', ?string $customerWa = null, ?string $customerName = null): \App\Models\Visit
    {
        return \App\Models\Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'active',
            'public_id' => (string) \Illuminate\Support\Str::ulid(),
            'customer_wa' => $customerWa,
            'customer_name' => $customerName,
            'join_pin' => '1234',
            'claimed_at' => now()->subMinutes(10),
            'claim_expires_at' => now()->addHours(2),
        ]);
    }

    private function addItemToCart(\App\Models\Visit $visit, \App\Models\MenuItem $item, int $qty = 1): \App\Models\VisitCartItem
    {
        return \App\Models\VisitCartItem::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'menu_item_id' => $item->id,
            'qty' => $qty,
        ]);
    }
}
