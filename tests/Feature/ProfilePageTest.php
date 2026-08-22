<?php

namespace Tests\Feature;

use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use App\Filament\Profile\EditProfile;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_owner_can_open_and_update_profile_on_admin_panel(): void
    {
        Storage::fake('public');

        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');

        $this->get('/admin/profile')->assertOk();

        $avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => 'Owner Baru',
                'email' => 'owner-baru@resto.test',
                'currentPassword' => 'password',
                'avatar_path' => $avatar,
            ])
            ->call('updateProfileInformation')
            ->assertHasNoFormErrors();

        $owner->refresh();

        $this->assertSame('Owner Baru', $owner->name);
        $this->assertSame('owner-baru@resto.test', $owner->email);
        $this->assertTrue(filled($owner->avatar_path));
        Storage::disk('public')->assertExists($owner->avatar_path);
    }

    public function test_owner_can_update_password(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);
        $owner->forceFill(['password' => 'password'])->save();

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(EditProfile::class)
            ->fillForm([
                'currentPassword' => 'password',
                'password' => 'NewPassword123!',
                'passwordConfirmation' => 'NewPassword123!',
            ], 'passwordForm')
            ->call('updatePassword')
            ->assertHasNoFormErrors();

        $this->assertTrue(Hash::check('NewPassword123!', $owner->fresh()->password));
    }

    public function test_staff_cannot_open_profile_on_admin_panel(): void
    {
        $restaurant = $this->makeRestaurant();
        $staff = $this->makeStaff($restaurant, ['kds.view'], 'kasir');

        $this->actingAs($staff);
        Filament::setCurrentPanel('admin');

        $this->get('/admin/profile')->assertForbidden();
    }

    public function test_founder_can_open_profile_on_founder_panel(): void
    {
        $founder = $this->makeFounder();

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        $this->get(EditProfile::getUrl(panel: 'founder'))->assertOk();

        Livewire::test(EditProfile::class)
            ->fillForm([
                'name' => 'Founder Baru',
                'email' => $founder->email,
            ])
            ->call('updateProfileInformation')
            ->assertHasNoFormErrors();

        $this->assertSame('Founder Baru', $founder->fresh()->name);
    }

    public function test_owner_cannot_open_founder_profile(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('founder');

        $this->get(EditProfile::getUrl(panel: 'founder'))->assertForbidden();
    }

    public function test_owner_sees_profile_link_in_admin_shell(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->get(SubscriptionStatusPage::getUrl(panel: 'admin', tenant: $restaurant))
            ->assertOk()
            ->assertSee('fi-dropdown-list-item-label">Profil', false);
    }

    public function test_staff_does_not_see_profile_link_in_admin_shell(): void
    {
        $restaurant = $this->makeRestaurant();
        $staff = $this->makeStaff($restaurant, ['kds.view'], 'kasir');

        $this->actingAs($staff);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->get(SubscriptionStatusPage::getUrl(panel: 'admin', tenant: $restaurant))
            ->assertOk()
            ->assertDontSee('fi-dropdown-list-item-label">Profil', false);
    }
}
