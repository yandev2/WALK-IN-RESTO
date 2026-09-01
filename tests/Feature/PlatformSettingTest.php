<?php

namespace Tests\Feature;

use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use App\Livewire\Landing\RestaurantDirectory;
use App\Models\Facility;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Support\RestaurantTheme;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class PlatformSettingTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    public function test_default_platform_setting_colors_directory_theme(): void
    {
        $theme = RestaurantTheme::for(null);

        $this->assertSame('#F97316', $theme['primary']);
        $this->assertSame(RestaurantTheme::lighten('#F97316', 0.18), $theme['accent']);
        $this->assertSame(RestaurantTheme::darken('#F97316', 0.14), $theme['primary_dark']);
    }

    public function test_platform_primary_color_updates_directory_theme(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#112233']);

        $theme = RestaurantTheme::for(null);

        $this->assertSame('#112233', $theme['primary']);
        $this->assertSame(RestaurantTheme::lighten('#112233', 0.18), $theme['accent']);
    }

    public function test_tenant_theme_does_not_use_platform_primary(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#112233']);

        $restaurant = Restaurant::query()->create([
            'name' => 'Tenant Theme',
            'slug' => 'tenant-theme',
            'is_active' => true,
        ]);

        $theme = RestaurantTheme::for($restaurant);

        $this->assertSame('#F97316', $theme['primary']);
        $this->assertSame('#FB923C', $theme['accent']);
    }

    public function test_home_uses_platform_copy_and_cta(): void
    {
        PlatformSetting::current()->update([
            'site_name' => 'RestoKita',
            'hero_eyebrow' => 'Datang langsung',
            'hero_title' => 'Cari resto favorit di kota ini',
            'hero_highlight' => 'favorit',
            'hero_subtitle' => 'Subtitle khusus dari Founder.',
            'cta_register_label' => 'Gabung mitra',
            'search_placeholder' => 'Cari resto atau menu andalan...',
            'search_button_label' => 'Temukan',
            'location_cta_label' => 'Pakai lokasi saya',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('RestoKita', false)
            ->assertSee('Datang langsung', false)
            ->assertSee('Cari resto', false)
            ->assertSee('favorit', false)
            ->assertSee('Subtitle khusus dari Founder.', false)
            ->assertSee('Gabung mitra', false)
            ->assertSee('Cari resto atau menu andalan...', false)
            ->assertSee('Temukan', false)
            ->assertSee('Pakai lokasi saya', false)
            ->assertDontSee('RestoTerdekat', false)
            ->assertDontSee('Daftarkan restoran', false);
    }

    public function test_home_footer_uses_defaults_with_site_and_year_tokens(): void
    {
        $year = (string) now()->year;

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('directory-footer', false)
            ->assertSee('lg:grid-cols-12', false)
            ->assertSee('lg:col-span-6', false)
            ->assertSee('lg:col-span-2', false)
            ->assertSee('max-w-xl', false)
            ->assertSee((string) config('subscription.contact_email'), false)
            ->assertSee("© {$year} RestoTerdekat. Semua hak dilindungi.", false)
            ->assertSee('RestoTerdekat membantu tamu menemukan restoran terdekat', false);
    }

    public function test_home_footer_uses_platform_copy_and_optional_links(): void
    {
        PlatformSetting::current()->update([
            'site_name' => 'RestoKita',
            'footer_about' => 'Tentang {site} dari Founder.',
            'footer_email' => 'halo@restokita.id',
            'footer_phone' => '081234567890',
            'footer_address' => 'Jakarta Selatan',
            'footer_instagram' => 'restokita',
            'footer_copyright' => 'Hak cipta {year} {site}',
            'footer_privacy_url' => 'https://example.com/privasi',
            'footer_terms_url' => 'https://example.com/syarat',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Tentang RestoKita dari Founder.', false)
            ->assertSee('lg:col-span-2', false)
            ->assertSee('halo@restokita.id', false)
            ->assertSee('081234567890', false)
            ->assertSee('https://wa.me/6281234567890', false)
            ->assertSee('Jakarta Selatan', false)
            ->assertSee('https://www.instagram.com/restokita', false)
            ->assertSee('https://example.com/privasi', false)
            ->assertSee('https://example.com/syarat', false)
            ->assertSee('Hak cipta '.now()->year.' RestoKita', false)
            ->assertDontSee('founder@restoterdekat.id', false);
    }

    public function test_home_header_falls_back_to_icon_and_site_name_without_logo(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('RestoTerdekat', false)
            ->assertSee('inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary', false)
            ->assertSee('font-display text-lg font-bold text-body', false);
    }

    public function test_home_header_uses_platform_logo_when_set(): void
    {
        PlatformSetting::current()->update([
            'logo_path' => 'https://example.com/platform-logo.png',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://example.com/platform-logo.png', false)
            ->assertSee('h-9 w-auto max-w-[220px] object-contain', false)
            ->assertDontSee('inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary', false)
            ->assertDontSee('font-display text-lg font-bold text-body', false);
    }

    public function test_home_hero_uses_full_bleed_banner_style(): void
    {
        PlatformSetting::current()->update([
            'hero_image_path' => 'https://example.com/hero-banner.jpg',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('directory-hero-media', false)
            ->assertSee('object-cover', false)
            ->assertSee('https://example.com/hero-banner.jpg', false)
            ->assertDontSee('landing-hero-clip', false);
    }

    public function test_billing_view_data_uses_rekening_and_qr_path(): void
    {
        PlatformSetting::current()->update([
            'bank_name' => 'Mandiri',
            'bank_holder' => 'PT Resto Platform',
            'bank_account' => '888899990000',
            'qr_image_path' => 'platform/qr/founder-qr.png',
        ]);

        $data = PlatformSetting::billingViewData();

        $this->assertSame('Mandiri', $data['bankName']);
        $this->assertSame('PT Resto Platform', $data['bankHolder']);
        $this->assertSame('888899990000', $data['bankAccount']);
        $this->assertStringContainsString('platform/qr/founder-qr.png', $data['qrUrl']);
        $this->assertNotSame(PlatformSetting::PLACEHOLDER_QR_URL, $data['qrUrl']);
        $this->assertSame((string) config('subscription.contact_email'), $data['contactEmail']);

        PlatformSetting::current()->update(['footer_email' => 'billing@resto.test']);

        $this->assertSame('billing@resto.test', PlatformSetting::billingViewData()['contactEmail']);
    }

    public function test_billing_falls_back_to_config_and_placeholder_qr(): void
    {
        $data = PlatformSetting::billingViewData();

        $this->assertSame((string) config('subscription.bank_name'), $data['bankName']);
        $this->assertSame((string) config('subscription.bank_account'), $data['bankAccount']);
        $this->assertSame(PlatformSetting::PLACEHOLDER_QR_URL, $data['qrUrl']);
    }

    public function test_subscription_status_card_shows_saved_billing_details(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        PlatformSetting::current()->update([
            'bank_name' => 'Mandiri',
            'bank_holder' => 'PT Resto Platform',
            'bank_account' => '888899990000',
            'qr_image_path' => 'platform/qr/founder-qr.png',
            'footer_email' => 'billing@resto.test',
        ]);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(SubscriptionStatusPage::class)
            ->assertOk()
            ->assertSee('Mandiri')
            ->assertSee('PT Resto Platform')
            ->assertSee('888899990000')
            ->assertSee('billing@resto.test')
            ->assertSee('platform/qr/founder-qr.png', false)
            ->assertDontSee('0000000000');
    }

    public function test_directory_facility_filters_follow_active_rows(): void
    {
        Facility::query()->where('key', 'wifi')->update(['is_active' => false]);
        Facility::query()->create([
            'key' => 'smoking_area',
            'name' => 'Area Merokok',
            'sort_order' => 90,
            'is_active' => true,
        ]);

        $options = Livewire::test(RestaurantDirectory::class)
            ->viewData('facilityOptions');

        $this->assertArrayNotHasKey('wifi', $options);
        $this->assertArrayHasKey('parking', $options);
        $this->assertSame('Area Merokok', $options['smoking_area']);
    }

    public function test_trial_days_defaults_to_config_and_can_be_updated(): void
    {
        $this->assertSame((int) config('subscription.trial_days', 30), PlatformSetting::trialDays());

        PlatformSetting::current()->update(['trial_days' => 14]);

        $this->assertSame(14, PlatformSetting::trialDays());
    }
}
