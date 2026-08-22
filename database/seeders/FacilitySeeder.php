<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\RestaurantCategory;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $sort = 1;

        foreach (RestaurantCategory::FACILITY_KEYS as $key => $name) {
            Facility::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $name,
                    'sort_order' => $sort,
                    'is_active' => true,
                ],
            );
            $sort++;
        }
    }
}
