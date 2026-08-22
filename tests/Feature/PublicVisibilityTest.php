<?php

namespace Tests\Feature;

use App\Livewire\Landing\RestaurantDirectory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class PublicVisibilityTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    public function test_unlisted_restaurant_is_hidden_from_directory(): void
    {
        $visible = $this->makeRestaurant([
            'name' => 'Resto Terlihat',
            'slug' => 'resto-terlihat',
            'listed_in_directory' => true,
        ]);
        $hidden = $this->makeRestaurant([
            'name' => 'Resto Tersembunyi',
            'slug' => 'resto-tersembunyi',
            'listed_in_directory' => false,
            'landing_enabled' => true,
        ]);

        Livewire::test(RestaurantDirectory::class)
            ->assertSee('Resto Terlihat', false)
            ->assertDontSee('Resto Tersembunyi', false);

        $this->get(route('landing.show', $hidden))->assertOk();
        $this->get(route('landing.show', $visible))->assertOk();
    }

    public function test_disabled_landing_returns_404(): void
    {
        $restaurant = $this->makeRestaurant([
            'name' => 'Resto Off',
            'slug' => 'resto-off',
            'listed_in_directory' => true,
            'landing_enabled' => false,
            'is_active' => true,
        ]);

        $this->get(route('landing.show', $restaurant))->assertNotFound();
        $this->get(route('landing.menu', $restaurant))->assertNotFound();
        $this->getJson('/api/v1/restaurants/'.$restaurant->slug)->assertNotFound();
    }
}
