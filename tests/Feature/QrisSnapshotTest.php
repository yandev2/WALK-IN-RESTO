<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Support\CmsMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class QrisSnapshotTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_qris_checkout_copies_outlet_image_to_payment_snapshot(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('outlets/qris/live.jpg', 'qris-image');

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['qris_image_path' => 'outlets/qris/live.jpg']);

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
                'qty' => 1,
            ])
            ->assertCreated();

        $checkout = $this->withHeaders($headers + ['Idempotency-Key' => 'qris-snapshot-1'])
            ->postJson('/api/v1/guest/checkout', ['method' => 'qris'])
            ->assertCreated();

        $order = Order::query()->where('public_id', $checkout->json('data.public_id'))->firstOrFail();
        $payment = Payment::query()->where('order_id', $order->id)->firstOrFail();

        $this->assertNotNull($payment->qris_image_path_snapshot);
        $this->assertNotSame('outlets/qris/live.jpg', $payment->qris_image_path_snapshot);
        $this->assertStringStartsWith('payment-qris-snapshots/', $payment->qris_image_path_snapshot);
        Storage::disk('public')->assertExists($payment->qris_image_path_snapshot);

        $world['outlet']->update(['qris_image_path' => 'outlets/qris/new-live.jpg']);
        CmsMedia::delete('outlets/qris/live.jpg');

        Storage::disk('public')->assertMissing('outlets/qris/live.jpg');
        Storage::disk('public')->assertExists($payment->qris_image_path_snapshot);
    }
}
