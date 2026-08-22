<?php

namespace Database\Seeders;

use App\Models\CmsProfile;
use App\Models\DiningTable;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Models\OutletOperatingHour;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantReview;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DirectoryDemoRestaurantsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = RestaurantCategory::query()
            ->whereIn('slug', ['indonesia', 'kopi-kafe', 'jepang', 'cepat-saji', 'bar-lounge'])
            ->get()
            ->keyBy('slug');

        $restaurants = [
            [
                'name' => 'Kopi Nusantara',
                'slug' => 'kopi-nusantara',
                'headline' => 'Kopi single origin & pastry rumahan',
                'price_level' => 2,
                'facilities' => ['wifi' => true, 'parking' => true, 'child_friendly' => true],
                'category_slugs' => ['kopi-kafe', 'indonesia'],
                'address' => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'lat' => -6.2615000,
                'lng' => 106.8108000,
                'hero' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=200&q=80',
            ],
            [
                'name' => 'Sushi Haru',
                'slug' => 'sushi-haru',
                'headline' => 'Sushi bar modern dengan counter omakase',
                'price_level' => 3,
                'facilities' => ['wifi' => true, 'parking' => true, 'outdoor' => false],
                'category_slugs' => ['jepang'],
                'address' => 'Jl. Senopati No. 45, Jakarta Selatan',
                'lat' => -6.2354000,
                'lng' => 106.8076000,
                'hero' => 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?auto=format&fit=crop&w=200&q=80',
            ],
            [
                'name' => 'Warung Nusantara',
                'slug' => 'warung-nusantara',
                'headline' => 'Masakan rumahan Indonesia autentik',
                'price_level' => 1,
                'facilities' => ['wifi' => false, 'parking' => true, 'musholla' => true],
                'category_slugs' => ['indonesia'],
                'address' => 'Jl. Tebet Timur Dalam No. 8, Jakarta Selatan',
                'lat' => -6.2297000,
                'lng' => 106.8523000,
                'hero' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=200&q=80',
            ],
            [
                'name' => 'Burger Street',
                'slug' => 'burger-street',
                'headline' => 'Smash burger & milkshake favorit anak muda',
                'price_level' => 2,
                'facilities' => ['wifi' => true, 'parking' => false, 'child_friendly' => true],
                'category_slugs' => ['cepat-saji'],
                'address' => 'Jl. Panglima Polim No. 22, Jakarta Selatan',
                'lat' => -6.2448000,
                'lng' => 106.7995000,
                'hero' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=200&q=80',
            ],
            [
                'name' => 'Sky Lounge 88',
                'slug' => 'sky-lounge-88',
                'headline' => 'Cocktail bar dengan pemandangan kota',
                'price_level' => 4,
                'facilities' => ['wifi' => true, 'parking' => true, 'outdoor' => true],
                'category_slugs' => ['bar-lounge'],
                'address' => 'Jl. Sudirman Kav. 52, Jakarta Pusat',
                'lat' => -6.2251000,
                'lng' => 106.8094000,
                'hero' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1470337458703-46ad1756a187?auto=format&fit=crop&w=200&q=80',
            ],
            [
                'name' => 'Ramen Lab',
                'slug' => 'ramen-lab',
                'headline' => 'Ramen rich broth dengan topping premium',
                'price_level' => 2,
                'facilities' => ['wifi' => true, 'parking' => false, 'child_friendly' => false],
                'category_slugs' => ['jepang', 'cepat-saji'],
                'address' => 'Jl. Melawai No. 5, Jakarta Selatan',
                'lat' => -6.2489000,
                'lng' => 106.7997000,
                'hero' => 'https://images.unsplash.com/photo-1569718212165-3a8278dfe799?auto=format&fit=crop&w=800&q=80',
                'logo' => 'https://images.unsplash.com/photo-1569718212165-3a8278dfe799?auto=format&fit=crop&w=200&q=80',
            ],
        ];

        foreach ($restaurants as $index => $data) {
            $restaurant = Restaurant::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'timezone' => 'Asia/Jakarta',
                    'currency' => 'IDR',
                    'is_active' => true,
                    'price_level' => $data['price_level'],
                    'facilities' => $data['facilities'],
                ],
            );

            CmsProfile::query()->updateOrCreate(
                ['restaurant_id' => $restaurant->id],
                [
                    'headline' => $data['headline'],
                    'hero_image_path' => $data['hero'],
                ],
            );

            $restaurant->update(['logo_path' => $data['logo']]);

            $outlet = Outlet::query()->updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'code' => 'MAIN',
                ],
                [
                    'name' => $data['name'].' — Utama',
                    'address' => $data['address'],
                    'phone' => '08123456789'.($index + 1),
                    'is_default' => true,
                    'is_open' => true,
                    'is_active' => true,
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'geofence_radius_m' => 30,
                    'gps_accuracy_max_m' => 50,
                    'pb1_pct' => 10,
                    'service_pct' => 5,
                    'tax_mode' => 'exclusive',
                ],
            );

            if (! DB::table('outlet_sequences')->where('outlet_id', $outlet->id)->exists()) {
                DB::table('outlet_sequences')->insert([
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'seq_key' => 'order',
                    'next_value' => 1,
                ]);
            }

            foreach (range(0, 6) as $day) {
                OutletOperatingHour::query()->updateOrCreate(
                    [
                        'outlet_id' => $outlet->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'restaurant_id' => $restaurant->id,
                        'opens_at' => '10:00:00',
                        'closes_at' => '22:00:00',
                        'is_closed' => false,
                    ],
                );
            }

            $station = KdsStation::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'slug' => 'kitchen',
                ],
                ['name' => 'Dapur'],
            );

            $menuCategory = MenuCategory::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'name' => 'Utama',
                ],
                ['sort_order' => 1, 'is_active' => true],
            );

            MenuItem::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'name' => 'Menu Andalan',
                ],
                [
                    'category_id' => $menuCategory->id,
                    'station_id' => $station->id,
                    'price' => 25000,
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $table = DiningTable::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'code' => 'A1',
                ],
                [
                    'capacity' => 4,
                    'qr_version' => 1,
                    'qr_secret' => Str::random(64),
                ],
            );

            $visit = Visit::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'table_id' => $table->id,
                    'status' => 'closed',
                ],
                [
                    'public_id' => (string) Str::ulid(),
                    'join_pin' => '1234',
                    'customer_wa' => '6281234567890',
                    'customer_name' => 'Tamu Demo',
                    'claimed_at' => now()->subHour(),
                    'claim_expires_at' => now()->subMinutes(30),
                    'closed_at' => now()->subMinutes(20),
                ],
            );

            RestaurantReview::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'visit_id' => $visit->id,
                ],
                [
                    'outlet_id' => $outlet->id,
                    'customer_name' => 'Pelanggan '.($index + 1),
                    'rating' => 4 + ($index % 2),
                    'comment' => 'Pelayanan ramah dan tempat nyaman.',
                    'submitted_at' => now()->subDays($index + 1),
                ],
            );

            $categoryIds = collect($data['category_slugs'])
                ->map(fn (string $slug) => $categories->get($slug)?->id)
                ->filter()
                ->values()
                ->all();

            $restaurant->categories()->sync($categoryIds);
        }

        $demo = Restaurant::query()->where('slug', 'resto-demo')->first();

        if ($demo && $categories->isNotEmpty()) {
            $demo->update([
                'price_level' => 2,
                'facilities' => ['wifi' => true, 'parking' => true, 'child_friendly' => true],
            ]);

            $demo->categories()->sync([
                $categories->get('indonesia')?->id,
                $categories->get('kopi-kafe')?->id,
            ]);

            Outlet::query()
                ->where('restaurant_id', $demo->id)
                ->where('is_default', true)
                ->update([
                    'latitude' => -6.2448000,
                    'longitude' => 106.7995000,
                    'address' => 'Jl. Panglima Polim No. 22, Jakarta Selatan',
                ]);
        }
    }
}
