<?php

namespace Database\Seeders;

use App\Models\RestaurantCategory;
use Illuminate\Database\Seeder;

class RestaurantCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Indonesia', 'slug' => 'indonesia', 'sort_order' => 1],
            ['name' => 'Kopi & Kafe', 'slug' => 'kopi-kafe', 'sort_order' => 2],
            ['name' => 'Jepang', 'slug' => 'jepang', 'sort_order' => 3],
            ['name' => 'Cepat Saji', 'slug' => 'cepat-saji', 'sort_order' => 4],
            ['name' => 'Bar & Lounge', 'slug' => 'bar-lounge', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            RestaurantCategory::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true],
            );
        }
    }
}
