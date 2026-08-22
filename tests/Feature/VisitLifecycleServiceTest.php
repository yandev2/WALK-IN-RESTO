<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VisitCartItem;
use App\Services\VisitLifecycleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class VisitLifecycleServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_cashier_close_deletes_cart_and_marks_cleaning(): void
    {
        $world = $this->createGuestRestaurant();
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
            ])
            ->assertCreated();

        $visit = $world['table']->fresh()->openVisit;
        $this->assertSame(1, VisitCartItem::query()->where('visit_id', $visit->id)->count());

        app(VisitLifecycleService::class)->closeByCashier($visit, User::factory()->create());

        $this->assertSame('closed', $visit->fresh()->status);
        $this->assertSame(0, VisitCartItem::query()->where('visit_id', $visit->id)->count());
        $this->assertNull($world['table']->fresh()->open_visit_id);
        $this->assertTrue($world['table']->fresh()->needs_cleaning);
    }

    public function test_close_is_blocked_while_awaiting_cashier(): void
    {
        $world = $this->createGuestRestaurant();
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', ['menu_item_id' => $world['item']->id])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => 'close-block-1',
            ])
            ->assertCreated();

        $visit = $world['table']->fresh()->openVisit;

        $this->expectException(ValidationException::class);
        app(VisitLifecycleService::class)->closeByCashier($visit, User::factory()->create());
    }
}
