<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitDevice;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestReviewWebTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_status_page_shows_review_cta_when_order_completed(): void
    {
        [$visit, $token, $item] = $this->openWebVisit('Siti');

        $order = $this->paidOrderForVisit($visit, $token, $item);
        $order->update(['status' => 'completed']);

        $this->withCookie('guest_device', $token)
            ->get(route('guest.status'))
            ->assertOk()
            ->assertSee('Beri ulasan', false);
    }

    public function test_guest_can_submit_review_via_livewire(): void
    {
        [$visit, $token, $item] = $this->openWebVisit('Siti');

        $order = $this->paidOrderForVisit($visit, $token, $item);
        $order->update(['status' => 'completed']);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestReview::class)
            ->set('rating', 5)
            ->set('comment', 'Makanannya enak dan pelayanan ramah.')
            ->call('submit')
            ->assertSet('done', true);

        $this->assertDatabaseHas('restaurant_reviews', [
            'visit_id' => $visit->id,
            'customer_name' => 'Siti',
            'rating' => 5,
        ]);
    }

    /**
     * @return array{0: Visit, 1: string, 2: MenuItem}
     */
    private function openWebVisit(string $name): array
    {
        $world = $this->createGuestRestaurant();
        $token = Str::random(64);

        $visit = Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'open',
            'join_pin' => '1234',
            'customer_name' => $name,
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

        return [$visit, $token, $world['item']];
    }

    private function paidOrderForVisit(Visit $visit, string $token, MenuItem $item): Order
    {
        $headers = $this->deviceHeaders($token);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $item->id,
            ])
            ->assertCreated();

        $checkout = $this->withHeaders($headers + ['Idempotency-Key' => uniqid('web-review-', true)])
            ->postJson('/api/v1/guest/checkout', ['method' => 'qris'])
            ->assertCreated();

        $order = Order::query()->where('public_id', $checkout->json('data.public_id'))->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh();
    }
}
