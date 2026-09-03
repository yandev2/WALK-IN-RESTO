<?php

namespace Tests\Feature;

use App\Livewire\Landing\RestaurantMenuCatalog;
use App\Models\MenuItem;
use App\Models\MenuItemPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class MenuItemPhotosTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_menu_item_photo_urls_include_primary_and_extras(): void
    {
        $world = $this->createGuestRestaurant();

        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update(['photo_path' => 'menu/primary.jpg']);

        MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'menu/extra-1.jpg',
            'sort_order' => 0,
        ]);

        MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'menu/extra-2.jpg',
            'sort_order' => 1,
        ]);

        $item->load('photos');

        $urls = $item->photoUrls();

        $this->assertCount(3, $urls);
        $this->assertStringContainsString('primary.jpg', $urls[0]);
        $this->assertStringContainsString('extra-1.jpg', $urls[1]);
        $this->assertStringContainsString('extra-2.jpg', $urls[2]);
    }

    public function test_dish_card_renders_thumbnails_and_image_preview_for_multiple_photos(): void
    {
        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update(['photo_path' => 'https://example.com/primary.jpg']);

        MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'https://example.com/extra-1.jpg',
            'sort_order' => 0,
        ]);

        Livewire::test(RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->assertOk()
            ->assertSee('dish-card-thumbs', false)
            ->assertSee('absolute bottom-2 right-2', false)
            ->assertSee('imagePreview', false)
            ->assertSee('openPreview', false)
            ->assertSee('image-preview-modal', false)
            ->assertSee('https://example.com/primary.jpg', false)
            ->assertSee('https://example.com/extra-1.jpg', false)
            ->assertDontSee('h-1.5 w-1.5 rounded-full', false);

        $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->assertSee('dish-card-thumbs', false)
            ->assertSee('openPreview', false);
    }
}
