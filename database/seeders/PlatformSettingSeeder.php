<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        $existing = PlatformSetting::query()->first();

        if ($existing) {
            return;
        }

        PlatformSetting::query()->create(PlatformSetting::defaults());
    }
}
