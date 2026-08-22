<?php

namespace App\Support;

use App\Models\DiningTable;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\URL;

final class TableQrToken
{
    public static function make(DiningTable $table): string
    {
        $payload = $table->id.'|'.((int) ($table->qr_version ?: 1));
        $signature = hash_hmac('sha256', $payload, $table->qr_secret);

        return rtrim(strtr(base64_encode($payload.'.'.$signature), '+/', '-_'), '=');
    }

    public static function url(DiningTable $table): string
    {
        return URL::to('/order/t/'.self::make($table));
    }

    public static function png(DiningTable $table, int $size = 512): string
    {
        $qrCode = new QrCode(
            data: self::url($table),
            size: $size,
            margin: 16,
        );

        return (new PngWriter)->write($qrCode)->getString();
    }

    public static function pdf(DiningTable $table): string
    {
        $table->loadMissing(['outlet.restaurant']);

        return Pdf::loadView('stickers.table-qr', [
            'table' => $table,
            'png' => base64_encode(self::png($table, 640)),
        ])->setPaper('a6', 'portrait')->output();
    }

    public static function resolve(string $token): ?DiningTable
    {
        $decoded = base64_decode(strtr($token, '-_', '+/'), true);

        if (! is_string($decoded) || ! str_contains($decoded, '.')) {
            return null;
        }

        [$payload, $signature] = explode('.', $decoded, 2);
        $parts = explode('|', $payload);

        if (count($parts) !== 2 || blank($signature)) {
            return null;
        }

        [$tableId, $version] = $parts;

        $table = DiningTable::query()
            ->with(['outlet.restaurant'])
            ->find($tableId);

        if (! $table || (int) $table->qr_version !== (int) $version) {
            return null;
        }

        $expected = hash_hmac('sha256', $payload, $table->qr_secret);

        if (! hash_equals($expected, $signature)) {
            return null;
        }

        return $table;
    }
}
