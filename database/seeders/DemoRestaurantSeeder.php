<?php

namespace Database\Seeders;

use App\Enums\PlanCode;
use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsGalleryImage;
use App\Models\CmsProfile;
use App\Models\DiningTable;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Models\Outlet;
use App\Models\OutletOperatingHour;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\SubscriptionPlanSync;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Demo',
            'slug' => 'resto-demo',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
            'plan_code' => PlanCode::ManagementKds->value,
            'legal_name' => 'Resto Demo',
            'logo_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=200&q=80',
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Resto Demo — Utama',
            'address' => 'Jl. Demo No. 1, Jakarta',
            'phone' => '081234567890',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'geofence_radius_m' => 30,
            'gps_accuracy_max_m' => 50,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
        ]);

        foreach (range(0, 6) as $day) {
            OutletOperatingHour::query()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'day_of_week' => $day,
                'opens_at' => '10:00:00',
                'closes_at' => '22:00:00',
                'is_closed' => false,
            ]);
        }

        DB::table('outlet_sequences')->insert([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'seq_key' => 'order',
            'next_value' => 1,
        ]);

        $kitchen = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'kitchen',
            'name' => 'Dapur',
            'sort_order' => 1,
        ]);

        $bar = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'bar',
            'name' => 'Bar',
            'sort_order' => 2,
        ]);

        foreach (range(1, 8) as $number) {
            DiningTable::query()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'code' => (string) $number,
                'capacity' => $number <= 4 ? 2 : 4,
                'area' => $number <= 4 ? 'Indoor' : 'Outdoor',
                'qr_secret' => Str::random(64),
            ]);
        }

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'headline' => 'Masakan rumahan, meja bebas pilih.',
            'about_html' => '<p>Resto Demo adalah warung makan walk-in. Tamu datang, duduk di meja yang masih kosong, lalu memesan dari HP sendiri lewat stiker QR di meja.</p><p>Tidak ada reservasi di tahap ini. Kalau meja penuh, kami minta Anda menunggu atau kembali sesuai jam buka.</p>',
            'hero_image_path' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80',
            'how_to_image_path' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80',
            'about_image_path' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1200&q=80',
            'map_embed_url' => 'https://maps.google.com/maps?q=-6.2000000,106.8166667&z=16&output=embed',
            'cta_label' => 'Lihat lokasi',
            'cta_url' => 'https://www.google.com/maps/search/?api=1&query=-6.2000000,106.8166667',
            'primary_color' => '#F97316',
            'accent_color' => '#FB923C',
        ]);

        CmsBanner::query()->create([
            'restaurant_id' => $restaurant->id,
            'title' => 'Nasi Goreng Spesial',
            'subtitle' => 'Telur matang, ayam suwir, dan sambal pedas khas dapur kami. Porsi hangat siap dinikmati.',
            'badge_text' => 'Baru!',
            'price_label' => 'Rp 28.000',
            'cta_label' => 'Lihat menu',
            'link_url' => '/'.$restaurant->slug.'/menu',
            'image_path' => 'https://images.unsplash.com/photo-1603133872878-684f208fb274?auto=format&fit=crop&w=1200&q=80',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths(2),
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CmsBanner::query()->create([
            'restaurant_id' => $restaurant->id,
            'title' => 'Promo Weekday',
            'subtitle' => 'Es teh gratis untuk setiap pesanan nasi goreng Senin–Jumat. Datang langsung, walk-in saja.',
            'price_label' => 'Gratis es teh',
            'cta_label' => 'Pesan sekarang',
            'link_url' => '/'.$restaurant->slug,
            'image_path' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?auto=format&fit=crop&w=1200&q=80',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonths(2),
            'sort_order' => 2,
            'is_active' => true,
        ]);

        foreach ([
            ['Bagaimana cara memesan?', '<p>Duduk di meja kosong, scan stiker QR, isi nomor WhatsApp, pilih menu, lalu bayar QRIS atau tunai. Kasir yang menerima pembayaran.</p>', 1],
            ['Bisa pesan meja dari HP sekarang?', '<p>Tidak. Tahap ini walk-in saja: datang, duduk, baru scan QR. Reservasi akan menyusul di tahap berikutnya.</p>', 2],
            ['Kalau HP teman ikut pesan?', '<p>Minta PIN 4 digit dari HP yang pertama scan. Jangan buka sesi baru di meja yang sama.</p>', 3],
        ] as [$question, $answer, $order]) {
            CmsFaq::query()->create([
                'restaurant_id' => $restaurant->id,
                'question' => $question,
                'answer_html' => $answer,
                'sort_order' => $order,
            ]);
        }

        foreach ([
            ['https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=900&q=80', 'Ruang makan'],
            ['https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80', 'Hidangan utama'],
            ['https://images.unsplash.com/photo-1559339352-11d035aa65de?auto=format&fit=crop&w=900&q=80', 'Suasana malam'],
            ['https://images.unsplash.com/photo-1544025162-d766402d3276?auto=format&fit=crop&w=900&q=80', 'Dapur'],
        ] as $index => [$path, $caption]) {
            CmsGalleryImage::query()->create([
                'restaurant_id' => $restaurant->id,
                'image_path' => $path,
                'caption' => $caption,
                'sort_order' => $index + 1,
            ]);
        }

        $makanan = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Makanan',
            'sort_order' => 1,
        ]);

        $minuman = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Minuman',
            'sort_order' => 2,
        ]);

        $nasiGoreng = MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $kitchen->id,
            'name' => 'Nasi Goreng',
            'description' => 'Nasi goreng spekial',
            'price' => 28000,
            'discount_percent' => 20,
            'photo_path' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 1,
        ]);

        MenuVariant::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'menu_item_id' => $nasiGoreng->id,
            'name' => 'Pedas',
            'price_delta' => 0,
            'sort_order' => 1,
        ]);

        MenuVariant::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'menu_item_id' => $nasiGoreng->id,
            'name' => 'Tidak Pedas',
            'price_delta' => 0,
            'sort_order' => 2,
        ]);

        $extraGroup = ModifierGroup::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Extra',
            'min_select' => 0,
            'max_select' => 2,
            'is_required' => false,
        ]);

        Modifier::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'modifier_group_id' => $extraGroup->id,
            'name' => 'Extra Telur',
            'price' => 5000,
            'sort_order' => 1,
        ]);

        Modifier::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'modifier_group_id' => $extraGroup->id,
            'name' => 'Extra Sosis',
            'price' => 7000,
            'sort_order' => 2,
        ]);

        $nasiGoreng->modifierGroups()->attach($extraGroup->id, [
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $minuman->id,
            'station_id' => $bar->id,
            'name' => 'Es Teh Manis',
            'description' => 'Teh manis dingin',
            'price' => 8000,
            'photo_path' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 1,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $kitchen->id,
            'name' => 'Mie Goreng',
            'description' => 'Mie goreng spesial',
            'price' => 25000,
            'photo_path' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $kitchen->id,
            'name' => 'Ayam Bakar',
            'description' => 'Ayam bakar bumbu kecap',
            'price' => 32000,
            'photo_path' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 3,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $kitchen->id,
            'name' => 'Sate Ayam',
            'description' => '10 tusuk sate ayam',
            'price' => 35000,
            'photo_path' => 'https://images.unsplash.com/photo-1529042410759-befb1204b916?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 4,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $minuman->id,
            'station_id' => $bar->id,
            'name' => 'Es Jeruk',
            'description' => 'Jeruk peras segar',
            'price' => 12000,
            'photo_path' => 'https://images.unsplash.com/photo-1622597467836-fbc0e840eaff?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $minuman->id,
            'station_id' => $bar->id,
            'name' => 'Kopi Susu',
            'description' => 'Kopi susu gula aren',
            'price' => 18000,
            'photo_path' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=800&q=80',
            'sort_order' => 3,
        ]);

        $this->seedStaff($restaurant, $outlet);
    }

    private function seedStaff(Restaurant $restaurant, Outlet $outlet): void
    {
        Role::query()->create([
            'name' => 'owner',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);

        $kasirRole = Role::query()->create([
            'name' => 'kasir',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $kasirRole->syncPermissions([
            'order.create',
            'order.verify_payment',
            'order.reject_payment',
            'order.void',
            'table.manage',
            'receipt.resend',
            'receipt.print',
        ]);

        $dapurRole = Role::query()->create([
            'name' => 'dapur',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $dapurRole->syncPermissions([
            'kds.view',
            'kds.update_status',
        ]);

        $superAdmin = User::query()->create([
            'name' => 'Super Admin',
            'email' => 'admin@resto.test',
            'username' => 'superadmin',
            'password' => 'password',
            'is_active' => true,
        ]);

        $owner = User::query()->create([
            'name' => 'Owner Demo',
            'email' => 'owner@resto.test',
            'username' => 'owner',
            'password' => 'password',
            'is_active' => true,
        ]);

        $kasir = User::query()->create([
            'name' => 'Kasir Demo',
            'email' => 'kasir@resto.test',
            'username' => 'kasir',
            'password' => 'password',
            'is_active' => true,
        ]);

        $dapur = User::query()->create([
            'name' => 'Dapur Demo',
            'email' => 'dapur@resto.test',
            'username' => 'dapur',
            'password' => 'password',
            'is_active' => true,
        ]);

        foreach ([$owner, $kasir, $dapur] as $staff) {
            $restaurant->users()->attach($staff->id, ['is_active' => true]);
            DB::table('outlet_users')->insert([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'user_id' => $staff->id,
                'created_at' => now(),
            ]);
        }

        app()[PermissionRegistrar::class]->setPermissionsTeamId(0);
        $superAdmin->assignRole('super_admin');

        $founder = User::query()->create([
            'name' => 'Founder',
            'email' => 'founder@resto.test',
            'username' => 'founder',
            'password' => 'password',
            'is_active' => true,
        ]);
        $founder->assignRole('founder');

        app()[PermissionRegistrar::class]->setPermissionsTeamId($restaurant->id);
        $owner->assignRole('owner');
        $kasir->assignRole('kasir');
        $dapur->assignRole('dapur');

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );
    }
}
