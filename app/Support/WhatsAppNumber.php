<?php

namespace App\Support;

final class WhatsAppNumber
{
    public static function normalize(?string $input): ?string
    {
        if (blank($input)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $input) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }

    public static function isValid(?string $input): bool
    {
        $normalized = self::normalize($input);

        return is_string($normalized) && preg_match('/^62[0-9]{8,13}$/', $normalized) === 1;
    }

    public static function mask(?string $input): ?string
    {
        if (blank($input)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $input) ?? '';
        $len = strlen($digits);

        if ($len <= 7) {
            return $input;
        }

        $prefix = substr($digits, 0, 4);
        $suffix = substr($digits, -4);
        $starCount = max(3, $len - 8);

        return $prefix . str_repeat('*', $starCount) . $suffix;
    }
}
