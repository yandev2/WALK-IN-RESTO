<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SubscriptionPlanSeeder::class,
            LandingTemplateSeeder::class,
            DemoRestaurantSeeder::class,
            RestaurantCategorySeeder::class,
            FacilitySeeder::class,
            PlatformSettingSeeder::class,
            DirectoryDemoRestaurantsSeeder::class,
            DemoCommissionInvoicesSeeder::class,
        ]);
    }
}
