<?php

namespace App\Filament\Founder\Resources\Tenants\Pages;

use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Models\Restaurant;
use App\Services\SubscriptionPlanSync;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected ?string $previousPlanCode = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->previousPlanCode = $this->record->plan_code;

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Restaurant $restaurant */
        $restaurant = $this->record;

        if ($restaurant->plan_code && $restaurant->plan_code !== $this->previousPlanCode) {
            app(SubscriptionPlanSync::class)->apply($restaurant, $restaurant->plan_code);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('forceActivate')
                ->label('Aktifkan 1 tahun')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->forceFill([
                        'subscription_status' => SubscriptionStatus::Active,
                        'subscribed_until' => now()->addYear(),
                        'grace_ends_at' => null,
                    ])->save();

                    Notification::make()->title('Langganan diaktifkan.')->success()->send();
                    $this->record->refresh();
                    $this->fillForm();
                }),
            Action::make('forceExpire')
                ->label('Paksa expired')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->forceFill([
                        'subscription_status' => SubscriptionStatus::Expired,
                        'subscribed_until' => now(),
                    ])->save();

                    Notification::make()->title('Tenant di-expired.')->success()->send();
                    $this->record->refresh();
                    $this->fillForm();
                }),
        ];
    }
}
