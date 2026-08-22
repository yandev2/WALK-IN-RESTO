<?php

namespace App\Support;

use App\Models\CmsProfile;
use Illuminate\Support\Arr;

final class LandingLayout
{
    /**
     * @var list<string>
     */
    public const SECTION_IDS = [
        'hero',
        'banners',
        'menu',
        'reviews',
        'how_to',
        'about',
        'gallery',
        'hours',
        'location',
        'faq',
        'cta',
    ];

    /**
     * @var array<string, string>
     */
    public const ANCHORS = [
        'hero' => 'atas',
        'banners' => 'promo',
        'menu' => 'menu',
        'reviews' => 'ulasan',
        'how_to' => 'cara-pesan',
        'about' => 'tentang',
        'gallery' => 'galeri',
        'hours' => 'jam',
        'location' => 'lokasi',
        'faq' => 'faq',
        'cta' => 'cta',
    ];

    /**
     * @var array<string, string>
     */
    public const ADMIN_LABELS = [
        'hero' => 'Hero',
        'banners' => 'Promo',
        'menu' => 'Menu',
        'reviews' => 'Ulasan',
        'how_to' => 'Cara pesan',
        'about' => 'Tentang',
        'gallery' => 'Galeri',
        'hours' => 'Jam operasional',
        'location' => 'Lokasi',
        'faq' => 'FAQ',
        'cta' => 'CTA penutup',
    ];

    /**
     * @var list<string>
     */
    public const NAV_IDS = ['menu', 'how_to', 'hours', 'gallery', 'location', 'faq'];

    /**
     * @param  list<array{id: string, enabled: bool}>  $sections
     * @param  array<string, array<string, mixed>>  $copy
     */
    public function __construct(
        public readonly array $sections,
        public readonly array $copy,
    ) {}

    public static function for(?CmsProfile $profile): self
    {
        return new self(
            self::normalizeSections($profile?->landing_sections),
            self::mergeCopy(self::defaultCopy(), is_array($profile?->landing_copy) ? $profile->landing_copy : []),
        );
    }

