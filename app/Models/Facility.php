<?php

namespace App\Models;

use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Facility extends Model
{
    protected $fillable = [
        'key',
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function activeOptions(): array
    {
        try {
            if (! Schema::hasTable('facilities')) {
                return RestaurantCategory::FACILITY_KEYS;
            }

            $options = static::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name', 'key')
                ->all();

            return $options !== [] ? $options : RestaurantCategory::FACILITY_KEYS;
        } catch (Throwable) {
            return RestaurantCategory::FACILITY_KEYS;
        }
    }

    /**
     * @return list<string>
     */
    public static function knownKeys(): array
    {
        try {
            if (! Schema::hasTable('facilities')) {
                return array_keys(RestaurantCategory::FACILITY_KEYS);
            }

            $keys = static::query()->orderBy('sort_order')->pluck('key')->all();

            return $keys !== [] ? $keys : array_keys(RestaurantCategory::FACILITY_KEYS);
        } catch (Throwable) {
            return array_keys(RestaurantCategory::FACILITY_KEYS);
        }
    }

    /**
     * @return array<string, Heroicon>
     */
    public static function iconMap(): array
    {
        return [
            'wifi' => Heroicon::OutlinedWifi,
            'parking' => Heroicon::OutlinedTruck,
            'musholla' => Heroicon::OutlinedMoon,
            'child_friendly' => Heroicon::OutlinedUserGroup,
            'outdoor' => Heroicon::OutlinedSun,
        ];
    }
}
