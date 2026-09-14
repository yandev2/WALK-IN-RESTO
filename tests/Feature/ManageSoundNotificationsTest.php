<?php

namespace Tests\Feature;

use App\Filament\Founder\Pages\ManageSoundNotifications;
use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManageSoundNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_sound_notification_paths_deletes_old_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('platform/sounds/cashier-old.mp3', 'cashier-old-audio');
        Storage::disk('public')->put('platform/sounds/kitchen-old.mp3', 'kitchen-old-audio');
        Storage::disk('public')->put('platform/sounds/cashier-new.mp3', 'cashier-new-audio');
        Storage::disk('public')->put('platform/sounds/kitchen-new.mp3', 'kitchen-new-audio');

        $setting = PlatformSetting::current();
        $setting->update([
            'cashier_sound_path' => 'platform/sounds/cashier-old.mp3',
            'kitchen_sound_path' => 'platform/sounds/kitchen-old.mp3',
        ]);

        Storage::disk('public')->assertExists('platform/sounds/cashier-old.mp3');
        Storage::disk('public')->assertExists('platform/sounds/kitchen-old.mp3');

        // Update to new audio files
        $setting->update([
            'cashier_sound_path' => 'platform/sounds/cashier-new.mp3',
            'kitchen_sound_path' => 'platform/sounds/kitchen-new.mp3',
        ]);

        // Old audio files must be deleted from storage
        Storage::disk('public')->assertMissing('platform/sounds/cashier-old.mp3');
        Storage::disk('public')->assertMissing('platform/sounds/kitchen-old.mp3');
        Storage::disk('public')->assertExists('platform/sounds/cashier-new.mp3');
        Storage::disk('public')->assertExists('platform/sounds/kitchen-new.mp3');

        // Clear audio files (set to null) -> old files should also be deleted
        $setting->update([
            'cashier_sound_path' => null,
            'kitchen_sound_path' => null,
        ]);

        Storage::disk('public')->assertMissing('platform/sounds/cashier-new.mp3');
        Storage::disk('public')->assertMissing('platform/sounds/kitchen-new.mp3');
    }

    public function test_sound_url_helpers_return_uploaded_url_and_fallback_to_null_when_empty(): void
    {
        Storage::fake('public');

        $setting = PlatformSetting::current();
        $setting->update([
            'cashier_sound_path' => null,
            'kitchen_sound_path' => null,
        ]);

        // When null, if no static mp3 exists, returns null (client will fallback to web audio synthesizer)
        $cashierUrl = PlatformSetting::cashierSoundUrl();
        $kitchenUrl = PlatformSetting::kitchenSoundUrl();

        // If file in public/sounds exists, it returns asset, otherwise null
        if (! file_exists(public_path('sounds/cashier-order.mp3'))) {
            $this->assertNull($cashierUrl);
        }
        if (! file_exists(public_path('sounds/kitchen-order.mp3'))) {
            $this->assertNull($kitchenUrl);
        }

        // When custom path is set
        $setting->update([
            'cashier_sound_path' => 'platform/sounds/custom-cashier.mp3',
            'kitchen_sound_path' => 'platform/sounds/custom-kitchen.mp3',
        ]);

        $this->assertStringContainsString('platform/sounds/custom-cashier.mp3', (string) PlatformSetting::cashierSoundUrl());
        $this->assertStringContainsString('platform/sounds/custom-kitchen.mp3', (string) PlatformSetting::kitchenSoundUrl());
    }

    public function test_founder_can_access_and_save_sound_notifications_page(): void
    {
        Storage::fake('public');
        $founder = $this->founderUser();

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        $fileCashier = \Illuminate\Http\UploadedFile::fake()->create('test-cashier.mp3', 100, 'audio/mpeg');
        $fileKitchen = \Illuminate\Http\UploadedFile::fake()->create('test-kitchen.mp3', 100, 'audio/mpeg');

        Livewire::test(ManageSoundNotifications::class)
            ->assertOk()
            ->assertSee('Audio Notifikasi (Kasir & Dapur)')
            ->assertSee('File Audio Bel Kasir')
            ->assertSee('File Audio Bel Dapur')
            ->fillForm([
                'cashier_sound_path' => $fileCashier,
                'kitchen_sound_path' => $fileKitchen,
            ])
            ->call('save')
            ->assertNotified('Pengaturan audio notifikasi berhasil disimpan');

        $setting = PlatformSetting::current();
        $this->assertNotNull($setting->cashier_sound_path);
        $this->assertNotNull($setting->kitchen_sound_path);
        Storage::disk('public')->assertExists($setting->cashier_sound_path);
        Storage::disk('public')->assertExists($setting->kitchen_sound_path);
    }

    public function test_founder_can_upload_wav_audio_files_successfully(): void
    {
        Storage::fake('public');
        $founder = $this->founderUser();

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        // mixkit-software-interface-start-2574.wav with audio/x-wav MIME type
        $fileWavX = \Illuminate\Http\UploadedFile::fake()->create('mixkit-software-interface-start-2574.wav', 414, 'audio/x-wav');
        $fileWav = \Illuminate\Http\UploadedFile::fake()->create('kitchen-bell.wav', 300, 'audio/wav');

        Livewire::test(ManageSoundNotifications::class)
            ->assertOk()
            ->fillForm([
                'cashier_sound_path' => $fileWavX,
                'kitchen_sound_path' => $fileWav,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified('Pengaturan audio notifikasi berhasil disimpan');

        $setting = PlatformSetting::current();
        $this->assertNotNull($setting->cashier_sound_path);
        $this->assertNotNull($setting->kitchen_sound_path);
        Storage::disk('public')->assertExists($setting->cashier_sound_path);
        Storage::disk('public')->assertExists($setting->kitchen_sound_path);
    }

    public function test_non_operator_cannot_access_sound_notifications_page(): void
    {
        $regularUser = User::factory()->create();

        $this->actingAs($regularUser);
        $this->assertFalse(ManageSoundNotifications::canAccess());
    }

    private function founderUser(): User
    {
        setPermissionsTeamId(0);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $user = User::factory()->create([
            'email' => 'founder@resto.test',
        ]);
        $user->assignRole('super_admin');

        return $user;
    }
}
