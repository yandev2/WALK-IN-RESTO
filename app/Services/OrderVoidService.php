<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderVoidService
{
    public function __construct(private KdsItemService $kds) {}

    public function voidItem(OrderItem $item, User $user, string $reason): void
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw ValidationException::withMessages([
                'reason' => 'Alasan void wajib diisi.',
            ]);
        }

        DB::transaction(function () use ($item, $user, $reason): void {
            $locked = OrderItem::query()->whereKey($item->id)->lockForUpdate()->firstOrFail();
            $order = Order::query()->whereKey($locked->order_id)->lockForUpdate()->firstOrFail();

            $this->assertVoidable($order, $locked);

            $policy = $this->policyFor($locked);
            $oldStatus = $locked->kds_status;

            $locked->forceFill([
                'kds_status' => 'voided',
                'void_omzet_policy' => $policy,
                'void_reason' => $reason,
                'voided_at' => now(),
            ])->save();

            $this->kds->syncOrder($order->refresh());

            ActivityLogger::log('order.void_item', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $order->visit_id,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'old' => ['kds_status' => $oldStatus, 'order_item_id' => $locked->id],
                'new' => [
                    'kds_status' => 'voided',
                    'void_omzet_policy' => $policy,
                    'order_item_id' => $locked->id,
                ],
                'reason' => $reason,
            ]);
        });
    }

    public function voidOrder(Order $order, User $user, string $reason): void
    {
        $reason = trim($reason);

        if ($reason === '') {
            throw ValidationException::withMessages([
                'reason' => 'Alasan void wajib diisi.',
            ]);
        }

        DB::transaction(function () use ($order, $user, $reason): void {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            $this->assertOrderPaid($locked);

            $items = $locked->items()->where('kds_status', '!=', 'voided')->lockForUpdate()->get();

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Tidak ada item yang bisa di-void.',
                ]);
            }

            foreach ($items as $item) {
                $policy = $this->policyFor($item);
                $oldStatus = $item->kds_status;

                $item->forceFill([
                    'kds_status' => 'voided',
                    'void_omzet_policy' => $policy,
                    'void_reason' => $reason,
                    'voided_at' => now(),
                ])->save();

                ActivityLogger::log('order.void_item', [
                    'restaurant_id' => $item->restaurant_id,
                    'outlet_id' => $item->outlet_id,
                    'visit_id' => $locked->visit_id,
                    'order_id' => $locked->id,
                    'user_id' => $user->id,
                    'old' => ['kds_status' => $oldStatus, 'order_item_id' => $item->id],
                    'new' => [
                        'kds_status' => 'voided',
                        'void_omzet_policy' => $policy,
                        'order_item_id' => $item->id,
                    ],
                    'reason' => $reason,
                ]);
            }

            $this->kds->syncOrder($locked->refresh());

            ActivityLogger::log('order.void', [
                'restaurant_id' => $locked->restaurant_id,
                'outlet_id' => $locked->outlet_id,
                'visit_id' => $locked->visit_id,
                'order_id' => $locked->id,
                'user_id' => $user->id,
                'old' => ['status' => $order->status],
                'new' => ['status' => $locked->fresh()->status],
                'reason' => $reason,
            ]);
        });
    }

    private function assertVoidable(Order $order, OrderItem $item): void
    {
        $this->assertOrderPaid($order);

        if ($item->kds_status === 'voided') {
            throw ValidationException::withMessages([
                'item' => 'Item ini sudah di-void.',
            ]);
        }
    }

    private function assertOrderPaid(Order $order): void
    {
        if (! $order->paid_at || ! $order->isAccepted()) {
            throw ValidationException::withMessages([
                'order' => 'Hanya pesanan yang sudah lunas yang bisa di-void.',
            ]);
        }
    }

    private function policyFor(OrderItem $item): string
    {
        return $item->kds_status === 'queued' ? 'cut' : 'waste';
    }
}
