<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthGlassTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_pages_use_glass_shell_without_custom_background(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('fi-simple-layout', false)
            ->assertSee('fi-logo', false)
            ->assertSee('Masuk', false)
            ->assertSee('Daftarkan restoran', false)
            ->assertSee(route('register.restaurant'), false)
            ->assertSee('--auth-primary:', false)
            ->assertDontSee('--auth-bg-image', false);

        $this->get('/founder/login')
            ->assertOk()
            ->assertSee('fi-simple-layout', false)
            ->assertSee('fi-logo', false)
            ->assertSee('Masuk', false)
            ->assertSee('--auth-primary:', false)
            ->assertDontSee(route('register.restaurant'), false)
            ->assertDontSee('--auth-bg-image', false);
    }

    public function test_login_and_register_use_platform_primary_color(): void
    {
        PlatformSetting::current()->update(['primary_color' => '#112233']);

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('--auth-primary: #112233', false);

        $this->get('/founder/login')
            ->assertOk()
            ->assertSee('--auth-primary: #112233', false);

        $this->get(route('register.restaurant'))
            ->assertOk()
            ->assertSee('--auth-primary: #112233', false);
    }

    public function test_auth_background_appears_on_login_and_register_pages(): void
    {
        PlatformSetting::current()->update([
            'auth_background_path' => 'https://example.com/auth-bg.jpg',
        ]);

        $expected = '--auth-bg-image: url("https://example.com/auth-bg.jpg")';

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee($expected, false);

        $this->get('/founder/login')
            ->assertOk()
            ->assertSee($expected, false);

        $this->get(route('register.restaurant'))
            ->assertOk()
            ->assertSee('auth-glass-page', false)
            ->assertSee('--auth-bg-image', false)
            ->assertSee('https://example.com/auth-bg.jpg', false)
            ->assertDontSee('directory-footer', false)
            ->assertDontSee('directory-header', false);
    }
}
