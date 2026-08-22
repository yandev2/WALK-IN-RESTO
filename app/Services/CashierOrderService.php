<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CashierOrderService
{
    public function __construct(
        private VisitClaimService $claims,
        private GuestCheckoutService $checkout,
    ) {}

    /**
     * @param  list<array{menu_item_id: int, variant_id?: int|null, qty: int, notes?: string|null, modifier_ids?: list<int>}>  $lines
     */
    public function create(
        User $user,
        DiningTable $table,
        string $customerWa,
        ?string $customerName,
        string $method,
        bool $sendReceipt,
        array $lines,
    ): Order {
        if ($lines === []) {
            throw ValidationException::withMessages(['lines' => 'Pilih minimal satu menu.']);
        }

        return DB::transaction(function () use ($user, $table, $customerWa, $customerName, $method, $sendReceipt, $lines) {
            $visit = $this->claims->openByCashier($table, $user, $customerWa, $customerName);

            $order = $this->checkout->placeOrder(
                $visit,
                $method,
                $sendReceipt,
                'cashier-'.Str::ulid(),
                'cashier',
                $lines,
                [],
                $user->id,
            );

            ActivityLogger::log('order.create', [
                'restaurant_id' => $order->restaurant_id,
                'outlet_id' => $order->outlet_id,
                'visit_id' => $visit->id,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'new' => [
                    'source' => 'cashier',
                    'payment_method' => $method,
                    'table_id' => $table->id,
                ],
            ]);

            return $order;
        });
    }
}
