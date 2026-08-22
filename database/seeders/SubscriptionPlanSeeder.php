<?php

namespace Database\Seeders;

use App\Enums\PlanCode;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::query()->updateOrCreate(
            ['code' => PlanCode::LandingOnly->value],
            [
                'name' => 'Landing Page Only',
                'description' => 'Halaman publik restoran, CMS, dan profil directory. Tanpa pemesanan, KDS, atau operasional.',
                'price_monthly' => 99000,
                'features' => [
                    'cms' => true,
                    'menu' => false,
                    'operations' => false,
                    'analytics' => false,
                    'settings' => 'limited',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => PlanCode::ManagementKds->value],
            [
                'name' => 'Management KDS',
                'description' => 'Semua fitur: CMS, menu, order, KDS, kasir, meja, dan laporan.',
                'price_monthly' => 249000,
                'features' => [
                    'cms' => true,
                    'menu' => true,
                    'operations' => true,
                    'analytics' => true,
                    'settings' => 'full',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
        );
    }
}
