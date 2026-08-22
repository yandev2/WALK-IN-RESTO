<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Validation\ValidationException;

class KdsItemService
{
    public function advance(OrderItem $item, User $user): void
    {
        $next = match ($item->kds_status) {
            'queued' => 'preparing',
            'preparing' => 'ready',
            'ready' => 'served',
            default => null,
        };

        if (! $next) {
            throw ValidationException::withMessages([
                'kds_status' => 'Item ini tidak bisa dimajukan.',
            ]);
        }

        $this->setStatus($item, $next, $user);
    }

    public function revertServed(OrderItem $item, User $user): void
    {
        if (! $item->canRevertServed()) {
            throw ValidationException::withMessages([
                'kds_status' => 'Served hanya bisa dikembalikan ke ready dalam 2 menit.',
            ]);
        }

        $this->setStatus($item, 'ready', $user, revertServed: true);
    }

    public function syncOrder(Order $order): void
    {
        if (! in_array($order->status, ['paid', 'in_production', 'completed', 'voided'], true)) {
            return;
        }

        $items = $order->items()->where('kds_status', '!=', 'voided')->get();

        if ($items->isEmpty()) {
            if (in_array($order->status, Order::ACCEPTED_STATUSES, true)) {
                $order->forceFill([
                    'status' => 'voided',
                    'voided_at' => $order->voided_at ?? now(),
                ])->save();
            }

            return;
        }

        if ($order->status === 'voided') {
            return;
        }

        $allServed = $items->every(fn (OrderItem $item): bool => $item->kds_status === 'served');

        $order->forceFill([
            'status' => $allServed ? 'completed' : 'in_production',
        ])->save();
    }

    private function setStatus(OrderItem $item, string $status, User $user, bool $revertServed = false): void
    {
        $old = $item->kds_status;

        $payload = ['kds_status' => $status];

        if ($status === 'preparing' && blank($item->preparing_at)) {
            $payload['preparing_at'] = now();
        }

        if ($status === 'ready') {
            if (blank($item->ready_at)) {
                $payload['ready_at'] = now();
            }

            if ($revertServed) {
                $payload['served_reverted_at'] = now();
                $payload['served_at'] = null;
            }
        }

        if ($status === 'served') {
            $payload['served_at'] = now();
        }

        $item->forceFill($payload)->save();

        $item->loadMissing('order');

        if ($item->order) {
            $this->syncOrder($item->order);
        }

        ActivityLogger::log('kds.update_status', [
            'restaurant_id' => $item->restaurant_id,
            'outlet_id' => $item->outlet_id,
            'order_id' => $item->order_id,
            'user_id' => $user->id,
            'old' => ['kds_status' => $old, 'order_item_id' => $item->id],
            'new' => ['kds_status' => $status, 'order_item_id' => $item->id],
            'reason' => $revertServed ? 'revert_served' : null,
        ]);
    }
}
