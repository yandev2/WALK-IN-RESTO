<?php

namespace App\Support;

final class FonnteErrorMessage
{
    /**
     * @param  array<string, mixed>|null  $payload
     */
    public static function fromResponse(?array $payload, int $httpStatus): string
    {
        $reason = strtolower(trim((string) (
            $payload['reason']
            ?? $payload['message']
            ?? $payload['detail']
            ?? ''
        )));

        if ($reason !== '') {
            return self::fromReason($reason);
        }

        if ($httpStatus >= 500) {
            return 'Server Fonnte sedang bermasalah. Coba kirim ulang nanti.';
        }

        if ($httpStatus === 401 || $httpStatus === 403) {
            return 'Token Fonnte ditolak. Periksa API key di Profil CMS.';
        }

        return 'Fonnte menolak permintaan (HTTP '.$httpStatus.').';
    }

    public static function fromReason(string $reason): string
    {
        $reason = strtolower(trim($reason));

        return match (true) {
            str_contains($reason, 'token invalid'),
            str_contains($reason, 'invalid token') => 'Token Fonnte tidak valid. Periksa API key di Profil CMS.',

            str_contains($reason, 'insufficient quota') => 'Kuota Fonnte habis. Upgrade paket atau tunggu reset bulanan.',

            str_contains($reason, 'target invalid') => 'Nomor WhatsApp tujuan tidak valid.',

            str_contains($reason, 'url invalid'),
            str_contains($reason, 'url unreachable') => 'Lampiran/link tidak bisa diakses Fonnte.',

            str_contains($reason, 'file format') => 'Format file struk tidak didukung Fonnte.',

            str_contains($reason, 'file size') => 'File struk melebihi batas 4 MB Fonnte.',

            str_contains($reason, 'input invalid') => 'Data kirim ke Fonnte tidak valid. Periksa nomor WA dan isi pesan.',

            str_contains($reason, 'devices must belong') => 'Token Fonnte tidak cocok dengan perangkat terdaftar.',

            str_contains($reason, 'timeout'),
            str_contains($reason, 'timed out') => 'Fonnte tidak merespons (timeout). Coba kirim ulang.',

            default => 'Fonnte: '.$reason,
        };
    }

    public static function fromThrowable(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        if ($message === '') {
            return 'Gagal menghubungi Fonnte. Periksa koneksi server.';
        }

        $lower = strtolower($message);

        if (str_contains($lower, 'fonnte') && ! str_starts_with($lower, 'fonnte:')) {
            return $message;
        }

        if (str_contains($lower, 'curl') || str_contains($lower, 'connection')) {
            return 'Tidak bisa terhubung ke Fonnte. Periksa koneksi internet server.';
        }

        return self::fromReason($message);
    }

    public static function isRetryable(string $message): bool
    {
        $normalized = strtolower($message);

        foreach ([
            'token fonnte',
            'kuota fonnte habis',
            'nomor whatsapp',
            'tidak valid',
            'format file',
            'melebihi batas',
            'data kirim',
            'token fonnte tidak cocok',
        ] as $needle) {
            if (str_contains($normalized, $needle)) {
                return false;
            }
        }

        return true;
    }
}
