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
            $defaults = PlatformSetting::defaults();
            $updates = [];
            foreach (['about_title', 'about_content', 'terms_title', 'terms_content'] as $key) {
                if (blank($existing->{$key})) {
                    $updates[$key] = $defaults[$key];
                }
            }
            if ($updates !== []) {
                $existing->update($updates);
            }

            return;
        }

        PlatformSetting::query()->create(PlatformSetting::defaults());
    }
}
