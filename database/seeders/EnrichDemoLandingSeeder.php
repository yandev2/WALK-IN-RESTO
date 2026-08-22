<?php

namespace Database\Seeders;

use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsGalleryImage;
use App\Models\CmsProfile;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class EnrichDemoLandingSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::query()->where('slug', 'resto-demo')->first();

        if (! $restaurant) {
            return;
        }

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'headline' => 'Masakan rumahan, meja bebas pilih.',
                'about_html' => '<p>Resto Demo adalah warung makan walk-in. Tamu datang, duduk di meja yang masih kosong, lalu memesan dari HP sendiri lewat stiker QR di meja.</p><p>Tidak ada reservasi di tahap ini. Kalau meja penuh, kami minta Anda menunggu atau kembali sesuai jam buka.</p>',
                'hero_image_path' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80',
                'how_to_image_path' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80',
                'about_image_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1200&q=80',
                'map_embed_url' => 'https://maps.google.com/maps?q=-6.2000000,106.8166667&z=16&output=embed',
                'cta_label' => 'Lihat lokasi',
                'cta_url' => 'https://www.google.com/maps/search/?api=1&query=-6.2000000,106.8166667',
            ],
        );

        CmsBanner::query()->updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'title' => 'Promo weekday: es teh gratis untuk nasi goreng',
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=1400&q=80',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonths(2),
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        $faqs = [
            ['Bagaimana cara memesan?', '<p>Duduk di meja kosong, scan stiker QR, isi nomor WhatsApp, pilih menu, lalu bayar QRIS atau tunai. Kasir yang menerima pembayaran.</p>', 1],
            ['Bisa pesan meja dari HP sekarang?', '<p>Tidak. Tahap ini walk-in saja: datang, duduk, baru scan QR. Reservasi akan menyusul di tahap berikutnya.</p>', 2],
            ['Kalau HP teman ikut pesan?', '<p>Minta PIN 4 digit dari HP yang pertama scan. Jangan buka sesi baru di meja yang sama.</p>', 3],
        ];

        foreach ($faqs as [$question, $answer, $order]) {
            CmsFaq::query()->updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'question' => $question,
                ],
                [
                    'answer_html' => $answer,
                    'sort_order' => $order,
                    'is_active' => true,
                ],
            );
        }

        $gallery = [
            ['https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=80', 'Ruang makan'],
            ['https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80', 'Hidangan utama'],
            ['https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=900&q=80', 'Suasana malam'],
            ['https://images.unsplash.com/photo-1544025162-d766402d3276?auto=format&fit=crop&w=900&q=80', 'Dapur'],
        ];

        foreach ($gallery as $index => [$path, $caption]) {
            CmsGalleryImage::query()->updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'caption' => $caption,
                ],
                [
                    'image_path' => $path,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        MenuItem::query()->where('restaurant_id', $restaurant->id)->where('name', 'Nasi Goreng')->update([
            'photo_path' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
        ]);

        MenuItem::query()->where('restaurant_id', $restaurant->id)->where('name', 'Es Teh Manis')->update([
            'photo_path' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
        ]);
    }
}
