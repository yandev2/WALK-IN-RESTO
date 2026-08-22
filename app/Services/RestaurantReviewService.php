<?php

namespace App\Services;

use App\Models\Order;
use App\Models\RestaurantReview;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RestaurantReviewService
{
    /** @var list<string> */
    private const PORTAL_STATUSES = ['paid', 'in_production', 'completed'];

    public function portalOpen(Visit $visit): bool
    {
        if ($visit->status !== 'open') {
            return false;
        }

        return $visit->orders()
            ->whereIn('status', self::PORTAL_STATUSES)
            ->exists();
    }

    public function canSubmit(Visit $visit): bool
    {
        if ($visit->status !== 'open') {
            return false;
        }

        if ($visit->review()->exists()) {
            return false;
        }

        return $visit->orders()
            ->where('status', 'completed')
            ->exists();
    }

    public function submit(Visit $visit, int $rating, string $comment): RestaurantReview
    {
        if ($visit->status !== 'open') {
            throw ValidationException::withMessages([
                'review' => 'Sesi meja sudah berakhir.',
            ]);
        }

        if ($visit->review()->exists()) {
            throw ValidationException::withMessages([
                'review' => 'Anda sudah memberikan ulasan untuk sesi ini.',
            ]);
        }

        $completedOrder = $visit->orders()
            ->where('status', 'completed')
            ->oldest('id')
            ->first();

        if (! $completedOrder instanceof Order) {
            throw ValidationException::withMessages([
                'review' => 'Ulasan baru bisa diberikan setelah pesanan selesai.',
            ]);
        }

        return DB::transaction(function () use ($visit, $rating, $comment, $completedOrder): RestaurantReview {
            $visit->loadMissing('review');

            if ($visit->review) {
                throw ValidationException::withMessages([
                    'review' => 'Anda sudah memberikan ulasan untuk sesi ini.',
                ]);
            }

            return RestaurantReview::query()->create([
                'restaurant_id' => $visit->restaurant_id,
                'outlet_id' => $visit->outlet_id,
                'visit_id' => $visit->id,
                'order_id' => $completedOrder->id,
                'customer_name' => $visit->customer_name,
                'rating' => $rating,
                'comment' => $comment,
                'submitted_at' => now(),
            ]);
        });
    }
}
