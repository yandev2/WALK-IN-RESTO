<?php

namespace App\Filament\Founder\Resources\Tenants\Pages;

use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Jobs\ForceDeleteTenantJob;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\SubscriptionPlanSync;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

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
            Action::make('resetOwnerPassword')
                ->label('Reset Password Owner')
                ->icon(Heroicon::OutlinedKey)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => "Reset Password Owner: {$this->record->name}")
                ->modalDescription(function (): string {
                    $owners = $this->record->getOwners();
                    if ($owners->isEmpty()) {
                        return "Tidak ada akun owner yang terikat pada tenant '{$this->record->name}'.";
                    }
                    if ($owners->count() === 1) {
                        $owner = $owners->first();
                        return "Password untuk akun owner '{$owner->name}' ({$owner->email}) akan dikembalikan menjadi default 'password'.";
                    }

                    return "Tenant ini memiliki {$owners->count()} akun owner. Silakan pilih akun owner yang ingin di-reset password-nya menjadi 'password'.";
                })
                ->modalSubmitActionLabel('Ya, Reset Password')
                ->form(function (): array {
                    $owners = $this->record->getOwners();
                    if ($owners->isEmpty()) {
                        return [
                            Placeholder::make('no_owner_info')
                                ->label('Pemberitahuan')
                                ->content("Tidak ada akun owner yang terikat pada tenant ini."),
                        ];
                    }

                    if ($owners->count() === 1) {
                        $owner = $owners->first();
                        return [
                            Placeholder::make('owner_info')
                                ->label('Akun Owner')
                                ->content("{$owner->name} ({$owner->email})"),
                            Placeholder::make('new_password_info')
                                ->label('Password Baru')
                                ->content('password'),
                        ];
                    }

                    $options = ['all' => 'Semua Owner (' . $owners->count() . ' akun)'] + $owners->mapWithKeys(
                        fn (User $u) => [$u->id => "{$u->name} ({$u->email})"]
                    )->all();

                    return [
                        Select::make('target_user_id')
                            ->label('Pilih Akun Owner')
                            ->options($options)
                            ->default('all')
                            ->required(),
                        Placeholder::make('new_password_info')
                            ->label('Password Baru')
                            ->content('password'),
                    ];
                })
                ->action(function (array $data): void {
                    $owners = $this->record->getOwners();
                    if ($owners->isEmpty()) {
                        Notification::make()
                            ->title('Tidak Ada Akun Owner')
                            ->body("Tidak ditemukan akun owner untuk tenant '{$this->record->name}'.")
                            ->warning()
                            ->send();

                        return;
                    }

                    $targetUserId = $data['target_user_id'] ?? null;

                    if ($owners->count() === 1 || $targetUserId === 'all' || $targetUserId === null) {
                        $targets = ($targetUserId && $targetUserId !== 'all')
                            ? $owners->where('id', (int) $targetUserId)
                            : $owners;
                    } else {
                        $targets = $owners->where('id', (int) $targetUserId);
                    }

                    foreach ($targets as $user) {
                        $user->forceFill([
                            'password' => Hash::make('password'),
                            'remember_token' => null,
                        ])->save();
                    }

                    $names = $targets->map(fn (User $u) => "{$u->name} ({$u->email})")->implode(', ');

                    Notification::make()
                        ->title('Password Owner Berhasil Direset')
                        ->body("Password untuk {$names} berhasil dikembalikan menjadi 'password'.")
                        ->success()
                        ->send();
                }),
            Action::make('forceDeleteTenant')
                ->label('Force Delete')
                ->icon(Heroicon::OutlinedTrash)
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => "Hapus Permanen Tenant: {$this->record->name}")
                ->modalDescription('PERINGATAN KERAS: Tindakan ini bersifat PERMANEN dan TIDAK DAPAT DIBATALKAN. Seluruh data transaksi, pesanan, menu, meja, QRIS, ulasan, laporan, berkas fisik di storage, dan akun staf eksklusif akan dimusnahkan. Ketik slug tenant di bawah untuk mengonfirmasi.')
                ->modalSubmitActionLabel('Ya, Hapus Permanen Seluruh Data')
                ->modalIcon(Heroicon::OutlinedExclamationTriangle)
                ->form([
                    TextInput::make('confirm_slug')
                        ->label('Ketik slug tenant untuk konfirmasi:')
                        ->helperText(fn (): string => "Ketik: {$this->record->slug}")
                        ->required()
                        ->rules([
                            fn () => function (string $attribute, $value, \Closure $fail): void {
                                if ($value !== $this->record->slug) {
                                    $fail("Slug yang Anda masukkan tidak sesuai dengan '{$this->record->slug}'.");
                                }
                            },
                        ]),
                ])
                ->action(function (): void {
                    $record = $this->record;
                    $id = $record->id;
                    $name = $record->name;
                    $slug = $record->slug;
                    $user = auth()->user();

                    // Instantly deactivate tenant
                    $record->forceFill([
                        'is_active' => false,
                        'listed_in_directory' => false,
                        'landing_enabled' => false,
                    ])->save();

                    // Dispatch background queue job
                    ForceDeleteTenantJob::dispatch($id, $name, $slug, $user);

                    Notification::make()
                        ->title('Penghapusan Tenant Sedang Diproses')
                        ->body("Proses penghapusan bersih tenant '{$name}' telah dikirim ke antrian background. Anda akan menerima notifikasi setelah selesai.")
                        ->warning()
                        ->send();

                    $this->redirect(TenantResource::getUrl('index'));
                }),
        ];
    }
}
