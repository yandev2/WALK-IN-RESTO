<?php

namespace App\Support;

final class ReservedSlugs
{
    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            'admin',
            'api',
            'daftar',
            'export-files',
            'filament',
            'founder',
            'livewire',
            'login',
            'order',
            'storage',
            'up',
        ];
    }

    public static function isReserved(string $slug): bool
    {
        return in_array(strtolower($slug), self::all(), true);
    }
}
