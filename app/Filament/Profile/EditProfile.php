<?php

namespace App\Filament\Profile;

use App\Models\User;
use App\Support\FilamentTenantTheme;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Filament\Support\Icons\Heroicon;
use Ipatco\FilamentProfile\Pages\EditProfile as BaseEditProfile;
use Spatie\Permission\PermissionRegistrar;

class EditProfile extends BaseEditProfile
{
    public static function plugin(): FilamentProfilePlugin
    {
        return FilamentProfilePlugin::make()
            ->showOnDropdown()
            ->showOnSideNav()
            ->icon(Heroicon::OutlinedUserCircle)
            ->label('Profil')
            ->profilePage(static::class);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return match (Filament::getCurrentPanel()?->getId()) {
            'founder' => $user->isPlatformOperator(),
            'admin' => $user->isRestaurantOwner(),
            default => false,
        };
    }

    public function mount(): void
    {
        $this->ensureTenantContext();

        parent::mount();
    }

    public function getDeleteAccountSection(): Component
    {
        return parent::getDeleteAccountSection()->hidden();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $avatar = $data['avatar_path'] ?? null;

        if (is_array($avatar)) {
            $data['avatar_path'] = $avatar[array_key_first($avatar)] ?? null;
        }

        return $data;
    }

    private function ensureTenantContext(): void
    {
        $panel = Filament::getCurrentPanel();

        if (! $panel?->hasTenancy() || Filament::getTenant()) {
            return;
        }

        $user = auth()->user();

        if (! $user instanceof User) {
            return;
        }

        $tenant = $user->getTenants($panel)->first();

        if (! $tenant) {
            return;
        }

        Filament::setTenant($tenant);
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->getKey());
        FilamentTenantTheme::apply($tenant);
    }
}
