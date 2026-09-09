<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class PendingPaymentsWidget extends Widget
{
    use CanPoll;

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 7,
    ];

    protected string $view = 'filament.widgets.pending-payments';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        $restaurant = Filament::getTenant();

        if ($restaurant instanceof Restaurant && ($restaurant->hasOverdueCashierInvoice() || $restaurant->hasUnpaidOverdueInvoice())) {
            return false;
        }

        return ($user->isSuperAdmin()
            || $user->can('order.verify_payment')
            || $user->can('order.reject_payment'))
            && SubscriptionAccess::allows('operations');
    }

    protected function getPollingInterval(): ?string
    {
        return '15s';
    }

    /**
     * @return Collection<int, Order>
     */
    public function getPendingOrders(): Collection
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant) {
            return new Collection();
        }

        return Order::query()
            ->with(['visit.diningTable', 'items.menuItem'])
            ->where('restaurant_id', $restaurant->id)
            ->where('status', 'awaiting_cashier')
            ->latest()
            ->limit(4)
            ->get();
    }

    public function getPendingCount(): int
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant) {
            return 0;
        }

        return Order::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('status', 'awaiting_cashier')
            ->count();
    }

    public function getOrdersUrl(): string
    {
        try {
            return OrderResource::getUrl();
        } catch (\Throwable) {
            return url('/admin/orders');
        }
    }
}