    /**
     * @return list<array{id: string, enabled: bool}>
     */
    public static function defaultSections(): array
    {
        return array_map(
            fn (string $id): array => ['id' => $id, 'enabled' => true],
            self::SECTION_IDS,
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function defaultCopy(): array
    {
        return [
            'hero' => [
                'nav' => 'Beranda',
                'pill' => 'Walk-in · tanpa reservasi',
                'subtitle' => 'Datang, duduk di meja pilihan Anda, lalu scan stiker QR untuk memesan dari HP.',
                'secondary_cta' => 'Cara pesan',
            ],
            'banners' => [
                'nav' => 'Promo',
                'label' => 'Promo',
                'title' => 'Penawaran spesial menanti Anda',
                'highlight' => 'spesial',
                'button' => 'Lihat semua',
            ],
            'menu' => [
                'nav' => 'Menu',
                'label' => 'Hidangan populer',
                'title' => 'Hidangan populer hari ini',
                'highlight' => 'populer',
                'subtitle' => 'Pesan lengkap dari HP setelah scan QR meja.',
                'button' => 'Lihat semua daftar menu',
            ],
            'reviews' => [
                'nav' => 'Ulasan',
                'label' => 'Testimoni',
                'title' => 'Apa kata pelanggan kami',
                'highlight' => 'pelanggan',
            ],
            'how_to' => [
                'nav' => 'Cara pesan',
                'label' => 'Mudah dipesan',
                'title' => 'Walk-in, scan, bayar',
                'highlight' => 'scan',
                'subtitle' => 'Tidak ada tombol pesan meja dari HP. Datang langsung ke restoran.',
                'steps' => [
                    ['title' => 'Datang', 'description' => 'Langsung ke resto. Tidak perlu booking atau DP.'],
                    ['title' => 'Duduk', 'description' => 'Pilih meja kosong yang Anda suka, lalu duduk.'],
                    ['title' => 'Scan QR', 'description' => 'Isi nomor WhatsApp, pilih menu dari HP.'],
                    ['title' => 'Bayar', 'description' => 'QRIS atau tunai. Kasir yang menerima pembayaran.'],
                ],
            ],
            'about' => [
                'nav' => 'Tentang',
                'label' => 'Tentang kami',
                'title' => 'Cerita di balik dapur',
                'highlight' => 'dapur',
            ],
            'gallery' => [
                'nav' => 'Galeri',
                'label' => 'Suasana',
                'title' => 'Suasana resto',
                'highlight' => 'resto',
            ],
            'hours' => [
                'nav' => 'Jam buka',
                'label' => 'Jam operasional',
                'title' => 'Kapan kami buka',
                'highlight' => 'buka',
            ],
            'location' => [
                'nav' => 'Lokasi',
                'label' => 'Kunjungi kami',
                'title' => 'Datang ke sini',
                'highlight' => 'sini',
                'button' => 'Buka di Google Maps',
            ],
            'faq' => [
                'nav' => 'FAQ',
                'label' => 'FAQ',
                'title' => 'Pertanyaan tamu',
                'highlight' => 'tamu',
            ],
            'cta' => [
                'title' => 'Siap datang ke {name}?',
                'subtitle' => 'Walk-in saja. Pilih meja kosong, scan QR, pesan dari HP.',
                'button' => 'Lihat lokasi',
                'wa_button' => 'WhatsApp kami',
                'footer_tagline' => 'Walk-in only. Datang, duduk, scan QR.',
                'footer_nav' => 'Navigasi',
                'footer_contact' => 'Kontak',
                'footer_visit' => 'Datang ke sini',
                'footer_maps' => 'Buka Google Maps',
                'footer_home' => 'Beranda',
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>|null  $stored
     * @return list<array{id: string, enabled: bool}>
     */
    public static function normalizeSections(?array $stored): array
    {
        $ordered = [];
        $seen = [];

        foreach ($stored ?? [] as $row) {
            $id = is_array($row) ? ($row['id'] ?? null) : null;

            if (! is_string($id) || ! in_array($id, self::SECTION_IDS, true) || isset($seen[$id])) {
                continue;
            }

            $seen[$id] = true;
            $ordered[] = [
                'id' => $id,
                'enabled' => (bool) ($row['enabled'] ?? true),
            ];
        }

        foreach (self::SECTION_IDS as $id) {
            if (isset($seen[$id])) {
                continue;
            }

            $ordered[] = ['id' => $id, 'enabled' => true];
        }

        return $ordered;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function formItems(?CmsProfile $profile): array
    {
        $layout = self::for($profile);

        return array_map(
            fn (array $section): array => [
                'id' => $section['id'],
                'enabled' => $section['enabled'],
                ...$layout->copyFor($section['id']),
            ],
            $layout->sections,
        );
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return array{landing_sections: list<array{id: string, enabled: bool}>, landing_copy: array<string, array<string, mixed>>}
     */
    public static function persistFromForm(array $items): array
    {
        $sections = [];
        $copy = [];
        $seen = [];

        foreach ($items as $item) {
            $id = $item['id'] ?? null;

            if (! is_string($id) || ! in_array($id, self::SECTION_IDS, true) || isset($seen[$id])) {
                continue;
            }

            $seen[$id] = true;
            $sections[] = [
                'id' => $id,
                'enabled' => (bool) ($item['enabled'] ?? true),
            ];
            $copy[$id] = Arr::except($item, ['id', 'enabled']);
        }

        foreach (self::SECTION_IDS as $id) {
            if (isset($seen[$id])) {
                continue;
            }

            $sections[] = ['id' => $id, 'enabled' => true];
        }

        return [
            'landing_sections' => $sections,
            'landing_copy' => $copy,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function copyFor(string $id): array
    {
        return $this->copy[$id] ?? [];
    }

    /**
     * @param  array<string, bool>  $available
     * @return list<string>
     */
    public function visibleIds(array $available): array
    {
        $ids = [];

        foreach ($this->sections as $section) {
            $id = $section['id'];

            if (! ($section['enabled'] ?? true)) {
                continue;
            }

            if (! ($available[$id] ?? false)) {
                continue;
            }

            $ids[] = $id;
        }

        return $ids;
    }

    public function isVisible(string $id, array $visibleIds): bool
    {
        return in_array($id, $visibleIds, true);
    }

    /**
     * @return array{sections: list<array{id: string, enabled: bool}>, copy: array<string, array<string, mixed>>}
     */
    public function toArray(): array
    {
        return [
            'sections' => $this->sections,
            'copy' => $this->copy,
        ];
    }

    public static function interpolate(string $text, string $name): string
    {
        return str_replace('{name}', $name, $text);
    }

    /**
     * @param  array<string, array<string, mixed>>  $defaults
     * @param  array<string, mixed>  $overrides
     * @return array<string, array<string, mixed>>
     */
    private static function mergeCopy(array $defaults, array $overrides): array
    {
        $merged = $defaults;

        foreach ($overrides as $id => $fields) {
            if (! is_string($id) || ! isset($merged[$id]) || ! is_array($fields)) {
                continue;
            }

            foreach ($fields as $key => $value) {
                if ($key === 'steps' && is_array($value)) {
                    $merged[$id]['steps'] = self::mergeSteps($defaults[$id]['steps'] ?? [], $value);

                    continue;
                }

                if (is_string($value) && trim($value) !== '') {
                    $merged[$id][$key] = $value;
                }
            }
        }

        return $merged;
    }

    /**
     * @param  list<array{title: string, description: string}>  $defaults
     * @param  list<array<string, mixed>>  $overrides
     * @return list<array{title: string, description: string}>
     */
    private static function mergeSteps(array $defaults, array $overrides): array
    {
        $steps = [];

        foreach ($defaults as $index => $step) {
            $override = $overrides[$index] ?? [];
            $steps[] = [
                'title' => filled($override['title'] ?? null) ? (string) $override['title'] : $step['title'],
                'description' => filled($override['description'] ?? null)
                    ? (string) $override['description']
                    : $step['description'],
            ];
        }

        return $steps;
    }
}
