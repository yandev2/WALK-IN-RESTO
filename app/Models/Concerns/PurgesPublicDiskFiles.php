<?php

namespace App\Models\Concerns;

use App\Support\CmsMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait PurgesPublicDiskFiles
{
    protected static function bootPurgesPublicDiskFiles(): void
    {
        static::updating(function (Model $model): void {
            foreach ($model->storedFileAttributes() as $attribute) {
                if ($model->isDirty($attribute)) {
                    CmsMedia::delete($model->getOriginal($attribute));
                }
            }
        });

        $purge = function (Model $model): void {
            foreach ($model->storedFileAttributes() as $attribute) {
                CmsMedia::delete($model->getAttribute($attribute));
            }
        };

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class), true)) {
            static::forceDeleting($purge);
        } else {
            static::deleting($purge);
        }
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return [];
    }
}
