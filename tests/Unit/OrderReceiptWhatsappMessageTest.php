<?php

namespace Tests\Unit;

use App\Support\OrderReceiptWhatsappMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderReceiptWhatsappMessageTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_compose_includes_items_discount_and_totals(): void
    {
        $world = $this->createGuestRestaurant();
        $world['item']->update([
            'price' => 40000,
            'discount_percent' => 20,
        ]);

        $order = $this->paidGuestOrder($world, 'wa-msg-detail', sendReceipt: false);

        $message = OrderReceiptWhatsappMessage::compose($order->fresh());

        $this->assertStringContainsString('Pesanan:', $message);
        $this->assertStringContainsString($world['item']->name, $message);
        $this->assertStringContainsString('x1 @ Rp 32.000 = Rp 32.000 (diskon 20%)', $message);
        $this->assertStringContainsString('Subtotal: Rp', $message);
        $this->assertStringContainsString('Service: Rp', $message);
        $this->assertStringContainsString('PB1: Rp', $message);
        $this->assertStringContainsString('Total: Rp', $message);
        $this->assertStringContainsString('Unduh PDF', $message);
        $this->assertStringContainsString('/receipts/', $message);
    }
}
