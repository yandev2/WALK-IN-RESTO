<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use App\Models\Concerns\PurgesPublicDiskFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsGalleryImage extends Model
{
    use BelongsToRestaurantAndOutlet;
    use PurgesPublicDiskFiles;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'image_path',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['image_path'];
    }
}
