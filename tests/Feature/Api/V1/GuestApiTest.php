<?php

namespace Tests\Feature\Api\V1;

use App\Support\TableQrToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestApiTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_lists_and_shows_restaurants(): void
    {
        $world = $this->createGuestRestaurant();

        $this->getJson('/api/v1/restaurants')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'resto-api');

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug)
            ->assertOk()
            ->assertJsonPath('data.slug', 'resto-api')
            ->assertJsonPath('data.cta_label', 'Lihat lokasi')
            ->assertJsonPath('data.outlet_name', 'Utama')
            ->assertJsonPath('data.is_open', true);
    }

    public function test_claim_menu_cart_and_qris_checkout(): void
    {
        $world = $this->createGuestRestaurant();
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/tables/'.$world['token'])
            ->assertOk()
            ->assertJsonPath('data.mode', 'claim')
            ->assertJsonPath('data.restaurant.slug', 'resto-api')
            ->assertJsonPath('data.restaurant.name', 'Resto API')
            ->assertJsonStructure(['data' => ['restaurant' => ['slug', 'name', 'logo_url', 'theme']]]);

        $claim = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
                'customer_name' => 'Budi',
            ]);

        $claim->assertCreated()
            ->assertJsonPath('data.table_code', '1')
            ->assertJsonPath('data.restaurant.slug', 'resto-api')
            ->assertJsonPath('data.restaurant.name', 'Resto API');

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/visit')
            ->assertOk()
            ->assertJsonPath('data.restaurant.name', 'Resto API')
            ->assertJsonPath('data.table_code', '1');

        $this->assertSame(4, strlen((string) $claim->json('data.join_pin')));

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/menu')
            ->assertOk()
            ->assertJsonPath('data.0.items.0.name', 'Es Teh');

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
                'qty' => 2,
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->getJson('/api/v1/guest/cart')
            ->assertOk()
            ->assertJsonPath('data.subtotal', 16000)
            ->assertJsonPath('data.service_pct', 5)
            ->assertJsonPath('data.pb1_pct', 10)
            ->assertJsonPath('data.tax_mode', 'exclusive')
            ->assertJsonPath('data.service_amount', 800)
            ->assertJsonPath('data.pb1_amount', 1680)
            ->assertJsonPath('data.grand_before', 18480);

        $this->withHeaders($headers + ['Idempotency-Key' => 'test-checkout-1'])
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'awaiting_cashier')
            ->assertJsonPath('data.payment_method', 'qris')
            ->assertJsonPath('data.payment.method', 'qris');
    }

    public function test_join_without_pin_returns_422(): void
    {
        $world = $this->createGuestRestaurant();
        $host = $this->newDeviceToken();

        $this->withHeaders($this->deviceHeaders($host))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $guest = $this->newDeviceToken();

        $this->withHeaders($this->deviceHeaders($guest))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/join', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['pin']);
    }

    public function test_claim_without_whatsapp_returns_422(): void
    {
        $world = $this->createGuestRestaurant();
        $device = $this->newDeviceToken();

        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_name' => 'Budi',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['customer_wa']);
    }

    public function test_cash_checkout_outside_geofence_returns_422(): void
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

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'cash',
                'idempotency_key' => 'cash-outside-1',
                'gps' => [
                    'lat' => -6.0000000,
                    'lng' => 106.8000000,
                    'accuracy' => 10,
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['gps']);
    }

    public function test_cannot_view_another_visits_order(): void
    {
        $world = $this->createGuestRestaurant();

        $deviceA = $this->newDeviceToken();
        $headersA = $this->deviceHeaders($deviceA);
        $this->withHeaders($headersA)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();
        $this->withHeaders($headersA)
            ->postJson('/api/v1/guest/cart/items', ['menu_item_id' => $world['item']->id])
            ->assertCreated();
        $orderPublicId = $this->withHeaders($headersA)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => 'order-a',
            ])
            ->assertCreated()
            ->json('data.public_id');

        $deviceB = $this->newDeviceToken();
        $headersB = $this->deviceHeaders($deviceB);
        $this->withHeaders($headersB)
            ->postJson('/api/v1/guest/tables/'.TableQrToken::make($world['otherTable']).'/claim', [
                'customer_wa' => '081111111111',
            ])
            ->assertCreated();

        $this->withHeaders($headersB)
            ->getJson('/api/v1/guest/orders/'.$orderPublicId)
            ->assertForbidden();
    }

    public function test_proof_upload_rejected_for_cash_orders(): void
    {
        Storage::fake('public');

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

        $orderPublicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'cash',
                'idempotency_key' => 'cash-denied',
                'gps' => ['gps_status' => 'denied'],
            ])
            ->assertCreated()
            ->json('data.public_id');

        $this->withHeaders($headers)
            ->post('/api/v1/guest/orders/'.$orderPublicId.'/proof', [
                'proof' => UploadedFile::fake()->image('bukti.jpg'),
            ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['proof']);
    }
}
