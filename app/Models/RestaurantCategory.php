<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RestaurantCategory extends Model
{
    public const FACILITY_KEYS = [
        'wifi' => 'Wifi',
        'parking' => 'Parkir',
        'musholla' => 'Musholla',
        'child_friendly' => 'Ramah Anak',
        'outdoor' => 'Outdoor',
    ];

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_restaurant_category');
    }
}
