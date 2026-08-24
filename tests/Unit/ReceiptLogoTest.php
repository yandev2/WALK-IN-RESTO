<?php

namespace Tests\Unit;

use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Support\ReceiptLogo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReceiptLogoTest extends TestCase
{
    use RefreshDatabase;

    private string $png;

    protected function setUp(): void
    {
        parent::setUp();

        $this->png = (string) base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==', true);
    }

    public function test_uses_restaurant_logo_before_platform(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('restaurants/logos/resto.png', $this->png);
        Storage::disk('public')->put('platform/logo/platform.png', $this->png);

        PlatformSetting::current()->update(['logo_path' => 'platform/logo/platform.png']);

        $restaurant = Restaurant::query()->create([
            'name' => 'Logo Resto',
            'slug' => 'logo-resto',
            'logo_path' => 'restaurants/logos/resto.png',
            'is_active' => true,
        ]);

        $this->assertSame(
            'data:image/png;base64,'.base64_encode($this->png),
            ReceiptLogo::dataUri($restaurant),
        );
    }

    public function test_falls_back_to_platform_logo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('platform/logo/platform.png', $this->png);
        PlatformSetting::current()->update(['logo_path' => 'platform/logo/platform.png']);

        $restaurant = Restaurant::query()->create([
            'name' => 'No Logo Resto',
            'slug' => 'no-logo-resto',
            'is_active' => true,
        ]);

        $this->assertSame(
            'data:image/png;base64,'.base64_encode($this->png),
            ReceiptLogo::dataUri($restaurant),
        );
    }

    public function test_falls_back_to_bundled_platform_asset(): void
    {
        Storage::fake('public');

        $restaurant = Restaurant::query()->create([
            'name' => 'Fallback Resto',
            'slug' => 'fallback-resto',
            'is_active' => true,
        ]);

        $uri = ReceiptLogo::dataUri($restaurant);

        $this->assertNotNull($uri);
        $this->assertStringStartsWith('data:image/png;base64,', $uri);
        $this->assertFileExists(public_path(ReceiptLogo::FALLBACK_PATH));
    }

    public function test_loads_remote_restaurant_logo(): void
    {
        Http::fake([
            'https://cdn.example.test/logo.png' => Http::response($this->png, 200),
        ]);

        $restaurant = Restaurant::query()->create([
            'name' => 'Remote Logo',
            'slug' => 'remote-logo',
            'logo_path' => 'https://cdn.example.test/logo.png',
            'is_active' => true,
        ]);

        $this->assertSame(
            'data:image/png;base64,'.base64_encode($this->png),
            ReceiptLogo::dataUri($restaurant),
        );
    }
}
