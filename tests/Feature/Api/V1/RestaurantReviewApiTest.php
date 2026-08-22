<?php

namespace Tests\Feature\Api\V1;

use App\Models\Order;
use App\Models\RestaurantReview;
use App\Models\User;
use App\Services\OrderPaymentService;
use App\Services\VisitLifecycleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class RestaurantReviewApiTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_review_portal_closed_before_paid_order(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/review')
            ->assertOk()
            ->assertJsonPath('data.portal_open', false)
            ->assertJsonPath('data.can_submit', false)
            ->assertJsonPath('data.submitted', false);
    }

    public function test_review_portal_open_after_paid_but_submit_requires_completed(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
            ])
            ->assertCreated();

        $checkout = $this->withHeaders($headers + ['Idempotency-Key' => 'review-paid-1'])
            ->postJson('/api/v1/guest/checkout', ['method' => 'qris'])
            ->assertCreated();

        $order = Order::query()->where('public_id', $checkout->json('data.public_id'))->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/review')
            ->assertOk()
            ->assertJsonPath('data.portal_open', true)
            ->assertJsonPath('data.can_submit', false);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 5,
                'comment' => 'Makanan enak sekali!',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['review']);
    }

    public function test_submit_review_after_completed_order(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ])
            ->assertCreated();

        $order = $this->paidOrderForDevice($headers, $world);

        $order->update(['status' => 'completed']);

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/review')
            ->assertOk()
            ->assertJsonPath('data.can_submit', true);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 5,
                'comment' => 'Pelayanan ramah dan makanan enak.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.customer_name', 'Budi')
            ->assertJsonPath('data.rating', 5);

        $this->assertDatabaseHas('restaurant_reviews', [
            'visit_id' => $order->visit_id,
            'customer_name' => 'Budi',
            'rating' => 5,
        ]);
    }

    public function test_only_one_review_per_visit(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ])
            ->assertCreated();

        $order = $this->paidOrderForDevice($headers, $world);
        $order->update(['status' => 'completed']);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 4,
                'comment' => 'Cukup baik untuk harga.',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 5,
                'comment' => 'Ingin ubah ulasan tapi tidak boleh.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['review']);

        $this->assertSame(1, RestaurantReview::query()->where('visit_id', $order->visit_id)->count());
    }

    public function test_submit_review_blocked_after_visit_closed(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ])
            ->assertCreated();

        $order = $this->paidOrderForDevice($headers, $world);
        $order->update(['status' => 'completed']);

        app(VisitLifecycleService::class)->close($order->visit);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 5,
                'comment' => 'Terlambat karena visit sudah tutup.',
            ])
            ->assertUnauthorized();
    }

    public function test_review_validation_requires_rating_and_comment(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $order = $this->paidOrderForDevice($headers, $world);
        $order->update(['status' => 'completed']);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'comment']);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/review', [
                'rating' => 6,
                'comment' => 'pendek',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'comment']);
    }

    public function test_visit_resource_includes_review_status(): void
    {
        $world = $this->createGuestRestaurant();
        $headers = $this->deviceHeaders($this->newDeviceToken());

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/visit')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'review' => ['portal_open', 'can_submit', 'submitted', 'review'],
                ],
            ]);
    }

    /**
     * @param  array<string, string>  $headers
     * @param  array<string, mixed>  $world
     */
    private function paidOrderForDevice(array $headers, array $world): Order
    {
        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
            ])
            ->assertCreated();

        $checkout = $this->withHeaders($headers + ['Idempotency-Key' => uniqid('review-', true)])
            ->postJson('/api/v1/guest/checkout', ['method' => 'qris'])
            ->assertCreated();

        $order = Order::query()->where('public_id', $checkout->json('data.public_id'))->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh();
    }
}
