<?php

namespace Tests\Feature;

use App\Support\OrderReceiptDownloadUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderReceiptDownloadTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_signed_link_downloads_receipt_pdf(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'receipt-download');

        $url = OrderReceiptDownloadUrl::signed($order->fresh());

        $this->get($url)
            ->assertOk()
            ->assertHeader('content-disposition');
    }

    public function test_unsigned_or_expired_link_is_rejected(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'receipt-download-unsigned');

        $this->get(route('receipts.download', ['order' => $order->public_id]))
            ->assertForbidden();

        $url = URL::temporarySignedRoute(
            'receipts.download',
            now()->subMinute(),
            ['order' => $order->public_id],
        );

        $this->get($url)->assertForbidden();
    }
}
