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
                'billing_type' => \App\Enums\BillingType::FixedMonthly->value,
                'commission_percentage' => null,
                'features' => [
                    'cms' => true,
                    'menu' => false,
                    'operations' => false,
                    'analytics' => false,
                    'settings' => 'limited',
                ],
                'is_active' => false, // Hidden for now (Landing Page 100% free with KDS)
                'sort_order' => 1,
            ],
        );

        SubscriptionPlan::query()->updateOrCreate(
            ['code' => PlanCode::ManagementKds->value],
            [
                'name' => 'Management KDS',
                'description' => 'Layanan kasir POS, order meja, dapur KDS, analitik, serta bonus gratis landing page & CMS. Tanpa biaya bulanan, hanya komisi persentase omzet kasir di akhir bulan.',
                'price_monthly' => 0,
                'billing_type' => \App\Enums\BillingType::Commission->value,
                'commission_percentage' => 10.00,
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
