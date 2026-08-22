<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use App\Models\Concerns\PurgesPublicDiskFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemPhoto extends Model
{
    use BelongsToRestaurantAndOutlet;
    use PurgesPublicDiskFiles;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'menu_item_id',
        'photo_path',
        'sort_order',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['photo_path'];
    }
}
