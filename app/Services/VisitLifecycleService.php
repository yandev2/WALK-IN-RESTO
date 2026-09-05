<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Visit;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VisitLifecycleService
{
    public function expireIfNeeded(Visit $visit): void
    {
        if ($visit->status !== 'open') {
            return;
        }

        if ($this->shouldAutoCloseSimpleModeVisit($visit)) {
            $this->close($visit, needsCleaning: false, reason: 'simple_mode_auto_close');

            return;
        }

        if ($visit->claim_expires_at?->isFuture()) {
            return;
        }

        if ($visit->hasBlockingOrders()) {
            return;
        }

        $this->close($visit, needsCleaning: false, reason: 'claim_ttl');
    }

    public function shouldAutoCloseSimpleModeVisit(Visit $visit): bool
    {
        $visit->loadMissing(['outlet', 'orders']);

        if (! (bool) $visit->outlet?->simple_mode) {
            return false;
        }

        $orders = $visit->orders;

        if ($orders->isEmpty()) {
            return false;
        }

        $hasIncomplete = $orders->contains(
            fn (Order $order): bool => in_array($order->status, ['awaiting_cashier', 'pending_payment', 'in_production', 'paid'], true)
        );

        if ($hasIncomplete) {
            return false;
        }

        $hasCompleted = $orders->contains(fn (Order $order): bool => $order->status === 'completed');

        if (! $hasCompleted) {
            return false;
        }

        $latestTime = $orders->max(fn (Order $order) => $order->paid_at ?? $order->updated_at ?? $order->created_at);

        return $latestTime && $latestTime->addMinutes(5)->isPast();
    }

    public function expireSimpleModeCompletedVisits(): int
    {
        $visits = Visit::query()
            ->where('status', 'open')
            ->whereHas('outlet', fn ($query) => $query->where('simple_mode', true))
            ->whereHas('orders', fn ($query) => $query->where('status', 'completed'))
            ->with(['orders', 'diningTable', 'outlet'])
            ->get();

        $closed = 0;

        foreach ($visits as $visit) {
            if ($this->shouldAutoCloseSimpleModeVisit($visit)) {
                $this->close($visit, needsCleaning: false, reason: 'simple_mode_auto_close');
                $closed++;
            }
        }

        return $closed;
    }

    public function expireStaleClaims(): int
    {
        $ids = Visit::query()
            ->where('status', 'open')
            ->whereNotNull('claim_expires_at')
            ->where('claim_expires_at', '<=', now())
            ->pluck('id');

        $closed = 0;

        foreach ($ids as $id) {
            $visit = Visit::query()
                ->with(['orders', 'diningTable'])
                ->find($id);

            if (! $visit || $visit->status !== 'open') {
                continue;
            }

            $this->expireIfNeeded($visit);

            if ($visit->fresh()?->status === 'closed') {
                $closed++;
            }
        }

        return $closed;
    }

    public function closeByCashier(Visit $visit, User $user): void
    {
        if ($visit->status !== 'open') {
            throw ValidationException::withMessages([
                'visit' => 'Visit ini sudah ditutup.',
            ]);
        }

        if ($visit->orders()->whereIn('status', ['awaiting_cashier', 'pending_payment'])->exists()) {
            throw ValidationException::withMessages([
                'visit' => 'Tidak bisa ditutup jika masih ada order menunggu kasir.',
            ]);
        }

        $visit->loadMissing(['outlet', 'diningTable.outlet']);
        $isSimple = (bool) ($visit->outlet?->simple_mode ?? $visit->diningTable?->outlet?->simple_mode);

        $this->close(
            $visit,
            needsCleaning: ! $isSimple,
            closedByUserId: $user->id,
            reason: $isSimple ? 'simple_mode_cashier' : 'cashier',
        );
    }

    public function close(Visit $visit, bool $needsCleaning = true, ?int $closedByUserId = null, ?string $reason = null): void
    {
        DB::transaction(function () use ($visit, $needsCleaning, $closedByUserId, $reason): void {
            $locked = Visit::query()->whereKey($visit->id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== 'open') {
                return;
            }

            $locked->forceFill([
                'status' => 'closed',
                'closed_at' => now(),
                'closed_by_user_id' => $closedByUserId,
            ])->save();

            $locked->loadMissing(['diningTable.outlet', 'outlet']);

            $isSimple = (bool) ($locked->outlet?->simple_mode ?? $locked->diningTable?->outlet?->simple_mode);
            $effectiveNeedsCleaning = $isSimple ? false : $needsCleaning;

            $locked->diningTable?->update([
                'open_visit_id' => null,
                'needs_cleaning' => $effectiveNeedsCleaning,
            ]);

            $locked->cartItems()->delete();

            ActivityLogger::log($reason === 'claim_ttl' ? 'visit.expire_claim' : 'visit.close', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $locked->id,
                'actor_type' => $reason === 'claim_ttl' ? 'system' : 'staff',
                'user_id' => $closedByUserId,
                'old' => ['status' => 'open'],
                'new' => ['status' => 'closed', 'needs_cleaning' => $effectiveNeedsCleaning],
                'reason' => $reason,
            ]);

            $visit->setRawAttributes($locked->getAttributes());
            $visit->syncOriginal();
        });
    }
}
