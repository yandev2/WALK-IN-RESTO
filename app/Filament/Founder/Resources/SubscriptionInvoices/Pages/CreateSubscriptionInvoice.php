<?php

namespace App\Filament\Founder\Resources\SubscriptionInvoices\Pages;

use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Models\Restaurant;
use App\Services\SubscriptionInvoiceService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSubscriptionInvoice extends CreateRecord
{
    protected static string $resource = SubscriptionInvoiceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $restaurant = Restaurant::query()->findOrFail($data['restaurant_id']);

        return app(SubscriptionInvoiceService::class)->createManual(
            $restaurant,
            $data['plan_code'],
            auth()->user(),
            isset($data['billing_months']) ? (int) $data['billing_months'] : 1,
            $data['admin_notes'] ?? null,
        );
    }
}
