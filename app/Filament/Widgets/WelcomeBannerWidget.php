<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use App\Models\User;
use App\Support\CmsMedia;
use App\Support\RestaurantTheme;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class WelcomeBannerWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = [
        'default' => 'full',
    ];

    protected string $view = 'filament.widgets.welcome-banner';

    public static function canView(): bool
    {
        return auth()->user() instanceof User;
    }

    /**
     * @return array{
     *     greeting: string,
     *     user_name: string,
     *     user_initials: string,
     *     avatar_url: string|null,
     *     restaurant_name: string|null,
     *     timezone: string,
     *     timezone_label: string,
     *     theme: array{primary: string, primary_dark: string, accent: string},
     * }
     */
    public function getBannerData(): array
    {
        $user = auth()->user();
        $restaurant = Filament::getTenant();
        $timezone = $restaurant instanceof Restaurant
            ? ($restaurant->timezone ?: 'Asia/Jakarta')
            : 'Asia/Jakarta';

        $name = $user instanceof User
            ? (filled($user->name) ? $user->name : ($user->username ?? 'Pengguna'))
            : 'Pengguna';

        $theme = RestaurantTheme::for($restaurant instanceof Restaurant ? $restaurant : null);

        $pendingOrders = 0;
        $publicUrl = null;
        $orderUrl = null;
        $menuUrl = null;

        if ($restaurant instanceof Restaurant) {
            $pendingOrders = \App\Models\Order::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 'awaiting_cashier')
                ->count();

            try {
                $publicUrl = route('landing.show', $restaurant);
            } catch (\Throwable) {
                $publicUrl = url('/');
            }
        }

        try {
            $orderUrl = \App\Filament\Resources\Orders\OrderResource::getUrl();
            $menuUrl = \App\Filament\Resources\MenuItems\MenuItemResource::getUrl();
        } catch (\Throwable) {
            // fallback
        }

        $hasOverdueInvoice = $restaurant instanceof Restaurant
            && ($restaurant->hasOverdueCashierInvoice() || $restaurant->hasUnpaidOverdueInvoice());

        $isKdsActive = ! $hasOverdueInvoice && SubscriptionAccess::allows('operations');

        $billingUrl = null;
        try {
            $billingUrl = \App\Filament\Pages\SubscriptionStatus::getUrl();
        } catch (\Throwable) {
            // fallback
        }

        return [
            'greeting' => $this->greetingFor($timezone),
            'user_name' => $name,
            'user_initials' => $this->initialsFor($name),
            'avatar_url' => $user instanceof User ? CmsMedia::url($user->avatar_path) : null,
            'restaurant_name' => $restaurant instanceof Restaurant ? $restaurant->name : null,
            'timezone' => $timezone,
            'timezone_label' => $this->timezoneLabel($timezone),
            'theme' => $theme,
            'pending_orders' => $pendingOrders,
            'public_url' => $publicUrl,
            'order_url' => $orderUrl,
            'menu_url' => $menuUrl,
            'billing_url' => $billingUrl,
            'is_kds_active' => $isKdsActive,
            'has_overdue_invoice' => $hasOverdueInvoice,
            'role_name' => $user instanceof User ? ($user->isRestaurantOwner() ? 'Owner Restoran' : 'Staff Operasional') : 'Pengguna',
        ];
    }

    private function timezoneLabel(string $timezone): string
    {
        return match ($timezone) {
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA',
            'Asia/Jayapura' => 'WIT',
            default => str_replace('_', ' ', $timezone),
        };
    }

    private function greetingFor(string $timezone): string
    {
        $hour = (int) Carbon::now($timezone)->format('G');

        return match (true) {
            $hour >= 5 && $hour < 11 => 'Selamat pagi',
            $hour >= 11 && $hour < 15 => 'Selamat siang',
            $hour >= 15 && $hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }

    private function initialsFor(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];

        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1).substr($parts[1], 0, 1));
        }

        return strtoupper(substr($name, 0, min(2, strlen($name))));
    }
}
