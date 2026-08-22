<?php

namespace App\Filament\Founder\Resources\SubscriptionInvoices\Pages;

use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionInvoices extends ListRecords
{
    protected static string $resource = SubscriptionInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
