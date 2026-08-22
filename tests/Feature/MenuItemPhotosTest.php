<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\MenuItemPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
