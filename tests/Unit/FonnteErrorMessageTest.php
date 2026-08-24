<?php

namespace Tests\Unit;

use App\Support\FonnteErrorMessage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FonnteErrorMessageTest extends TestCase
{
    #[DataProvider('reasonProvider')]
    public function test_maps_fonnte_reason_to_indonesian(string $reason, string $expected): void
    {
        $this->assertSame($expected, FonnteErrorMessage::fromReason($reason));
    }

    /**
     * @return list<array{0: string, 1: string}>
     */
    public static function reasonProvider(): array
    {
        return [
            ['token invalid', 'Token Fonnte tidak valid. Periksa API key di Profil CMS.'],
            ['insufficient quota', 'Kuota Fonnte habis. Upgrade paket atau tunggu reset bulanan.'],
            ['target invalid', 'Nomor WhatsApp tujuan tidak valid.'],
            ['request invalid on disconnected device', 'Perangkat WhatsApp Fonnte terputus. Buka dashboard Fonnte dan sambungkan ulang (scan QR).'],
            ['request timeout', 'Fonnte tidak merespons (timeout). Coba kirim ulang.'],
        ];
    }

    public function test_from_response_uses_http_status_when_reason_missing(): void
    {
        $this->assertSame(
            'Token Fonnte ditolak. Periksa API key di Profil CMS.',
            FonnteErrorMessage::fromResponse(null, 401),
        );
    }

    #[DataProvider('retryableProvider')]
    public function test_is_retryable(string $message, bool $retryable): void
    {
        $this->assertSame($retryable, FonnteErrorMessage::isRetryable($message));
    }

    /**
     * @return list<array{0: string, 1: bool}>
     */
    public static function retryableProvider(): array
    {
        return [
            ['Token Fonnte tidak valid. Periksa API key di Profil CMS.', false],
            ['Kuota Fonnte habis. Upgrade paket atau tunggu reset bulanan.', false],
            ['Nomor WhatsApp tujuan tidak valid.', false],
            ['Perangkat WhatsApp Fonnte terputus. Buka dashboard Fonnte dan sambungkan ulang (scan QR).', false],
            ['Fonnte tidak merespons (timeout). Coba kirim ulang.', true],
            ['Server Fonnte sedang bermasalah. Coba kirim ulang nanti.', true],
            ['Tidak bisa terhubung ke Fonnte. Periksa koneksi internet server.', true],
        ];
    }
}
