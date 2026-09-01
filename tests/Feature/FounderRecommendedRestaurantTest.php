<?php

namespace Tests\Feature;

use App\Filament\Founder\Resources\Tenants\Pages\EditTenant;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FounderRecommendedRestaurantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_restaurant_recommended_scope_and_attribute(): void
    {
        $restaurant = Restaurant::query()->firstOrFail();
        $this->assertFalse($restaurant->is_recommended);
        $this->assertFalse($restaurant->isRecommended());

        $restaurant->update(['is_recommended' => true]);
        $restaurant->refresh();

        $this->assertTrue($restaurant->is_recommended);
        $this->assertTrue($restaurant->isRecommended());
        $this->assertTrue(Restaurant::query()->recommended()->where('id', $restaurant->id)->exists());
    }

    public function test_founder_can_toggle_is_recommended_on_tenant(): void
    {
        $founder = User::query()->where('email', 'admin@resto.test')->firstOrFail();

        $restaurant = Restaurant::query()->firstOrFail();
        $restaurant->update(['is_recommended' => false]);

        $this->actingAs($founder);
        \Filament\Facades\Filament::setCurrentPanel('founder');

        Livewire::test(EditTenant::class, ['record' => $restaurant->getKey()])
            ->assertSchemaComponentVisible('is_recommended')
            ->fillForm([
                'is_recommended' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($restaurant->fresh()->is_recommended);
    }
}
