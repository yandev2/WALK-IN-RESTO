<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use App\Support\CmsMedia;
use App\Support\RestaurantTheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PlatformSetting extends Model
{
    use PurgesPublicDiskFiles;

    public const CACHE_KEY = 'platform_setting';

    public const PLACEHOLDER_QR_URL = 'https://picsum.photos/seed/founder-billing-qr/200/200';

    protected $fillable = [
        'primary_color',
        'site_name',
        'logo_path',
        'favicon_path',
        'auth_background_path',
        'hero_eyebrow',
        'hero_title',
        'hero_highlight',
        'hero_subtitle',
        'hero_image_path',
        'cta_register_label',
        'search_placeholder',
        'search_button_label',
        'location_cta_label',
        'footer_about',
        'footer_email',
        'footer_phone',
        'footer_address',
        'footer_instagram',
        'footer_copyright',
        'footer_privacy_url',
        'footer_terms_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image_path',
        'canonical_url',
        'about_title',
        'about_content',
        'terms_title',
        'terms_content',
        'bank_name',
        'bank_holder',
        'bank_account',
        'qr_image_path',
        'trial_days',
        'cashier_commission_percentage',
    ];

    protected function casts(): array
    {
        return [
            'trial_days' => 'integer',
            'cashier_commission_percentage' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $setting): void {
            if (filled($setting->primary_color)) {
                $setting->primary_color = RestaurantTheme::normalizeHex($setting->primary_color)
                    ?? RestaurantTheme::DEFAULT_PRIMARY;
            }
        });

        static::updating(function (self $setting): void {
            foreach (['about_content', 'terms_content'] as $attribute) {
                if ($setting->isDirty($attribute)) {
                    $oldFiles = self::extractRichTextAttachments($setting->getOriginal($attribute));
                    $newFiles = self::extractRichTextAttachments($setting->getAttribute($attribute));
                    $deletedFiles = array_diff($oldFiles, $newFiles);

                    foreach ($deletedFiles as $deletedFile) {
                        CmsMedia::delete($deletedFile);
                    }
                }
            }
        });

        static::deleting(function (self $setting): void {
            foreach (['about_content', 'terms_content'] as $attribute) {
                $files = self::extractRichTextAttachments($setting->getAttribute($attribute));
                foreach ($files as $file) {
                    CmsMedia::delete($file);
                }
            }
        });

        static::saved(fn () => self::forgetCache());
        static::deleted(fn () => self::forgetCache());
    }

    /**
     * Ekstrak file lampiran gambar/media dari konten HTML RichEditor.
     *
     * @return list<string>
     */
    public static function extractRichTextAttachments(?string $html): array
    {
        if (blank($html)) {
            return [];
        }

        preg_match_all('/(?:href|src)=["\'](?:https?:\/\/[^\/"\']+)?(?:\/storage\/)?(platform\/pages\/[^"\']+)["\']/i', $html, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'primary_color' => RestaurantTheme::DEFAULT_PRIMARY,
            'site_name' => 'RestoTerdekat',
            'logo_path' => null,
            'favicon_path' => null,
            'auth_background_path' => null,
            'hero_eyebrow' => 'Walk-in',
            'hero_title' => 'Temukan restoran terdekat & terbaik',
            'hero_highlight' => 'terdekat',
            'hero_subtitle' => 'Jelajahi restoran di sekitar Anda, lihat menu, jam buka, dan langsung datang ke meja pilihan.',
            'hero_image_path' => null,
            'cta_register_label' => 'Daftarkan restoran',
            'search_placeholder' => 'Cari restoran, masakan, atau menu...',
            'search_button_label' => 'Cari',
            'location_cta_label' => 'Izinkan lokasi',
            'footer_about' => '{site} membantu tamu menemukan restoran terdekat dan membantu resto menerima tamu walk-in.',
            'footer_email' => (string) config('subscription.contact_email', 'founder@restoterdekat.id'),
            'footer_phone' => (string) config('subscription.contact_whatsapp', ''),
            'footer_address' => null,
            'footer_instagram' => null,
            'footer_copyright' => '© {year} {site}. Semua hak dilindungi.',
            'footer_privacy_url' => null,
            'footer_terms_url' => null,
            'meta_title' => 'Temukan Restoran Terdekat & Kuliner Terbaik - {site}',
            'meta_description' => 'Jelajahi restoran terbaik dan rekomendasi kuliner terdekat di sekitar Anda. Lihat menu lezat, jam buka, fasilitas, dan langsung pesan dari meja tanpa antre.',
            'meta_keywords' => 'restoran terdekat, kuliner terdekat, menu restoran, cafe terdekat, walk-in resto, pesan dari meja, direktori restoran',
            'og_image_path' => null,
            'canonical_url' => null,
            'about_title' => 'Tentang RestoTerdekat',
            'about_content' => '<h2>Solusi Mudah Menikmati Kuliner Walk-In</h2><p><strong>RestoTerdekat</strong> adalah platform direktori kuliner modern yang dirancang untuk menghubungkan pecinta kuliner dengan berbagai restoran terbaik di sekitar mereka secara instan dan tanpa ribet.</p><p>Kami memahami bahwa pengalaman bersantap yang menyenangkan berawal dari kemudahan. Melalui RestoTerdekat, Anda dapat dengan mudah:</p><ul><li><strong>Menemukan Restoran Terdekat</strong>: Mencari restoran pilihan berdasarkan jarak, kategori masakan, atau fasilitas yang tersedia.</li><li><strong>Melihat Menu &amp; Jam Buka</strong>: Mengetahui hidangan populer, harga terkini, dan status operasional restoran secara real-time.</li><li><strong>Dine-In Walk-In Tanpa Reservasi</strong>: Datang langsung, duduk di meja pilihan Anda, dan nikmati kemudahan memesan dari meja dengan scan stiker QR.</li></ul><p>Bagi pemilik restoran, kami menyediakan sistem operasional digital terintegrasi—mulai dari Kitchen Display System (KDS), kasir POS, hingga pemesanan mandiri oleh pelanggan—untuk meningkatkan efisiensi dan kepuasan tamu.</p>',
            'terms_title' => 'Syarat & Ketentuan Layanan',
            'terms_content' => '<h2>Ketentuan Penggunaan Platform</h2><p>Selamat datang di <strong>RestoTerdekat</strong>. Dengan mengakses dan menggunakan situs web serta layanan kami, Anda menyetujui untuk terikat dengan Syarat dan Ketentuan berikut ini.</p><h3>1. Layanan Direktori &amp; Walk-In</h3><p>RestoTerdekat menyediakan informasi restoran, daftar menu, jam buka, serta layanan pemesanan mandiri meja (walk-in dining). Kami senantiasa berupaya menyajikan informasi yang akurat dan terkini, namun ketersediaan menu dan harga sewaktu-waktu dapat berubah sesuai kebijakan masing-masing restoran mitra.</p><h3>2. Pemesanan &amp; Pembayaran</h3><p>Tamu dapat melakukan pemesanan langsung dari meja melalui pemindaian stiker QR resmi restoran. Pembayaran dapat dilakukan melalui metode yang disediakan oleh restoran, baik secara tunai di kasir maupun melalui pembayaran digital (QRIS/Transfer Bank).</p><h3>3. Kewajiban Pengguna</h3><ul><li>Pengguna wajib memberikan informasi yang benar saat melakukan pemesanan atau pendaftaran.</li><li>Pengguna dilarang menyalahgunakan sistem pemesanan QR, termasuk membuat pesanan palsu atau mengganggu operasional restoran.</li><li>Pengguna bertanggung jawab atas pesanan yang telah dikonfirmasi di meja restoran.</li></ul><h3>4. Pembaharuan Ketentuan</h3><p>Kami berhak untuk memperbarui Syarat dan Ketentuan ini sewaktu-waktu guna meningkatkan kualitas layanan. Perubahan akan berlaku efektif setelah diterbitkan pada halaman ini.</p>',
            'bank_name' => (string) config('subscription.bank_name', 'BCA'),
            'bank_holder' => (string) config('subscription.bank_holder', 'RestoTerdekat'),
            'bank_account' => (string) config('subscription.bank_account', '0000000000'),
            'qr_image_path' => null,
            'trial_days' => (int) config('subscription.trial_days', 30),
            'cashier_commission_percentage' => 10.00,
        ];
    }

    public static function optional(): ?self
    {
        try {
            if (! Schema::hasTable('platform_settings')) {
                return null;
            }

            if (! app()->environment('testing') && Cache::has(self::CACHE_KEY)) {
                $cached = Cache::get(self::CACHE_KEY);

                return $cached instanceof self ? $cached : null;
            }

            $row = static::query()->first();

            if ($row instanceof self && ! app()->environment('testing')) {
                Cache::forever(self::CACHE_KEY, $row);
            }

            return $row;
        } catch (Throwable) {
            return null;
        }
    }

    public static function current(): self
    {
        $existing = static::optional();

        if ($existing instanceof self) {
            return $existing;
        }

        $created = static::query()->create(static::defaults());
        self::forgetCache();

        return $created;
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function trialDays(): int
    {
        $fallback = max(1, (int) config('subscription.trial_days', 30));
        $value = static::optional()?->trial_days;

        if ($value === null || (int) $value < 1) {
            return $fallback;
        }

        return min(365, (int) $value);
    }

    public static function cashierCommissionPercentage(): float
    {
        $value = static::optional()?->cashier_commission_percentage;

        if ($value === null || (float) $value < 0) {
            return 10.00;
        }

        return (float) $value;
    }

    /**
     * @return array<string, mixed>
     */
    public static function homeViewData(): array
    {
        $defaults = static::defaults();
        $row = static::optional();
        $data = [];

        foreach ($defaults as $key => $fallback) {
            $value = $row?->getAttribute($key);
            $data[$key] = filled($value) ? $value : $fallback;
        }

        $data['hero_image_url'] = CmsMedia::url($row?->hero_image_path);
        $data['logo_url'] = CmsMedia::url($row?->logo_path);
        $data['favicon_url'] = CmsMedia::url($row?->favicon_path) ?: $data['logo_url'];
        $data['og_image_url'] = CmsMedia::url($row?->og_image_path) ?: $data['hero_image_url'] ?: $data['logo_url'];
        $data['auth_background_url'] = CmsMedia::url($row?->auth_background_path);

        $siteName = (string) ($data['site_name'] ?? '');
        foreach (['footer_about', 'footer_copyright', 'meta_title', 'meta_description'] as $tokenField) {
            $data[$tokenField] = self::applyFooterTokens((string) ($data[$tokenField] ?? ''), $siteName);
        }

        $data['canonical_url'] = filled($row?->canonical_url) ? (string) $row->canonical_url : (url()->current());
        $data['footer_whatsapp_url'] = CmsMedia::whatsappUrl($data['footer_phone'] ?? null);
        $data['footer_instagram_url'] = CmsMedia::instagramUrl($data['footer_instagram'] ?? null);

        return $data;
    }

    public static function applyFooterTokens(string $text, string $siteName): string
    {
        return str_replace(
            ['{year}', '{site}'],
            [(string) now()->year, $siteName],
            $text,
        );
    }

    /**
     * @return array{bankName: string, bankAccount: string, bankHolder: string, contactEmail: string|null, qrUrl: string}
     */
    public static function billingViewData(): array
    {
        $row = static::optional();
        $defaults = static::defaults();

        return [
            'bankName' => filled($row?->bank_name) ? (string) $row->bank_name : $defaults['bank_name'],
            'bankAccount' => filled($row?->bank_account) ? (string) $row->bank_account : $defaults['bank_account'],
            'bankHolder' => filled($row?->bank_holder) ? (string) $row->bank_holder : $defaults['bank_holder'],
            'contactEmail' => filled($row?->footer_email)
                ? (string) $row->footer_email
                : config('subscription.contact_email'),
            'qrUrl' => CmsMedia::url($row?->qr_image_path) ?: self::PLACEHOLDER_QR_URL,
        ];
    }

    public static function heroTitleHtml(array $home): string
    {
        $title = (string) ($home['hero_title'] ?? '');
        $highlight = (string) ($home['hero_highlight'] ?? '');

        if ($title === '') {
            return '';
        }

        if ($highlight === '' || ! str_contains($title, $highlight)) {
            return e($title);
        }

        $escapedTitle = e($title);
        $escapedHighlight = e($highlight);
        $pos = strpos($escapedTitle, $escapedHighlight);

        if ($pos === false) {
            return $escapedTitle;
        }

        return substr($escapedTitle, 0, $pos)
            .'<span class="text-primary">'.$escapedHighlight.'</span>'
            .substr($escapedTitle, $pos + strlen($escapedHighlight));
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['hero_image_path', 'logo_path', 'favicon_path', 'og_image_path', 'qr_image_path', 'auth_background_path'];
    }
}
