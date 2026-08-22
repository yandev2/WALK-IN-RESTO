<?php

namespace App\Support;

use App\Models\Activity;
use App\Models\Order;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Model;

final class ActivityLogger
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public static function log(string $action, array $payload = []): void
    {
        $logName = strstr($action, '.', true) ?: $action;
        $restaurantId = $payload['restaurant_id'] ?? TenantContext::restaurantId();

        $properties = array_filter([
            'old' => $payload['old'] ?? null,
            'attributes' => $payload['new'] ?? null,
            'reason' => $payload['reason'] ?? null,
            'outlet_id' => $payload['outlet_id'] ?? null,
            'visit_id' => $payload['visit_id'] ?? null,
            'order_id' => $payload['order_id'] ?? null,
            'payment_id' => $payload['payment_id'] ?? null,
            'whatsapp_message_id' => $payload['whatsapp_message_id'] ?? null,
            'actor_type' => $payload['actor_type'] ?? 'staff',
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
        ], static fn (mixed $value): bool => $value !== null);

        $logger = activity($logName)
            ->event($action)
            ->withProperties($properties);

        $causer = self::resolveCauser($payload);

        if ($causer instanceof User) {
            $logger->causedBy($causer);
        }

        $subject = self::resolveSubject($payload);

        if ($subject instanceof Model) {
            $logger->performedOn($subject);

            if (blank($restaurantId) && isset($subject->restaurant_id)) {
                $restaurantId = $subject->restaurant_id;
            }
        }

        $logger->tap(function (Activity $activity) use ($restaurantId): void {
            if (filled($restaurantId)) {
                $activity->restaurant_id = $restaurantId;
            }
        });

        $logger->log($payload['description'] ?? $action);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function resolveCauser(array $payload): ?User
    {
        if (array_key_exists('user_id', $payload)) {
            return filled($payload['user_id'])
                ? User::query()->find($payload['user_id'])
                : null;
        }

        $user = auth()->user();

        return $user instanceof User ? $user : null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private static function resolveSubject(array $payload): ?Model
    {
        if (($payload['subject'] ?? null) instanceof Model) {
            return $payload['subject'];
        }

        if (filled($payload['order_id'] ?? null)) {
            return Order::query()->withoutRestaurantScope()->find($payload['order_id']);
        }

        if (filled($payload['visit_id'] ?? null)) {
            return Visit::query()->withoutRestaurantScope()->find($payload['visit_id']);
        }

        return null;
    }
}
