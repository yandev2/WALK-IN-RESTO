<?php

namespace Tests\Feature;

use App\Services\FonnteClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class FonnteClientTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_connection_error_is_retryable(): void
    {
        Http::fake(function (): never {
            throw new ConnectionException('Connection refused');
        });

        $result = app(FonnteClient::class)->sendReceiptMessage('token', '6281234567890', 'Halo');

        $this->assertFalse($result['ok']);
        $this->assertTrue($result['retryable']);
        $this->assertStringContainsString('terhubung', (string) $result['error']);
    }

    public function test_token_invalid_is_not_retryable(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => false, 'reason' => 'token invalid'], 200),
        ]);

        $result = app(FonnteClient::class)->sendReceiptMessage('bad-token', '6281234567890', 'Halo');

        $this->assertFalse($result['ok']);
        $this->assertFalse($result['retryable']);
        $this->assertSame('Token Fonnte tidak valid. Periksa API key di Profil CMS.', $result['error']);
    }
}
