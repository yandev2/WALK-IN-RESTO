<?php

namespace App\Services;

use App\Enums\PlanCode;
use App\Models\CmsProfile;
use App\Models\KdsStation;
use App\Models\Outlet;
use App\Models\OutletOperatingHour;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantProvisioner
{
    public function provision(Restaurant $restaurant, string $planCode): void
    {
        DB::transaction(function () use ($restaurant, $planCode): void {
            $outlet = $this->ensureDefaultOutlet($restaurant);
            $this->ensureOperatingHours($restaurant, $outlet);
            $this->ensureOutletSequence($restaurant, $outlet);
            $this->ensureCmsProfile($restaurant);

            if ($planCode === PlanCode::ManagementKds->value) {
                $this->ensureKdsStations($restaurant, $outlet);
            }
        });
    }

    public function ensureKdsStations(Restaurant $restaurant, ?Outlet $outlet = null): void
    {
        $outlet ??= $restaurant->defaultOutlet;

        if (! $outlet instanceof Outlet) {
            $outlet = $this->ensureDefaultOutlet($restaurant);
        }

        $defaults = [
            ['slug' => 'kitchen', 'name' => 'Dapur', 'sort_order' => 1],
            ['slug' => 'bar', 'name' => 'Bar', 'sort_order' => 2],
        ];

        foreach ($defaults as $station) {
            KdsStation::query()->firstOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'slug' => $station['slug'],
                ],
                [
                    'name' => $station['name'],
                    'sort_order' => $station['sort_order'],
                ],
            );
        }
    }

    public function ensureDefaultOutlet(Restaurant $restaurant): Outlet
    {
        $outlet = $restaurant->defaultOutlet;

        if ($outlet instanceof Outlet) {
            return $outlet;
        }

        return Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => $restaurant->name.' — Utama',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'geofence_radius_m' => 30,
            'gps_accuracy_max_m' => 50,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
            'claim_ttl_minutes' => 10,
            'awaiting_cashier_ttl_minutes' => 20,
        ]);
    }

    private function ensureOperatingHours(Restaurant $restaurant, Outlet $outlet): void
    {
        if (OutletOperatingHour::query()->where('outlet_id', $outlet->id)->exists()) {
            return;
        }

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
    }

    private function ensureOutletSequence(Restaurant $restaurant, Outlet $outlet): void
    {
        $exists = DB::table('outlet_sequences')
            ->where('outlet_id', $outlet->id)
            ->where('seq_key', 'order')
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('outlet_sequences')->insert([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'seq_key' => 'order',
            'next_value' => 1,
        ]);
    }

    private function ensureCmsProfile(Restaurant $restaurant): void
    {
        if ($restaurant->cmsProfile) {
            return;
        }

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'headline' => $restaurant->name,
            'cta_label' => 'Lihat lokasi',
        ]);
    }
}
