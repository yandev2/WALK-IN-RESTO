<?php

namespace App\Filament\Founder\Widgets;

use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Services\FounderAnalyticsService;
use Filament\Widgets\Widget;

class FounderStatsWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.founder-stats';

    protected int|string|array $columnSpan = 'full';

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $service = app(FounderAnalyticsService::class);
        $kpi = $service->getKpiData();

        return [
            'kpi' => $kpi,
            'tenantUrl' => TenantResource::getUrl(),
            'invoicesUrl' => SubscriptionInvoiceResource::getUrl(),
            'pendingInvoicesUrl' => SubscriptionInvoiceResource::getUrl().'?tableFilters[status][value]=awaiting_verification',
        ];
    }
}
