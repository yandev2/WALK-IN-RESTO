<?php

namespace App\Support;

final class IdrAmount
{
    public static function parse(mixed $input): ?int
    {
        if ($input === null || $input === false) {
            return null;
        }

        if (is_int($input)) {
            return $input >= 0 ? $input : null;
        }

        if (is_float($input)) {
            return $input >= 0 ? (int) round($input) : null;
        }

        $raw = trim((string) $input);

        if ($raw === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if ($digits === '') {
            return null;
        }

        return (int) $digits;
    }
}
