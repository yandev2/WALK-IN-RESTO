<?php

namespace Tests\Feature;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Visit;
use App\Services\ExportService;
use App\Support\TableQrToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_http_responses_include_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');
        $response->assertHeader('Content-Security-Policy');

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("script-src 'self' 'unsafe-inline' 'unsafe-eval'", $csp);
        $this->assertStringContainsString("worker-src 'self' blob:", $csp);
        $this->assertStringContainsString("child-src 'self' blob:", $csp);
        $this->assertStringContainsString("frame-src 'self' https://www.google.com https://maps.google.com", $csp);
    }

    public function test_hsts_header_is_sent_when_connection_is_secure(): void
    {
        $response = $this->get('https://localhost/');

        $response->assertOk();
        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    public function test_dining_table_hides_qr_secret_in_serialization(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Sec Test',
            'slug' => 'resto-sec-test',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Utama',
            'is_active' => true,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T01',
            'capacity' => 4,
            'qr_version' => 1,
            'qr_secret' => 'super-secret-token-key',
        ]);

        $array = $table->toArray();
        $json = $table->toJson();

        $this->assertArrayNotHasKey('qr_secret', $array);
        $this->assertStringNotContainsString('super-secret-token-key', $json);
    }

    public function test_export_service_uses_local_private_disk_by_default(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Export Sec',
            'slug' => 'resto-export-sec',
            'is_active' => true,
        ]);

        $user = User::factory()->create();

        $exportService = app(ExportService::class);
        $export = $exportService->createQueued(
            restaurantId: $restaurant->id,
            userId: $user->id,
            module: 'penjualan_order',
            format: 'excel',
            filters: [],
            filename: 'test.xlsx',
            filePath: 'restaurants/'.$restaurant->id.'/export/test.xlsx',
        );

        $this->assertSame('local', $export->disk);
    }

    public function test_payment_proof_view_requires_authorization(): void
    {
        Storage::fake('local');

        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Proof Sec',
            'slug' => 'resto-proof-sec',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUT-01',
            'name' => 'Cabang Utama',
            'is_active' => true,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T01',
            'capacity' => 4,
            'qr_version' => 1,
            'qr_secret' => 'proof-sec-token',
        ]);

        $visit = Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'open',
            'join_pin' => '1234',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
            'customer_wa' => '081234567890',
        ]);

        $order = Order::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'public_id' => 'ord-sec-123',
            'number' => 1,
            'idempotency_key' => (string) Str::uuid(),
            'status' => 'awaiting_cashier',
            'source' => 'qr_guest',
            'payment_method' => 'qris',
            'currency' => 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 50000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 50000,
            'grand_payable' => 50000,
            'send_receipt' => false,
        ]);

        $fakeFile = UploadedFile::fake()->image('receipt.jpg');
        $storedPath = $fakeFile->store('payment-proofs/'.$restaurant->id, 'local');

        $payment = Payment::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'order_id' => $order->id,
            'public_id' => 'pay-sec-123',
            'method' => 'qris',
            'status' => 'pending',
            'amount' => 50000,
            'proof_image_path' => $storedPath,
        ]);

        $routeUrl = route('payments.proof.show', ['order' => $order->public_id, 'payment' => $payment->public_id]);

        // 1. Unauthorized guest without visit or signature -> 403 Forbidden
        $response = $this->get($routeUrl);
        $response->assertStatus(403);

        // 2. Authorized via Temporary Signed URL -> 200 OK with private cache headers
        $signedUrl = URL::temporarySignedRoute(
            'payments.proof.show',
            now()->addMinutes(30),
            ['order' => $order->public_id, 'payment' => $payment->public_id]
        );
        $signedResponse = $this->get($signedUrl);
        $signedResponse->assertOk();
        $this->assertStringContainsString('private', (string) $signedResponse->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', (string) $signedResponse->headers->get('Cache-Control'));
        $signedResponse->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_guest_scan_route_has_throttle_middleware(): void
    {
        $route = app('router')->getRoutes()->getByName('guest.scan');
        $middleware = $route ? $route->gatherMiddleware() : [];

        $hasThrottle = false;
        foreach ($middleware as $m) {
            if (str_starts_with($m, 'throttle:')) {
                $hasThrottle = true;
                break;
            }
        }

        $this->assertTrue($hasThrottle, 'Route guest.scan must have throttle middleware attached.');
    }

    public function test_payment_proof_rejects_cross_tenant_or_traversal_path(): void
    {
        Storage::fake('local');

        $restaurantA = Restaurant::query()->create([
            'name' => 'Resto A',
            'slug' => 'resto-a',
            'currency' => 'IDR',
            'is_active' => true,
        ]);
        $restaurantB = Restaurant::query()->create([
            'name' => 'Resto B',
            'slug' => 'resto-b',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $outletA = Outlet::query()->create([
            'restaurant_id' => $restaurantA->id,
            'name' => 'Cabang A',
            'code' => 'CBG-A',
            'is_active' => true,
        ]);

        $tableA = DiningTable::query()->create([
            'restaurant_id' => $restaurantA->id,
            'outlet_id' => $outletA->id,
            'code' => 'T01',
            'capacity' => 4,
            'qr_version' => 1,
            'qr_secret' => 'proof-sec-token-a',
        ]);

        $visitA = Visit::query()->create([
            'restaurant_id' => $restaurantA->id,
            'outlet_id' => $outletA->id,
            'table_id' => $tableA->id,
            'status' => 'open',
            'join_pin' => '1234',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
            'customer_wa' => '081234567890',
        ]);

        $orderA = Order::query()->create([
            'restaurant_id' => $restaurantA->id,
            'outlet_id' => $outletA->id,
            'visit_id' => $visitA->id,
            'public_id' => 'ord-sec-a',
            'number' => 1,
            'idempotency_key' => (string) Str::uuid(),
            'status' => 'awaiting_cashier',
            'source' => 'qr_guest',
            'payment_method' => 'qris',
            'currency' => 'IDR',
            'pb1_pct_snapshot' => 0,
            'service_pct_snapshot' => 0,
            'tax_mode_snapshot' => 'exclusive',
            'subtotal' => 50000,
            'discount_amount' => 0,
            'service_amount' => 0,
            'pb1_amount' => 0,
            'grand_before' => 50000,
            'grand_payable' => 50000,
            'send_receipt' => false,
        ]);

        // Attempt pointing to Restaurant B's path
        $payment = Payment::query()->create([
            'restaurant_id' => $restaurantA->id,
            'outlet_id' => $outletA->id,
            'order_id' => $orderA->id,
            'public_id' => 'pay-sec-traversal',
            'method' => 'qris',
            'status' => 'pending',
            'amount' => 50000,
            'proof_image_path' => 'payment-proofs/'.$restaurantB->id.'/receipt.jpg',
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'payments.proof.show',
            now()->addMinutes(30),
            ['order' => $orderA->public_id, 'payment' => $payment->public_id]
        );

        $response = $this->get($signedUrl);
        $response->assertNotFound();
    }

    public function test_guest_device_middleware_sanitizes_malicious_or_oversized_cookie(): void
    {
        $oversizedToken = str_repeat('A', 200);

        $response = $this->withCookie('guest_device', $oversizedToken)
            ->get(route('guest.need-scan'));

        $response->assertOk();
        $cookie = $response->getCookie('guest_device');
        $this->assertNotNull($cookie);
        $this->assertSame(64, strlen($cookie->getValue()));
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]{64}$/', $cookie->getValue());
    }
}
