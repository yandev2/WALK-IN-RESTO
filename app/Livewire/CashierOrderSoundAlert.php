<?php

namespace App\Livewire;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CashierOrderSoundAlert extends Component
{
    /**
     * @var list<int>
     */
    public array $knownOrderIds = [];

    public int $lastKnownOrderId = 0;

    public ?int $restaurantId = null;

    public bool $isInitialized = false;

    public function mount(): void
    {
        $this->syncInitialOrders();
    }

    public function syncInitialOrders(): void
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return;
        }

        $this->restaurantId = $restaurant->id;

        $pendingIds = Order::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('status', Order::STATUS_AWAITING_CASHIER)
            ->where('source', '!=', 'cashier')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $this->knownOrderIds = $pendingIds;
        $this->lastKnownOrderId = (int) (Order::query()
            ->where('restaurant_id', $restaurant->id)
            ->max('id') ?? 0);
        $this->isInitialized = true;
    }

    public function checkNewOrders(): void
    {
        $restaurantId = $this->restaurantId ?? Filament::getTenant()?->id;

        if (! $restaurantId) {
            return;
        }

        if (! $this->isInitialized) {
            $this->syncInitialOrders();

            return;
        }

        $newOrders = Order::query()
            ->with(['visit.diningTable'])
            ->where('restaurant_id', $restaurantId)
            ->where('status', Order::STATUS_AWAITING_CASHIER)
            ->where('source', '!=', 'cashier')
            ->where(function ($query) {
                $query->where('id', '>', $this->lastKnownOrderId)
                    ->orWhereNotIn('id', $this->knownOrderIds);
            })
            ->orderBy('id', 'asc')
            ->limit(20)
            ->get();

        if ($newOrders->isEmpty()) {
            return;
        }

        $newIds = $newOrders->pluck('id')->map(fn ($id) => (int) $id)->all();
        $count = count($newIds);

        if ($count === 1) {
            $order = $newOrders->first();
            $tableCode = $order->visit?->diningTable?->code;
            $location = filled($tableCode) ? "Meja {$tableCode}" : 'Bungkus / Kasir';
            $amount = 'Rp '.number_format((int) $order->grand_payable, 0, ',', '.');

            $viewUrl = null;
            try {
                $viewUrl = OrderResource::getUrl('view', ['record' => $order]);
            } catch (\Throwable) {
                $viewUrl = url('/admin/orders/'.$order->id);
            }

            $notification = Notification::make()
                ->title('Pesanan Baru Masuk!')
                ->body("Pesanan #{$order->number} ({$location}) • {$amount}")
                ->warning()
                ->duration(10000);

            if ($viewUrl) {
                $notification->actions([
                    Action::make('view')
                        ->label('Periksa Pesanan')
                        ->button()
                        ->url($viewUrl),
                ]);
            }

            $notification->send();
        } else {
            // High concurrency / peak hours: Consolidated single notification to prevent toast flood
            $tableList = $newOrders->take(3)->map(function ($order) {
                $tableCode = $order->visit?->diningTable?->code;

                return filled($tableCode) ? "Meja {$tableCode}" : '#'.$order->number;
            })->join(', ');

            $moreText = $count > 3 ? ' dan '.($count - 3).' lainnya' : '';

            $ordersUrl = null;
            try {
                $ordersUrl = OrderResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => ['value' => Order::STATUS_AWAITING_CASHIER],
                    ],
                ]);
            } catch (\Throwable) {
                $ordersUrl = url('/admin/orders');
            }

            $notification = Notification::make()
                ->title("{$count} Pesanan Baru Masuk!")
                ->body("{$count} pesanan baru menunggu kasir ({$tableList}{$moreText})")
                ->warning()
                ->duration(12000);

            if ($ordersUrl) {
                $notification->actions([
                    Action::make('view_queue')
                        ->label('Buka Antrian Pesanan')
                        ->button()
                        ->url($ordersUrl),
                ]);
            }

            $notification->send();
        }

        $this->dispatch('cashier-order-sound', count: $count, soundUrl: PlatformSetting::cashierSoundUrl());

        // Cap array size to latest 100 to prevent Livewire state bloat over long shifts
        $merged = array_values(array_unique(array_merge($this->knownOrderIds, $newIds)));
        $this->knownOrderIds = array_slice($merged, -100);
        $this->lastKnownOrderId = max($this->lastKnownOrderId, ...$newIds);
    }

    public function render(): View
    {
        return view('livewire.cashier-order-sound-alert');
    }
}
