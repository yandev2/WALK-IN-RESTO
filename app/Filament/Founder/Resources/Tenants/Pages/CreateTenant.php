<?php

namespace App\Filament\Founder\Resources\Tenants\Pages;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Services\RestaurantProvisioner;
use App\Services\SubscriptionPlanSync;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['plan_code'] ??= PlanCode::ManagementKds->value;
        $data['subscription_status'] ??= SubscriptionStatus::Trial->value;
        $data['timezone'] ??= 'Asia/Jakarta';
        $data['currency'] ??= 'IDR';

        if (($data['subscription_status'] ?? null) === SubscriptionStatus::Trial->value) {
            $data['trial_ends_at'] ??= now()->addDays(PlatformSetting::trialDays());
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Restaurant $restaurant */
        $restaurant = $this->record;

        app(RestaurantProvisioner::class)->provision(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );
    }
}
