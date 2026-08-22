<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Visit;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderPaymentService
{
    private const DEFAULT_AWAITING_TTL_MINUTES = 20;

    public function approve(Order $order, User $cashier, ?string $gpsOverrideReason = null): void
    {
        DB::transaction(function () use ($order, $cashier, $gpsOverrideReason): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== 'awaiting_cashier') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan ini sudah diproses kasir lain.',
                ]);
            }

            $payment = $locked->payments()->latest('id')->lockForUpdate()->first();

            if ($payment instanceof Payment) {
                $needsGpsOverride = $payment->method === 'cash'
                    && in_array($payment->gps_status, ['low_accuracy', 'denied'], true);

                if ($needsGpsOverride && blank($gpsOverrideReason) && blank($payment->gps_overridden_at)) {
                    throw ValidationException::withMessages([
                        'gps' => 'Pembayaran tunai ini butuh override GPS beserta alasan.',
                    ]);
                }

                if ($needsGpsOverride && filled($gpsOverrideReason) && blank($payment->gps_overridden_at)) {
                    $payment->forceFill([
                        'gps_override_by_user_id' => $cashier->id,
                        'gps_override_reason' => $gpsOverrideReason,
                        'gps_overridden_at' => now(),
                    ]);
                }

                $payment->forceFill([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'paid_by_user_id' => $cashier->id,
                    'qris_hold_amount' => null,
                ])->save();
            }

            $oldStatus = $locked->status;

            $locked->forceFill([
                'status' => 'paid',
                'paid_at' => now(),
            ])->save();

            app(KdsItemService::class)->syncOrder($locked->refresh());

            ActivityLogger::log('order.approve_payment', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $locked->visit_id,
                'order_id' => $locked->id,
                'payment_id' => $payment?->id,
                'user_id' => $cashier->id,
                'old' => ['status' => $oldStatus],
                'new' => ['status' => 'paid'],
                'reason' => $gpsOverrideReason,
            ]);

            $order->setRawAttributes($locked->getAttributes());
            $order->syncOriginal();
        });

        try {
            app(OrderReceiptService::class)->afterPaid($order->refresh(), $cashier);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function reject(Order $order, User $cashier, string $reason): void
    {
        DB::transaction(function () use ($order, $cashier, $reason): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($locked->status !== 'awaiting_cashier') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan ini sudah diproses kasir lain.',
                ]);
            }

            $payment = $locked->payments()->latest('id')->lockForUpdate()->first();

            if ($payment instanceof Payment) {
                $payment->forceFill([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'reject_reason' => $reason,
                    'qris_hold_amount' => null,
                ])->save();
            }

            $oldStatus = $locked->status;

            $locked->forceFill([
                'status' => 'rejected',
            ])->save();

            ActivityLogger::log('order.reject_payment', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $locked->visit_id,
                'order_id' => $locked->id,
                'payment_id' => $payment?->id,
                'user_id' => $cashier->id,
                'old' => ['status' => $oldStatus],
                'new' => ['status' => 'rejected'],
                'reason' => $reason,
            ]);

            $order->setRawAttributes($locked->getAttributes());
            $order->syncOriginal();
        });
    }

    public function expireStaleAwaiting(): int
    {
        return $this->expireOrders($this->staleAwaitingQuery()->pluck('id'));
    }

    public function expireStaleAwaitingForVisit(Visit $visit): int
    {
        return $this->expireOrders(
            $this->staleAwaitingQuery()
                ->where('visit_id', $visit->id)
                ->pluck('id'),
        );
    }

    private function expireOrders(iterable $ids): int
    {
        $cancelled = 0;

        foreach ($ids as $id) {
            $order = Order::query()->find($id);

            if ($order instanceof Order && $this->expireAwaiting($order)) {
                $cancelled++;
            }
        }

        return $cancelled;
    }

    public function expireAwaiting(Order $order): bool
    {
        return DB::transaction(function () use ($order): bool {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->first();

            if (! $locked instanceof Order || $locked->status !== 'awaiting_cashier') {
                return false;
            }

            $payment = $locked->payments()->latest('id')->lockForUpdate()->first();

            if (
                ! $payment instanceof Payment
                || $payment->status !== 'awaiting_cashier'
                || ! $this->paymentHasExpired($payment)
            ) {
                return false;
            }

            $payment->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expired_at' => now(),
                'qris_hold_amount' => null,
            ])->save();

            $oldStatus = $locked->status;

            $locked->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ])->save();

            ActivityLogger::log('order.expire_awaiting', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $locked->visit_id,
                'order_id' => $locked->id,
                'payment_id' => $payment->id,
                'actor_type' => 'system',
                'user_id' => null,
                'old' => ['status' => $oldStatus],
                'new' => ['status' => 'cancelled'],
                'reason' => 'awaiting_cashier_ttl',
            ]);

            $order->setRawAttributes($locked->getAttributes());
            $order->syncOriginal();

            return true;
        });
    }

    private function staleAwaitingQuery(): Builder
    {
        $now = now();
        $fallbackBefore = $now->copy()->subMinutes(self::DEFAULT_AWAITING_TTL_MINUTES);

        return Order::query()
            ->where('status', 'awaiting_cashier')
            ->whereHas('payments', function ($query) use ($now, $fallbackBefore): void {
                $query->where('status', 'awaiting_cashier')
                    ->whereRaw(
                        '(awaiting_expires_at <= ? or (awaiting_expires_at is null and created_at <= ?))',
                        [$now, $fallbackBefore],
                    );
            });
    }

    private function paymentHasExpired(Payment $payment): bool
    {
        $now = now();

        if ($payment->awaiting_expires_at) {
            return $now->greaterThanOrEqualTo($payment->awaiting_expires_at);
        }

        if ($payment->created_at) {
            return $now->copy()->subMinutes(self::DEFAULT_AWAITING_TTL_MINUTES)
                ->greaterThanOrEqualTo($payment->created_at);
        }

        return false;
    }
}
