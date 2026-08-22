<?php

namespace App\Filament\Founder\Widgets;

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FounderStatsWidget extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Trial', Restaurant::query()->where('subscription_status', SubscriptionStatus::Trial)->count()),
            Stat::make('Grace', Restaurant::query()->where('subscription_status', SubscriptionStatus::Grace)->count()),
            Stat::make('Expired', Restaurant::query()->where('subscription_status', SubscriptionStatus::Expired)->count()),
            Stat::make(
                'Menunggu verifikasi',
                SubscriptionInvoice::query()->where('status', InvoiceStatus::AwaitingVerification)->count(),
            ),
        ];
    }
}
