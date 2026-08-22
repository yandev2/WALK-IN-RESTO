<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuItemPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class StoredFileCleanupTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_updating_menu_item_photo_deletes_old_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menu/old.jpg', 'old');
        Storage::disk('public')->put('menu/new.jpg', 'new');

        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update(['photo_path' => 'menu/old.jpg']);

        $item->update(['photo_path' => 'menu/new.jpg']);

        Storage::disk('public')->assertMissing('menu/old.jpg');
        Storage::disk('public')->assertExists('menu/new.jpg');
    }

    public function test_soft_deleting_menu_item_keeps_files_until_force_delete(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menu/primary.jpg', 'primary');
        Storage::disk('public')->put('menu/extra.jpg', 'extra');

        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update(['photo_path' => 'menu/primary.jpg']);

        MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'menu/extra.jpg',
            'sort_order' => 0,
        ]);

        $item->delete();

        Storage::disk('public')->assertExists('menu/primary.jpg');
        Storage::disk('public')->assertExists('menu/extra.jpg');

        $item->forceDelete();

        Storage::disk('public')->assertMissing('menu/primary.jpg');
        Storage::disk('public')->assertMissing('menu/extra.jpg');
    }

    public function test_deleting_menu_item_photo_removes_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('menu/extra.jpg', 'extra');

        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->findOrFail($world['item']->id);

        $photo = MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'menu/extra.jpg',
            'sort_order' => 0,
        ]);

        $photo->delete();

        Storage::disk('public')->assertMissing('menu/extra.jpg');
    }

    public function test_updating_user_avatar_deletes_old_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('users/avatars/old.jpg', 'old');
        Storage::disk('public')->put('users/avatars/new.jpg', 'new');

        $user = User::query()->create([
            'name' => 'Staff',
            'email' => 'staff@example.test',
            'username' => 'staff',
            'password' => Hash::make('password'),
            'avatar_path' => 'users/avatars/old.jpg',
            'is_active' => true,
        ]);

        $user->update(['avatar_path' => 'users/avatars/new.jpg']);

        Storage::disk('public')->assertMissing('users/avatars/old.jpg');
        Storage::disk('public')->assertExists('users/avatars/new.jpg');
    }
}
