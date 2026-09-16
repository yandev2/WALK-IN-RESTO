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
            'blog',
            'blogger',
            'daftar',
            'export-files',
            'filament',
            'founder',
            'livewire',
            'login',
            'order',
            'robots.txt',
            'sitemap.xml',
            'storage',
            'tentang',
            'syarat-dan-ketentuan',
            'up',
        ];
    }

    public static function isReserved(string $slug): bool
    {
        return in_array(strtolower($slug), self::all(), true);
    }
}
