<?php

namespace App\Models;

use App\Models\Concerns\ScopedToRestaurant;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use ScopedToRestaurant;

    protected static function booted(): void
    {
        static::creating(function (self $activity): void {
            if (filled($activity->restaurant_id)) {
                return;
            }

            if ($restaurantId = TenantContext::restaurantId()) {
                $activity->restaurant_id = $restaurantId;

                return;
            }

            $subject = $activity->subject;

            if (is_object($subject) && isset($subject->restaurant_id)) {
                $activity->restaurant_id = $subject->restaurant_id;
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
