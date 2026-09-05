<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Livewire\Auth\RegisterRestaurant;
use App\Models\OutletOperatingHour;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RestaurantRegistrationStepperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('public');
    }

    public function test_full_5_step_onboarding_wizard_with_simple_mode_visuals_and_gps(): void
    {
        $category = RestaurantCategory::query()->create([
            'name' => 'Sunda & Prasmanan',
            'slug' => 'sunda-prasmanan',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $logoFile = UploadedFile::fake()->image('logo.png', 400, 400);
        $heroFile = UploadedFile::fake()->image('hero.jpg', 1600, 900);
        $qrisFile = UploadedFile::fake()->image('qris.png', 500, 500);

        Livewire::test(RegisterRestaurant::class)
            // Step 1: Akun
            ->set('name', 'Budi Santoso')
            ->set('email', 'budi@prasmanan.test')
            ->set('password', 'secret1234')
            ->set('password_confirmation', 'secret1234')
            ->call('nextFromAccount')
            ->assertSet('step', 2)

            // Step 2: Restoran
            ->set('restaurant_name', 'Prasmanan Bu Imas')
            ->set('slug', 'prasmanan-bu-imas')
            ->call('nextFromRestaurant')
            ->assertSet('step', 3)

            // Step 3: Paket
            ->call('selectPlan', PlanCode::ManagementKds->value)
            ->assertSet('plan_code', PlanCode::ManagementKds->value)
            ->call('nextFromPlan')
            ->assertSet('step', 4)

            // Step 4: Mode Kasir & Visual Brand
            ->set('simple_mode', true)
            ->set('logo', $logoFile)
            ->set('hero_banner', $heroFile)
            ->set('qris_image', $qrisFile)
            ->call('applyColorPreset', '#ef4444', '#f97316')
            ->assertSet('primary_color', '#ef4444')
            ->assertSet('accent_color', '#f97316')
            ->call('nextFromVisual')
            ->assertSet('step', 5)

            // Step 5: Info & Lokasi
            ->call('toggleCategory', $category->id)
            ->assertSet('category_ids', [$category->id])
            ->set('price_level', 2)
            ->call('toggleFacility', 'wifi')
            ->call('toggleFacility', 'parking')
            ->assertSet('facilities', ['wifi', 'parking'])
            ->set('headline', 'Prasmanan Khas Sunda Terlengkap & Terlezat')
            ->set('about_text', 'Menyajikan aneka masakan rumahan khas Sunda dengan konsep prasmanan dan lesehan nyaman.')
            ->set('phone', '081234567890')
            ->set('instagram', '@prasmanan.bu.imas')
            ->set('address', 'Jl. Balonggede No. 38, Bandung')
            ->set('latitude', -6.9248234)
            ->set('longitude', 107.6052123)
            ->set('opens_at', '09:00')
            ->set('closes_at', '21:30')
            ->call('register')
            ->assertRedirect('/admin/prasmanan-bu-imas');

        // Verify Restaurant
        $restaurant = Restaurant::query()->where('slug', 'prasmanan-bu-imas')->first();
        $this->assertSame(2, $restaurant->price_level);
        $this->assertSame('Prasmanan Bu Imas', $restaurant->legal_name);
        $this->assertTrue($restaurant->hasFacility('wifi'));
        $this->assertTrue($restaurant->hasFacility('parking'));
        $this->assertFalse($restaurant->hasFacility('outdoor'));
        $this->assertNotNull($restaurant->logo_path);
        Storage::disk('public')->assertExists($restaurant->logo_path);
        $this->assertTrue($restaurant->categories->contains($category->id));

        // Verify Outlet
        $outlet = $restaurant->defaultOutlet;
        $this->assertNotNull($outlet);
        $this->assertTrue($outlet->simple_mode);
        $this->assertSame('Jl. Balonggede No. 38, Bandung', $outlet->address);
        $this->assertSame('081234567890', $outlet->phone);
        $this->assertSame('@prasmanan.bu.imas', $outlet->instagram);
        $this->assertSame('https://www.instagram.com/prasmanan.bu.imas', $outlet->instagramUrl());
        $this->assertEquals(-6.9248234, (float) $outlet->latitude);
        $this->assertEquals(107.6052123, (float) $outlet->longitude);
        $this->assertNotNull($outlet->qris_image_path);
        Storage::disk('public')->assertExists($outlet->qris_image_path);

        // Verify Operating Hours
        $hour = OutletOperatingHour::query()->where('outlet_id', $outlet->id)->first();
        $this->assertNotNull($hour);
        $this->assertSame('09:00:00', $hour->opens_at);
        $this->assertSame('21:30:00', $hour->closes_at);

        // Verify CMS Profile & Maps
        $cms = $restaurant->cmsProfile;
        $this->assertNotNull($cms);
        $this->assertSame('Prasmanan Khas Sunda Terlengkap & Terlezat', $cms->headline);
        $this->assertStringContainsString('Menyajikan aneka masakan rumahan khas Sunda', $cms->about_html);
        $this->assertSame('#EF4444', $cms->primary_color);
        $this->assertSame('#F97316', $cms->accent_color);
        $this->assertNotNull($cms->hero_image_path);
        Storage::disk('public')->assertExists($cms->hero_image_path);
        $this->assertStringContainsString('-6.9248234', $cms->map_embed_url);
        $this->assertStringContainsString('maps.google.com', $cms->map_embed_url);
        $this->assertStringContainsString('-6.9248234', $cms->cta_url);

        // Verify User & Auth
        $user = User::query()->where('email', 'budi@prasmanan.test')->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }

    public function test_skipping_visual_and_info_still_provisions_complete_restaurant(): void
    {
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'Siti Rahma')
            ->set('email', 'siti@resto.test')
            ->set('password', 'secret1234')
            ->set('password_confirmation', 'secret1234')
            ->call('nextFromAccount')
            ->set('restaurant_name', 'Warung Cepat')
            ->set('slug', 'warung-cepat')
            ->call('nextFromRestaurant')
            ->call('selectPlan', PlanCode::LandingOnly->value)
            ->call('nextFromPlan')
            ->assertSet('step', 4)
            ->call('skipVisual')
            ->assertSet('step', 5)
            ->call('register')
            ->assertRedirect('/admin/warung-cepat');

        $restaurant = Restaurant::query()->where('slug', 'warung-cepat')->first();
        $this->assertNotNull($restaurant);
        $this->assertSame('Warung Cepat', $restaurant->legal_name);
        $this->assertSame(1, $restaurant->price_level);

        $outlet = $restaurant->defaultOutlet;
        $this->assertNotNull($outlet);
        $this->assertFalse($outlet->simple_mode);

        $cms = $restaurant->cmsProfile;
        $this->assertNotNull($cms);
        $this->assertSame('Warung Cepat', $cms->headline);
        $this->assertStringContainsString('Selamat datang di Warung Cepat', $cms->about_html);
    }

    public function test_category_and_facility_toggle_operations(): void
    {
        $component = Livewire::test(RegisterRestaurant::class)
            ->call('toggleFacility', 'wifi')
            ->assertSet('facilities', ['wifi'])
            ->call('toggleFacility', 'parking')
            ->assertSet('facilities', ['wifi', 'parking'])
            ->call('toggleFacility', 'wifi')
            ->assertSet('facilities', ['parking']);

        $component->call('toggleCategory', 10)
            ->assertSet('category_ids', [10])
            ->call('toggleCategory', 20)
            ->assertSet('category_ids', [10, 20])
            ->call('toggleCategory', 10)
            ->assertSet('category_ids', [20]);
    }

    public function test_invalid_color_and_coordinates_fail_validation(): void
    {
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'Test Error')
            ->set('email', 'error@test.test')
            ->set('password', 'password12')
            ->set('password_confirmation', 'password12')
            ->set('restaurant_name', 'Resto Error')
            ->set('slug', 'resto-error')
            ->set('plan_code', PlanCode::LandingOnly->value)
            ->set('primary_color', 'not-a-color')
            ->set('latitude', 150) // invalid latitude (must be between -90 and 90)
            ->call('register')
            ->assertHasErrors(['primary_color', 'latitude']);
    }

    public function test_landing_only_plan_hides_cashier_mode_and_qris_in_step_4(): void
    {
        // 1. Landing Only: Mode Kasir and QRIS are hidden, Stepper says '4. Tampilan'
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'User Landing')
            ->set('email', 'landing@resto.test')
            ->set('password', 'secret1234')
            ->set('password_confirmation', 'secret1234')
            ->call('nextFromAccount')
            ->set('restaurant_name', 'Resto Landing Saja')
            ->set('slug', 'resto-landing-saja')
            ->call('nextFromRestaurant')
            ->call('selectPlan', PlanCode::LandingOnly->value)
            ->call('nextFromPlan')
            ->assertSet('step', 4)
            ->assertSee('4. Tampilan', false)
            ->assertDontSee('Pilih Mode Operasional Kasir', false)
            ->assertDontSee('QRIS Pembayaran Outlet', false)
            ->assertSee('Logo Restoran (1:1)', false)
            ->assertSee('Banner Utama Landing (16:9)', false);

        // 2. Management KDS: Mode Kasir and QRIS are visible, Stepper says '4. Mode & Tampilan'
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'User Management')
            ->set('email', 'mgmt@resto.test')
            ->set('password', 'secret1234')
            ->set('password_confirmation', 'secret1234')
            ->call('nextFromAccount')
            ->set('restaurant_name', 'Resto Management')
            ->set('slug', 'resto-mgmt')
            ->call('nextFromRestaurant')
            ->call('selectPlan', PlanCode::ManagementKds->value)
            ->call('nextFromPlan')
            ->assertSet('step', 4)
            ->assertSee('4. Mode & Tampilan')
            ->assertSee('Pilih Mode Operasional Kasir', false)
            ->assertSee('QRIS Pembayaran Outlet', false);
    }
}
