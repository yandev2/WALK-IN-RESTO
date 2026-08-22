<?php

namespace App\Support;

final class MenuSearch
{
    public static function normalize(string $term): string
    {
        return strtolower(preg_replace('/\s+/', '', $term) ?? '');
    }
}
