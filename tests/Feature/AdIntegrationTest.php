<?php

namespace Tests\Feature;

use App\Filament\Founder\Pages\ManageAdSettings;
use App\Models\AdSetting;
use App\Models\User;
use App\Services\AdPlacementService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Tests\TestCase;

class AdIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        AdSetting::forgetCache();
    }

    public function test_ads_txt_serves_content_dynamically(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'ads_txt_content' => "google.com, pub-9876543210123456, DIRECT, f08c47fec0942fa0\ncontact=admin@restoterdekat.com",
        ]);

        $response = $this->get('/ads.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('pub-9876543210123456');
        $response->assertSee('contact=admin@restoterdekat.com');
    }

    public function test_ads_are_blocked_on_customer_and_panel_routes(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'is_enabled' => true,
            'adsense_enabled' => true,
            'adsense_client_id' => 'ca-pub-1234567890123456',
        ]);

        $service = app(AdPlacementService::class);

        // Simulate request to /order/menu
        $this->app->instance('request', Request::create('/order/menu'));
        $this->assertFalse($service->isAdAllowedForCurrentRequest());
        $this->assertSame('', $service->getAdSenseHeadScript());

        // Simulate request to /pos/cashier
        $this->app->instance('request', Request::create('/pos/cashier'));
        $this->assertFalse($service->isAdAllowedForCurrentRequest());

        // Simulate request to /cart
        $this->app->instance('request', Request::create('/cart'));
        $this->assertFalse($service->isAdAllowedForCurrentRequest());

        // Simulate request to /founder/dashboard
        $this->app->instance('request', Request::create('/founder/dashboard'));
        $this->assertFalse($service->isAdAllowedForCurrentRequest());

        // Simulate request to /admin
        $this->app->instance('request', Request::create('/admin'));
        $this->assertFalse($service->isAdAllowedForCurrentRequest());

        // Simulate request to public blog post -> SHOULD BE ALLOWED
        $this->app->instance('request', Request::create('/blog/resep-nasi-goreng'));
        $this->assertTrue($service->isAdAllowedForCurrentRequest());
        $this->assertStringContainsString('ca-pub-1234567890123456', $service->getAdSenseHeadScript());
    }

    public function test_in_article_ad_injection_after_nth_paragraph(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'is_enabled' => true,
            'slot_blog_article_middle' => [
                'provider' => 'adsense',
                'code' => '<ins class="adsbygoogle" data-ad-slot="12345"></ins>',
                'is_active' => true,
            ],
        ]);

        $this->app->instance('request', Request::create('/blog/test-article'));

        $service = app(AdPlacementService::class);

        $htmlContent = '<p>Paragraph 1</p><p>Paragraph 2</p><p>Paragraph 3</p><p>Paragraph 4</p>';

        $injected = $service->injectInArticleAd($htmlContent, 3, 'blog_article_middle');

        $this->assertStringContainsString('<p>Paragraph 3</p>', $injected);
        $this->assertStringContainsString('data-ad-slot="12345"', $injected);
        $this->assertStringContainsString('<p>Paragraph 4</p>', $injected);

        // Check that ad is placed AFTER paragraph 3 and BEFORE paragraph 4
        $posP3 = strpos($injected, '</p>');
        $posP3 = strpos($injected, '</p>', $posP3 + 1);
        $posP3 = strpos($injected, '</p>', $posP3 + 1); // 3rd </p>
        $posAd = strpos($injected, 'data-ad-slot="12345"');
        $posP4 = strpos($injected, 'Paragraph 4');

        $this->assertTrue($posP3 < $posAd);
        $this->assertTrue($posAd < $posP4);
    }

    public function test_guest_cannot_access_founder_ad_settings(): void
    {
        $response = $this->get('/founder/monetisasi-iklan');
        $response->assertRedirect('/founder/login');
    }

    public function test_founder_can_access_and_save_ad_settings(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $this->actingAs($founder);

        Livewire::test(ManageAdSettings::class)
            ->assertSuccessful()
            ->set('data.is_enabled', true)
            ->set('data.adsense_enabled', true)
            ->set('data.adsense_client_id', 'ca-pub-9999888877776666')
            ->set('data.adsense_auto_ads', true)
            ->set('data.ads_txt_content', 'google.com, pub-9999888877776666, DIRECT, f08c47fec0942fa0')
            ->call('save')
            ->assertHasNoErrors()
            ->assertNotified('Pengaturan iklan berhasil disimpan');

        $setting = AdSetting::current();
        $this->assertTrue($setting->is_enabled);
        $this->assertTrue($setting->adsense_enabled);
        $this->assertSame('ca-pub-9999888877776666', $setting->adsense_client_id);
        $this->assertTrue($setting->adsense_auto_ads);
        $this->assertStringContainsString('pub-9999888877776666', $setting->ads_txt_content);
    }

    public function test_blade_components_render_correctly_based_on_settings(): void
    {
        // 1. When master disabled -> nothing renders
        AdSetting::current()->update([
            'is_enabled' => false,
            'adsense_enabled' => true,
            'adsense_client_id' => 'ca-pub-1111222233334444',
            'slot_blog_article_top' => [
                'provider' => 'adsense',
                'code' => '<ins class="adsbygoogle" data-slot="top"></ins>',
                'is_active' => true,
            ],
        ]);
        AdSetting::forgetCache();

        $this->app->instance('request', Request::create('/blog/kuliner-bandung'));

        $viewHead = $this->blade('<x-ads.head />');
        $this->assertStringNotContainsString('ca-pub-1111222233334444', $viewHead);

        $viewSlot = $this->blade('<x-ads.slot name="blog_article_top" />');
        $this->assertStringNotContainsString('data-slot="top"', $viewSlot);

        // 2. When master enabled -> renders properly
        AdSetting::current()->update(['is_enabled' => true]);
        AdSetting::forgetCache();

        $viewHeadActive = $this->blade('<x-ads.head />');
        $this->assertStringContainsString('ca-pub-1111222233334444', $viewHeadActive);

        $viewSlotActive = $this->blade('<x-ads.slot name="blog_article_top" />');
        $this->assertStringContainsString('data-slot="top"', $viewSlotActive);
        $this->assertStringContainsString('ad-slot--blog_article_top', $viewSlotActive);
        $this->assertStringContainsString('Iklan', $viewSlotActive);
    }

    public function test_founder_header_actions_and_guide_modal_render(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $this->actingAs($founder);

        Livewire::test(ManageAdSettings::class)
            ->assertActionExists('guide')
            ->assertActionExists('viewAdsTxt');

        // Test rendering the guide modal view directly
        $guideView = view('filament.founder.pages.info-ad-integration')->render();
        $this->assertStringContainsString('Buku Panduan Monetisasi', $guideView);
        $this->assertStringContainsString('Zero-Ads Protection', $guideView);
        $this->assertStringContainsString('slot_blog_article_middle', $guideView);
        $this->assertStringContainsString('Popunder', $guideView);
    }

    public function test_security_headers_allow_ads_in_production_environment(): void
    {
        $middleware = new \App\Http\Middleware\SecurityHeaders();
        $request = Request::create('/', 'GET');

        $this->app->detectEnvironment(fn () => 'production');

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp);
        $this->assertStringContainsString('https://pagead2.googlesyndication.com', $csp);
        $this->assertStringContainsString('https://*.googlesyndication.com', $csp);
        $this->assertStringContainsString('https://googleads.g.doubleclick.net', $csp);
        $this->assertStringContainsString('https://*.adsterra.com', $csp);
    }

    public function test_adsterra_native_code_fallback_in_slots(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'is_enabled' => true,
            'adsterra_enabled' => true,
            'adsterra_native_enabled' => true,
            'adsterra_native_code' => '<div id="adsterra-native-widget"></div>',
            'slot_directory_native' => [
                'provider' => 'adsterra',
                'code' => '',
                'is_active' => true,
            ],
        ]);
        AdSetting::forgetCache();

        $this->app->instance('request', Request::create('/'));

        $this->assertTrue(AdSetting::current()->isSlotActive('directory_native'));

        $service = app(AdPlacementService::class);
        $rendered = $service->renderSlot('directory_native');

        $this->assertStringContainsString('adsterra-native-widget', $rendered);
        $this->assertStringContainsString('ad-slot--directory_native', $rendered);
        $this->assertSame('<div id="adsterra-native-widget"></div>', $service->getAdsterraNativeScript());
    }

    public function test_directory_native_slot_renders_on_homepage(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'is_enabled' => true,
            'slot_directory_native' => [
                'provider' => 'custom',
                'code' => '<div id="test-directory-ad">Promo Resto</div>',
                'is_active' => true,
            ],
        ]);
        AdSetting::forgetCache();

        // Create at least one restaurant so the loop executes
        $user = User::factory()->create();
        $restaurant = \App\Models\Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Resto Uji Rasa',
            'slug' => 'resto-uji-rasa',
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('test-directory-ad');
        $response->assertSee('ad-slot--directory_native');

        // When master disabled
        $setting->update(['is_enabled' => false]);
        AdSetting::forgetCache();

        $responseDisabled = $this->get('/');
        $responseDisabled->assertOk();
        $responseDisabled->assertDontSee('test-directory-ad');
        $responseDisabled->assertDontSee('ad-slot--directory_native');
    }

    public function test_livewire_request_respects_referer_for_ad_permissions(): void
    {
        $setting = AdSetting::current();
        $setting->update([
            'is_enabled' => true,
        ]);
        AdSetting::forgetCache();

        $service = app(AdPlacementService::class);

        // 1. Livewire update on homepage -> allowed
        $requestHome = Request::create('/livewire/update', 'POST');
        $requestHome->headers->set('X-Livewire', 'true');
        $requestHome->headers->set('Referer', 'http://localhost/');
        $this->app->instance('request', $requestHome);
        $this->assertTrue($service->isAdAllowedForCurrentRequest());

        // 2. Livewire update on /pos/cashier -> blocked
        $requestPos = Request::create('/livewire/update', 'POST');
        $requestPos->headers->set('X-Livewire', 'true');
        $requestPos->headers->set('Referer', 'http://localhost/pos/cashier');
        $this->app->instance('request', $requestPos);
        $this->assertFalse($service->isAdAllowedForCurrentRequest());
    }
}
