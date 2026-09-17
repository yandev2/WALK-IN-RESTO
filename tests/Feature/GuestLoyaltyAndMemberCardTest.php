<?php

namespace Tests\Feature;

use App\Livewire\Guest\GuestCheckout;
use App\Livewire\Guest\GuestMenu;
use App\Livewire\Guest\GuestStatus;
use App\Models\Customer;
use App\Models\CustomerLoyaltyPoint;
use App\Models\Order;
use App\Models\Visit;
use App\Models\VisitDevice;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestLoyaltyAndMemberCardTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    private string $deviceToken = 'test-guest-device-loyalty-123';

    private function openGuestVisit(): array
    {
        $world = $this->createGuestRestaurant();
        $token = Str::random(64);

        $visit = Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'open',
            'join_pin' => '4321',
            'customer_name' => 'Budi',
            'customer_wa' => '6281234567890',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
        ]);

        $world['table']->update(['open_visit_id' => $visit->id]);

        VisitDevice::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'device_token' => $token,
            'is_host' => true,
            'user_agent' => 'test',
            'joined_at' => now(),
            'last_seen_at' => now(),
        ]);

        return [$visit, $token, $world];
    }

    public function test_guest_menu_renders_member_card_with_tier_styling(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();
        $visit->update(['customer_wa' => '6281234567890']);

        // 1. VIP Tier
        $customer = Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Sultan Iskandar',
            'tier' => 'vip',
            'total_spent' => 7500000,
            'points_balance' => 750,
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestMenu::class)
            ->assertSee('VIP MEMBER')
            ->assertSee('Sultan Iskandar')
            ->assertSee('750')
            ->assertSee('Poin')
            ->assertSee('from-[#1a0533]');

        // 2. Gold Tier
        $customer->update(['tier' => 'gold', 'name' => 'Juragan Emas']);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestMenu::class)
            ->assertSee('GOLD MEMBER')
            ->assertSee('Juragan Emas')
            ->assertSee('from-amber-400');

        // 3. Silver Tier
        $customer->update(['tier' => 'silver', 'name' => 'Ksatria Perak']);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestMenu::class)
            ->assertSee('SILVER MEMBER')
            ->assertSee('Ksatria Perak')
            ->assertSee('from-slate-100');

        // 4. Reguler Tier
        $customer->update(['tier' => 'reguler', 'name' => 'Budi Santoso']);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestMenu::class)
            ->assertSee('REGULER MEMBER')
            ->assertSee('Budi Santoso')
            ->assertSee('from-emerald-600');
    }

    public function test_guest_checkout_displays_conversion_rate_and_min_points_info(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();
        $visit->update(['customer_wa' => '6281234567890']);

        $world['restaurant']->updateLoyaltySettings([
            'enabled' => true,
            'point_redemption_rate' => 1000,
            'min_redeem_points' => 10,
        ]);

        // Customer with insufficient points (< 10)
        Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Budi Baru',
            'tier' => 'reguler',
            'total_spent' => 25000,
            'points_balance' => 5,
        ]);

        // Add item to cart
        $visit->cartItems()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $world['item']->id,
            'qty' => 2,
            'notes' => null,
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestCheckout::class)
            ->assertSee('Tukar Poin Belanja')
            ->assertSee('Nilai 1 Poin')
            ->assertSee('Rp 1.000')
            ->assertSee('Minimal Penukaran')
            ->assertSee('10 Poin')
            ->assertSee('belum mencapai batas minimal penukaran')
            ->assertDontSee('Pakai Maksimal');
    }

    public function test_guest_checkout_allows_redemption_when_points_sufficient(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();
        $visit->update(['customer_wa' => '6281234567890']);

        $world['restaurant']->updateLoyaltySettings([
            'enabled' => true,
            'point_redemption_rate' => 1000,
            'min_redeem_points' => 10,
            'max_redeem_percentage' => 50,
        ]);

        // Customer with sufficient points (50 points)
        Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Siti Loyal',
            'tier' => 'gold',
            'total_spent' => 1500000,
            'points_balance' => 50,
        ]);

        // Add item to cart: 5 * 8000 = 40000 subtotal
        // Max 50% discount = 20000 = 20 points max discount
        $visit->cartItems()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $world['item']->id,
            'qty' => 5,
            'notes' => null,
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestCheckout::class)
            ->assertSee('Tukarkan Poin untuk Potongan Belanja')
            ->set('usePoints', true)
            ->call('applyMaxPoints')
            ->assertSet('usePoints', true)
            ->assertSet('pointsToRedeem', 20)
            ->assertSee('Diskon Poin (20 Poin)')
            ->assertSee('-Rp 20.000');
    }

    public function test_guest_status_renders_points_earned_modal_and_can_dismiss(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();
        $visit->update(['customer_wa' => '6281234567890']);

        $customer = Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Agus Beruntung',
            'tier' => 'reguler',
            'total_spent' => 0,
            'points_balance' => 10,
        ]);

        $order = Order::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $visit->id,
            'number' => 101,
            'idempotency_key' => (string) Str::uuid(),
            'status' => 'paid',
            'source' => 'guest',
            'payment_method' => 'qris',
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 100000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 100000,
            'grand_payable' => 100000,
            'paid_at' => now(),
        ]);

        // Record loyalty earn point
        CustomerLoyaltyPoint::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'type' => 'earn',
            'points' => 10,
            'balance_after' => 20,
            'description' => 'Poin belanja pesanan #101',
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(GuestStatus::class)
            ->assertSee('Selamat! Kamu Mendapatkan Poin!')
            ->assertSee('+10 Poin')
            ->assertSee('Rp 100.000')
            ->assertSee('#101')
            ->assertSee('20')
            ->call('dismissLoyaltyAlert', $order->id)
            ->assertDontSee('Selamat! Kamu Mendapatkan Poin!');
    }

    public function test_guest_pay_renders_points_earned_modal_when_order_paid(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();
        $visit->update(['customer_wa' => '6281234567890']);

        $customer = Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Agus Beruntung',
            'tier' => 'reguler',
            'total_spent' => 0,
            'points_balance' => 10,
        ]);

        $order = Order::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $visit->id,
            'number' => 102,
            'idempotency_key' => (string) Str::uuid(),
            'status' => 'paid',
            'source' => 'guest',
            'payment_method' => 'qris',
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 100000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 100000,
            'grand_payable' => 100000,
            'paid_at' => now(),
        ]);

        CustomerLoyaltyPoint::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'type' => 'earn',
            'points' => 15,
            'balance_after' => 25,
            'description' => 'Poin belanja pesanan #102',
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestPay::class, ['order' => $order])
            ->assertSee('Selamat! Kamu Mendapatkan Poin!')
            ->assertSee('+15 Poin')
            ->assertSee('#102')
            ->call('dismissLoyaltyAlert', $order->id)
            ->assertDontSee('Selamat! Kamu Mendapatkan Poin!');
    }
}
