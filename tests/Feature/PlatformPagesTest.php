<?php

namespace Tests\Feature;

use App\Filament\Founder\Pages\ManagePlatformPages;
use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlatformPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PlatformSettingSeeder::class);
    }

    public function test_about_page_renders_successfully_with_default_content(): void
    {
        $response = $this->get(route('page.about'));

        $response->assertOk();
        $response->assertSee('Tentang RestoTerdekat');
        $response->assertSee('Solusi Mudah Menikmati Kuliner Walk-In');
        $response->assertSee('Menemukan Restoran Terdekat');
        $response->assertSee('Dine-In Walk-In Tanpa Reservasi');
    }

    public function test_terms_page_renders_successfully_with_default_content(): void
    {
        $response = $this->get(route('page.terms'));

        $response->assertOk();
        $response->assertSee('Syarat &amp; Ketentuan Layanan', false);
        $response->assertSee('Ketentuan Penggunaan Platform');
        $response->assertSee('Layanan Direktori &amp; Walk-In', false);
        $response->assertSee('Pemesanan &amp; Pembayaran', false);
    }

    public function test_header_and_footer_contain_links_to_static_pages(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('page.about'));
        $response->assertSee('Tentang');
        $response->assertSee('Tentang kami');
        $response->assertSee(route('page.terms'));
        $response->assertSee('Syarat & ketentuan', false);
    }

    public function test_founder_can_manage_static_pages_in_founder_panel(): void
    {
        Storage::fake('public');
        Filament::setCurrentPanel('founder');

        setPermissionsTeamId(0);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $founder = User::factory()->create([
            'email' => 'founder@resto.test',
        ]);
        $founder->assignRole('super_admin');

        $this->actingAs($founder);

        Livewire::test(ManagePlatformPages::class)
            ->assertSuccessful()
            ->assertSchemaStateSet([
                'about_title' => 'Tentang RestoTerdekat',
                'terms_title' => 'Syarat & Ketentuan Layanan',
            ])
            ->fillForm([
                'about_title' => 'Tentang Kami yang Baru',
                'about_content' => '<p>Konten tentang yang telah diperbarui oleh founder.</p>',
                'terms_title' => 'Syarat & Ketentuan Terkini',
                'terms_content' => '<p>Ketentuan baru platform kuliner.</p>',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Verifikasi database terupdate
        $setting = PlatformSetting::current();
        $this->assertSame('Tentang Kami yang Baru', $setting->about_title);
        $this->assertStringContainsString('Konten tentang yang telah diperbarui', $setting->about_content);
        $this->assertSame('Syarat & Ketentuan Terkini', $setting->terms_title);

        // Verifikasi halaman publik langsung menampilkan perubahan
        $aboutResponse = $this->get(route('page.about'));
        $aboutResponse->assertOk();
        $aboutResponse->assertSee('Tentang Kami yang Baru');
        $aboutResponse->assertSee('Konten tentang yang telah diperbarui oleh founder.');

        $termsResponse = $this->get(route('page.terms'));
        $termsResponse->assertOk();
        $termsResponse->assertSee('Syarat &amp; Ketentuan Terkini', false);
        $termsResponse->assertSee('Ketentuan baru platform kuliner.');
    }

    public function test_updating_rich_text_purges_orphaned_image_attachments(): void
    {
        Storage::fake('public');

        // Buat file gambar dummy di disk public
        $disk = Storage::disk('public');
        $disk->put('platform/pages/photo1.jpg', 'dummy-image-1');
        $disk->put('platform/pages/photo2.jpg', 'dummy-image-2');

        $this->assertTrue($disk->exists('platform/pages/photo1.jpg'));
        $this->assertTrue($disk->exists('platform/pages/photo2.jpg'));

        $setting = PlatformSetting::current();
        $setting->update([
            'about_content' => '<p>Lihat gambar: <img src="/storage/platform/pages/photo1.jpg"> dan <img src="/storage/platform/pages/photo2.jpg"></p>',
        ]);

        // Simpan pembaruan di mana photo1 dihapus dari konten HTML
        $setting->update([
            'about_content' => '<p>Hanya tersisa gambar kedua: <img src="/storage/platform/pages/photo2.jpg"></p>',
        ]);

        // photo1 harus terhapus otomatis, photo2 tetap ada
        $this->assertFalse($disk->exists('platform/pages/photo1.jpg'));
        $this->assertTrue($disk->exists('platform/pages/photo2.jpg'));
    }
}
