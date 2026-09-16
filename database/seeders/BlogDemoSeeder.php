<?php

namespace Database\Seeders;

use App\Enums\BlogCommentStatus;
use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use App\Models\VisitLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class BlogDemoSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->setPermissionsTeamId(0);

        // 1. Pastikan role blogger dan founder tersedia
        Role::findOrCreate('founder', 'web');
        Role::findOrCreate('blogger', 'web');

        // 2. Akun Penulis 1: Chef Budi Santoso
        $blogger1 = User::firstOrCreate(
            ['email' => 'budi.blogger@walk-in-resto.test'],
            [
                'name' => 'Chef Budi Santoso',
                'username' => 'chefbudi',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $blogger1->hasRole('blogger')) {
            $blogger1->assignRole('blogger');
        }

        // 3. Akun Penulis 2: Siti Rahma
        $blogger2 = User::firstOrCreate(
            ['email' => 'siti.blogger@walk-in-resto.test'],
            [
                'name' => 'Siti Rahma',
                'username' => 'sitirahma',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $blogger2->hasRole('blogger')) {
            $blogger2->assignRole('blogger');
        }

        // Pastikan akun founder ada sebagai pembanding
        $founder = User::whereHasGlobalRole('founder')->first()
            ?? User::firstOrCreate(
                ['email' => 'founder@walk-in-resto.test'],
                [
                    'name' => 'Founder Walk-In Resto',
                    'username' => 'founder',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
        if (! $founder->hasRole('founder')) {
            $founder->assignRole('founder');
        }

        // 4. Kategori Blog
        $categoriesData = [
            [
                'id_name' => 'Kuliner Nusantara',
                'id_slug' => 'kuliner-nusantara',
                'en_name' => 'Indonesian Culinary',
                'en_slug' => 'indonesian-culinary',
                'sort' => 1,
            ],
            [
                'id_name' => 'Tips Bisnis Resto',
                'id_slug' => 'tips-bisnis-resto',
                'en_name' => 'Restaurant Business Tips',
                'en_slug' => 'restaurant-business-tips',
                'sort' => 2,
            ],
            [
                'id_name' => 'Resep & Inovasi Menu',
                'id_slug' => 'resep-inovasi-menu',
                'en_name' => 'Recipes & Menu Innovations',
                'en_slug' => 'recipes-menu-innovations',
                'sort' => 3,
            ],
            [
                'id_name' => 'Tren Kafe & Kopi',
                'id_slug' => 'tren-kafe-kopi',
                'en_name' => 'Coffee & Cafe Trends',
                'en_slug' => 'coffee-cafe-trends',
                'sort' => 4,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $cat = BlogCategory::whereTranslation('slug', $catData['id_slug'], 'id')->first();
            if (! $cat) {
                $cat = BlogCategory::create([
                    'sort_order' => $catData['sort'],
                    'is_active' => true,
                ]);
                $cat->translations()->create([
                    'locale' => 'id',
                    'name' => $catData['id_name'],
                    'slug' => $catData['id_slug'],
                    'meta_title' => $catData['id_name'],
                    'meta_description' => "Artikel seputar {$catData['id_name']}",
                ]);
                $cat->translations()->create([
                    'locale' => 'en',
                    'name' => $catData['en_name'],
                    'slug' => $catData['en_slug'],
                    'meta_title' => $catData['en_name'],
                    'meta_description' => "Articles about {$catData['en_name']}",
                ]);
            }
            $categories[$catData['id_slug']] = $cat;
        }

        // 5. Tag Blog
        $tagsData = [
            ['id_name' => 'Rendang', 'id_slug' => 'rendang', 'en_name' => 'Rendang', 'en_slug' => 'rendang'],
            ['id_name' => 'Kopi Spesialti', 'id_slug' => 'kopi-spesialti', 'en_name' => 'Specialty Coffee', 'en_slug' => 'specialty-coffee'],
            ['id_name' => 'SOP Restoran', 'id_slug' => 'sop-restoran', 'en_name' => 'Restaurant SOP', 'en_slug' => 'restaurant-sop'],
            ['id_name' => 'Food Plating', 'id_slug' => 'food-plating', 'en_name' => 'Food Plating', 'en_slug' => 'food-plating'],
            ['id_name' => 'Menu Viral', 'id_slug' => 'menu-viral', 'en_name' => 'Viral Menus', 'en_slug' => 'viral-menus'],
            ['id_name' => 'Strategi Kasir', 'id_slug' => 'strategi-kasir', 'en_name' => 'Cashier Strategy', 'en_slug' => 'cashier-strategy'],
        ];

        $tags = [];
        foreach ($tagsData as $tData) {
            $tag = BlogTag::whereTranslation('slug', $tData['id_slug'], 'id')->first();
            if (! $tag) {
                $tag = BlogTag::create([
                    'sort_order' => 1,
                    'is_active' => true,
                ]);
                $tag->translations()->create([
                    'locale' => 'id',
                    'name' => $tData['id_name'],
                    'slug' => $tData['id_slug'],
                ]);
                $tag->translations()->create([
                    'locale' => 'en',
                    'name' => $tData['en_name'],
                    'slug' => $tData['en_slug'],
                ]);
            }
            $tags[$tData['id_slug']] = $tag;
        }

        // 6. Definisi Artikel untuk Masing-Masing Penulis
        $postsData = [
            // --- Artikel Chef Budi Santoso (blogger1) ---
            [
                'author' => $blogger1,
                'category_slug' => 'kuliner-nusantara',
                'tags' => ['rendang', 'menu-viral'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(24),
                'reading_time' => 6,
                'views' => 680,
                'likes' => 74,
                'id_title' => 'Rahasia Meracik Bumbu Rendang Autentik dengan Daging Super Empuk',
                'id_slug' => 'rahasia-meracik-bumbu-rendang-autentik-super-empuk',
                'id_excerpt' => 'Pelajari teknik memasak rendang tradisional Minang dengan bumbu rempah pilihan yang meresap hingga serat daging terdalam.',
                'en_title' => 'Secrets to Authentic Minang Rendang with Melt-in-Your-Mouth Beef',
                'en_slug' => 'secrets-authentic-minang-rendang-tender-beef',
                'en_excerpt' => 'Master the traditional Minang technique for rich, deeply caramelized slow-cooked beef rendang.',
            ],
            [
                'author' => $blogger1,
                'category_slug' => 'tips-bisnis-resto',
                'tags' => ['sop-restoran', 'strategi-kasir'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(17),
                'reading_time' => 5,
                'views' => 520,
                'likes' => 58,
                'id_title' => '5 Kesalahan Fatal Saat Menghitung HPP Makanan Restoran',
                'id_slug' => '5-kesalahan-fatal-menghitung-hpp-makanan-restoran',
                'id_excerpt' => 'Salah menghitung waste dan susut saat memasak bisa menghabiskan margin profit restoran Anda tanpa disadari.',
                'en_title' => '5 Critical Mistakes When Calculating Food Cost in Restaurants',
                'en_slug' => '5-critical-mistakes-calculating-food-cost-restaurants',
                'en_excerpt' => 'Failing to account for shrinkage and prep waste can silently drain your restaurant profit margins.',
            ],
            [
                'author' => $blogger1,
                'category_slug' => 'resep-inovasi-menu',
                'tags' => ['menu-viral', 'food-plating'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(9),
                'reading_time' => 4,
                'views' => 340,
                'likes' => 42,
                'id_title' => 'Eksplorasi Street Food Tradisional yang Sukses Naik Kelas ke Restoran Mewah',
                'id_slug' => 'eksplorasi-street-food-tradisional-naik-kelas',
                'id_excerpt' => 'Bagaimana sentuhan plating modern dan higienitas tinggi mengubah jajanan kaki lima menjadi menu best seller premium.',
                'en_title' => 'How Traditional Street Foods Elevated to Fine Dining Menus',
                'en_slug' => 'traditional-street-foods-elevated-fine-dining',
                'en_excerpt' => 'Discover how modern plating techniques turn beloved street foods into high-margin signatures.',
            ],
            [
                'author' => $blogger1,
                'category_slug' => 'tips-bisnis-resto',
                'tags' => ['sop-restoran'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(3),
                'reading_time' => 4,
                'views' => 210,
                'likes' => 28,
                'id_title' => 'Standar Higienitas Dapur Profesional: Panduan Praktis Checklist Harian',
                'id_slug' => 'standar-higienitas-dapur-profesional-checklist-harian',
                'id_excerpt' => 'Checklist sanitasi yang mudah diterapkan chef dan kru kitchen untuk menjaga standar kebersihan tertinggi.',
                'en_title' => 'Professional Kitchen Hygiene: Practical Daily Checklist',
                'en_slug' => 'professional-kitchen-hygiene-daily-checklist',
                'en_excerpt' => 'An actionable sanitation checklist for kitchen crews to maintain food safety and health compliance.',
            ],
            [
                'author' => $blogger1,
                'category_slug' => 'kuliner-nusantara',
                'tags' => ['menu-viral'],
                'status' => BlogPostStatus::Scheduled,
                'published_at' => now()->addDays(5),
                'reading_time' => 5,
                'views' => 0,
                'likes' => 0,
                'id_title' => 'Tren Sambal Nusantara 2026: Dari Matah Hingga Sambal Gami Bakar',
                'id_slug' => 'tren-sambal-nusantara-2026-matah-gami',
                'id_excerpt' => 'Melihat evolusi sambal tradisional di kalangan pecinta kuliner pedas nusantara.',
                'en_title' => 'Indonesian Sambal Trends: From Raw Matah to Sizzling Cobek Gami',
                'en_slug' => 'indonesian-sambal-trends-matah-gami',
                'en_excerpt' => 'The cultural resurgence of spicy local hot relish in modern gastronomy.',
            ],
            [
                'author' => $blogger1,
                'category_slug' => 'resep-inovasi-menu',
                'tags' => ['food-plating'],
                'status' => BlogPostStatus::Draft,
                'published_at' => null,
                'reading_time' => 3,
                'views' => 0,
                'likes' => 0,
                'id_title' => '[Draft] Resep Sarapan Seimbang Cepat Saji untuk Resto Urban',
                'id_slug' => 'draft-resep-sarapan-seimbang-cepat-saji',
                'id_excerpt' => 'Konsep menu sarapan padat gizi yang siap disajikan di bawah 5 menit.',
                'en_title' => '[Draft] Quick & Balanced Breakfast Concepts for Urban Diners',
                'en_slug' => 'draft-quick-balanced-breakfast-concepts',
                'en_excerpt' => 'High-nutrition breakfast options prepared under 5 minutes.',
            ],

            // --- Artikel Siti Rahma (blogger2) ---
            [
                'author' => $blogger2,
                'category_slug' => 'tren-kafe-kopi',
                'tags' => ['kopi-spesialti', 'strategi-kasir'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(21),
                'reading_time' => 7,
                'views' => 610,
                'likes' => 65,
                'id_title' => 'Manual Brew vs Mesin Espresso: Mana Investasi Terbaik untuk Kafe Pemula?',
                'id_slug' => 'manual-brew-vs-mesin-espresso-investasi-kafe-pemula',
                'id_excerpt' => 'Komparasi mendalam antara biaya modal mesin komersial versus ritual seduh manual yang dicari penikmat kopi spesialti.',
                'en_title' => 'Manual Brew vs Commercial Espresso: Which Yields Better ROI for New Cafes?',
                'en_slug' => 'manual-brew-vs-espresso-roi-new-cafes',
                'en_excerpt' => 'A detailed breakdown of equipment CapEx, drink margins, and customer speed-of-service.',
            ],
            [
                'author' => $blogger2,
                'category_slug' => 'tips-bisnis-resto',
                'tags' => ['food-plating', 'menu-viral'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(13),
                'reading_time' => 5,
                'views' => 450,
                'likes' => 51,
                'id_title' => 'Trik Foto Makanan Menggunakan HP: Menu di Aplikasi Jadi 2x Lebih Menggiurkan',
                'id_slug' => 'trik-foto-makanan-pakai-hp-menu-lebih-menggiurkan',
                'id_excerpt' => 'Manfaatkan pencahayaan jendela alami, sudut 45 derajat, dan props minimalis untuk foto menu profesional tanpa kamera mahal.',
                'en_title' => 'Smartphone Food Photography: Double Your Online Menu Conversions',
                'en_slug' => 'smartphone-food-photography-double-conversions',
                'en_excerpt' => 'Harness window light, 45-degree angles, and simple garnishes for studio-quality menu shots.',
            ],
            [
                'author' => $blogger2,
                'category_slug' => 'tren-kafe-kopi',
                'tags' => ['menu-viral'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(7),
                'reading_time' => 4,
                'views' => 310,
                'likes' => 37,
                'id_title' => 'Membangun Vibe Aesthetic Kafe Minimalis yang Ramah Konten TikTok & Instagram',
                'id_slug' => 'membangun-vibe-aesthetic-kafe-minimalis-ramah-konten',
                'id_excerpt' => 'Ciptakan spot foto instagenic dan alur duduk yang membuat pengunjung betah nongkrong sekaligus mempromosikan tempat Anda secara organik.',
                'en_title' => 'Designing an Instagrammable Aesthetic Cafe on a Realistic Budget',
                'en_slug' => 'designing-instagrammable-aesthetic-cafe-budget',
                'en_excerpt' => 'Turn interior corners into free word-of-mouth marketing magnets for Gen Z customers.',
            ],
            [
                'author' => $blogger2,
                'category_slug' => 'resep-inovasi-menu',
                'tags' => ['menu-viral'],
                'status' => BlogPostStatus::Published,
                'published_at' => now()->subDays(2),
                'reading_time' => 3,
                'views' => 170,
                'likes' => 24,
                'id_title' => 'Racikan Mocktail Buah Segar yang Laris Manis di Jam Panas Terik Siang Hari',
                'id_slug' => 'racikan-mocktail-buah-segar-laris-manis-siang-hari',
                'id_excerpt' => 'Paduan sirup rempah dingin, soda, dan ekstrak buah tropis yang memberikan margin kotor di atas 70%.',
                'en_title' => 'Refreshing Fruit Mocktail Recipes That Sell Out During Peak Afternoon Heat',
                'en_slug' => 'fruit-mocktail-recipes-peak-afternoon-heat',
                'en_excerpt' => 'Tropical botanicals and sparkling infusions that offer 70%+ gross margins.',
            ],
            [
                'author' => $blogger2,
                'category_slug' => 'tren-kafe-kopi',
                'tags' => ['kopi-spesialti'],
                'status' => BlogPostStatus::Draft,
                'published_at' => null,
                'reading_time' => 4,
                'views' => 0,
                'likes' => 0,
                'id_title' => '[Draft] Panduan Kalibrasi Grinder Kopi Setiap Pagi',
                'id_slug' => 'draft-panduan-kalibrasi-grinder-kopi-setiap-pagi',
                'id_excerpt' => 'Langkah cepat menyetel grind size untuk mendapatkan extraction yield ideal.',
                'en_title' => '[Draft] Morning Coffee Grinder Dial-In Routine',
                'en_slug' => 'draft-morning-coffee-grinder-dial-in',
                'en_excerpt' => 'A rapid routine to dial in espresso extraction yield every morning.',
            ],
        ];

        $createdPosts = [];
        foreach ($postsData as $pData) {
            $category = $categories[$pData['category_slug']] ?? null;
            if (! $category) {
                continue;
            }

            $existing = BlogPost::whereTranslation('slug', $pData['id_slug'], 'id')->first();
            if ($existing) {
                $createdPosts[] = $existing;
                continue;
            }

            $post = BlogPost::create([
                'blog_category_id' => $category->id,
                'author_id' => $pData['author']->id,
                'status' => $pData['status'],
                'is_active' => true,
                'is_featured' => $pData['status'] === BlogPostStatus::Published,
                'published_at' => $pData['published_at'],
                'reading_time_minutes' => $pData['reading_time'],
                'views_count' => $pData['views'],
                'likes_count' => $pData['likes'],
                'comments_count' => 0,
            ]);

            $post->translations()->create([
                'locale' => 'id',
                'title' => $pData['id_title'],
                'slug' => $pData['id_slug'],
                'excerpt' => $pData['id_excerpt'],
                'content' => "<p>{$pData['id_excerpt']}</p><p>Panduan lengkap ini ditulis oleh praktisi terpercaya untuk membantu Anda mengembangkan wawasan bisnis dan seni kuliner secara berkelanjutan.</p>",
                'meta_title' => $pData['id_title'],
                'meta_description' => $pData['id_excerpt'],
            ]);

            $post->translations()->create([
                'locale' => 'en',
                'title' => $pData['en_title'],
                'slug' => $pData['en_slug'],
                'excerpt' => $pData['en_excerpt'],
                'content' => "<p>{$pData['en_excerpt']}</p><p>This comprehensive guide is prepared by verified industry practitioners to help elevate your culinary and business operations.</p>",
                'meta_title' => $pData['en_title'],
                'meta_description' => $pData['en_excerpt'],
            ]);

            // Hubungkan tag
            $tagIds = [];
            foreach ($pData['tags'] as $tSlug) {
                if (isset($tags[$tSlug])) {
                    $tagIds[] = $tags[$tSlug]->id;
                }
            }
            if (! empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }

            $createdPosts[] = $post;
        }

        // 7. Seeder Komentar Blog (Interaksi)
        $commentsData = [
            ['author' => 'Andi Wijaya', 'email' => 'andi@gmail.com', 'content' => 'Penjelasan mengenai susut bahan dan HPP sangat membuka mata! Terima kasih chef.'],
            ['author' => 'Rina Kartika', 'email' => 'rina@outlook.com', 'content' => 'Resep rendangnya langsung saya coba di rumah, bumbunya mantap meresap sempurna.'],
            ['author' => 'Fajar Pratama', 'email' => 'fajar@yahoo.com', 'content' => 'Tips pencahayaan foto makanan pakai HP ini benar-benar praktis tanpa perlu sewa fotografer mahal.'],
            ['author' => 'Dewi Anggraini', 'email' => 'dewi@gmail.com', 'content' => 'Untuk kafe kecil, memang manual brew jauh lebih ramah di kantong saat awal buka.'],
            ['author' => 'Hendra Setiawan', 'email' => 'hendra@resto.co.id', 'content' => 'Checklist sanitasi dapurnya sudah saya print dan tempel di area preparation. Sangat membantu.'],
        ];

        $publishedPosts = collect($createdPosts)->filter(fn ($p) => $p->status === BlogPostStatus::Published)->values();

        if ($publishedPosts->isNotEmpty()) {
            foreach ($commentsData as $idx => $cData) {
                $targetPost = $publishedPosts[$idx % $publishedPosts->count()];
                BlogComment::firstOrCreate(
                    [
                        'blog_post_id' => $targetPost->id,
                        'author_email' => $cData['email'],
                    ],
                    [
                        'author_name' => $cData['author'],
                        'content' => $cData['content'],
                        'status' => BlogCommentStatus::Approved,
                        'approved_at' => now()->subDays($idx * 3),
                        'approved_by' => $founder->id,
                        'ip_address' => '127.0.0.' . ($idx + 10),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    ]
                );
            }

            // Sync comments count
            foreach ($publishedPosts as $p) {
                $p->update(['comments_count' => $p->comments()->where('status', BlogCommentStatus::Approved)->count()]);
            }
        }

        // 8. Seeder Log Kunjungan (VisitLog) Selama 30 Hari Terakhir
        // Ini memastikan chart tren harian, persentase pertumbuhan (7 & 30 hari), donut kategori, dan statistik perangkat terisi penuh.
        $referrers = [
            'https://www.google.com/search?q=tips+bisnis+resto',
            'https://www.google.com/search?q=resep+rendang+empuk',
            'https://l.instagram.com/',
            'https://api.whatsapp.com/send',
            'https://t.co/resto-trend',
            'https://www.facebook.com/',
            null, // Direct traffic
        ];

        $userAgents = [
            'Mozilla/5.0 (Linux; Android 13; SM-S908B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        ];

        $visitLogsToInsert = [];
        $now = Carbon::now();

        // Cek apakah sudah ada log demo
        $existingLogsCount = VisitLog::where('created_at', '>=', $now->copy()->subDays(30))->count();

        if ($existingLogsCount < 50 && $publishedPosts->isNotEmpty()) {
            for ($day = 29; $day >= 0; $day--) {
                $targetDate = $now->copy()->subDays($day);

                // Buat volume kunjungan meningkat mendekati hari ini (tren positif)
                $visitsCount = match (true) {
                    $day <= 3 => rand(12, 22),
                    $day <= 7 => rand(8, 16),
                    $day <= 14 => rand(6, 12),
                    default => rand(4, 9),
                };

                for ($v = 0; $v < $visitsCount; $v++) {
                    $post = $publishedPosts->random();
                    $hour = rand(7, 23);
                    $minute = rand(0, 59);
                    $logTime = $targetDate->copy()->setHour($hour)->setMinute($minute);

                    $isArticle = rand(1, 10) <= 8; // 80% ke artikel, 20% ke beranda/arsip
                    $pageKey = $isArticle ? 'blog_post' : (rand(1, 2) === 1 ? 'blog_index' : 'blog_category');
                    $referer = $referrers[array_rand($referrers)];
                    $userAgent = $userAgents[array_rand($userAgents)];
                    $visitorId = 'demo_vis_' . rand(1, 120);

                    $visitLogsToInsert[] = [
                        'page_key' => $pageKey,
                        'visitable_type' => $isArticle ? BlogPost::class : null,
                        'visitable_id' => $isArticle ? $post->id : null,
                        'locale' => rand(1, 10) <= 8 ? 'id' : 'en',
                        'path' => $isArticle ? "/blog/{$post->id}" : '/blog',
                        'ip_address' => '192.168.1.' . rand(10, 250),
                        'user_agent' => $userAgent,
                        'referer' => $referer,
                        'visitor_hash' => md5($visitorId . $targetDate->toDateString()),
                        'session_id' => Str::random(40),
                        'created_at' => $logTime,
                    ];
                }
            }

            // Chunk insert untuk efisiensi tinggi
            foreach (array_chunk($visitLogsToInsert, 100) as $chunk) {
                DB::table('visit_logs')->insert($chunk);
            }
        }

        // 9. Seeder Likes
        if ($publishedPosts->isNotEmpty()) {
            foreach ($publishedPosts as $p) {
                $likesCount = rand(5, 20);
                for ($l = 0; $l < $likesCount; $l++) {
                    BlogLike::firstOrCreate([
                        'blog_post_id' => $p->id,
                        'visitor_hash' => 'like_hash_' . $p->id . '_' . $l,
                    ], [
                        'ip_address' => '192.168.1.' . rand(10, 250),
                    ]);
                }
            }
        }
    }
}
