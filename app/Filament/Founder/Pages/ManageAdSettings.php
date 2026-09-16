<?php

namespace App\Filament\Founder\Pages;

use App\Models\AdSetting;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class ManageAdSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Monetisasi & Iklan';

    protected static ?string $title = 'Monetisasi & Iklan (AdSense / Adsterra)';

    protected static ?string $slug = 'monetisasi-iklan';

    protected static ?int $navigationSort = 7;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('guide')
                ->label('Panduan Integrasi Ads')
                ->icon(Heroicon::OutlinedBookOpen)
                ->color('info')
                ->outlined()
                ->modalWidth('4xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup Panduan')
                ->modalHeading('Panduan Lengkap Integrasi & Monetisasi Iklan')
                ->modalContent(fn (): View => view('filament.founder.pages.info-ad-integration')),

            Action::make('viewAdsTxt')
                ->label('Buka ads.txt')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->outlined()
                ->url(fn (): string => route('ads.txt'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $setting = AdSetting::current();

        $this->form->fill([
            'is_enabled' => (bool) $setting->is_enabled,
            'ads_txt_content' => $setting->ads_txt_content,
            'adsense_enabled' => (bool) $setting->adsense_enabled,
            'adsense_client_id' => $setting->adsense_client_id,
            'adsense_auto_ads' => (bool) $setting->adsense_auto_ads,
            'adsterra_enabled' => (bool) $setting->adsterra_enabled,
            'adsterra_social_bar_enabled' => (bool) $setting->adsterra_social_bar_enabled,
            'adsterra_social_bar_code' => $setting->adsterra_social_bar_code,
            'adsterra_native_enabled' => (bool) $setting->adsterra_native_enabled,
            'adsterra_native_code' => $setting->adsterra_native_code,
            'slot_blog_article_top' => $setting->slot_blog_article_top ?? ['provider' => null, 'code' => null, 'is_active' => false],
            'slot_blog_article_middle' => $setting->slot_blog_article_middle ?? ['provider' => null, 'code' => null, 'is_active' => false],
            'slot_blog_article_bottom' => $setting->slot_blog_article_bottom ?? ['provider' => null, 'code' => null, 'is_active' => false],
            'slot_blog_sidebar' => $setting->slot_blog_sidebar ?? ['provider' => null, 'code' => null, 'is_active' => false],
            'slot_blog_feed' => $setting->slot_blog_feed ?? ['provider' => null, 'code' => null, 'is_active' => false],
            'slot_directory_native' => $setting->slot_directory_native ?? ['provider' => null, 'code' => null, 'is_active' => false],
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('adTabs')
                    ->tabs([
                        $this->masterAndNetworksTab(),
                        $this->slotPlacementsTab(),
                        $this->adsTxtTab(),
                        $this->protectedZonesTab(),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan Pengaturan Iklan')
                                ->icon(Heroicon::OutlinedCheckCircle)
                                ->submit('save')
                                ->color('primary'),
                        ]),
                    ]),
            ]);
    }

    protected function masterAndNetworksTab(): Tab
    {
        return Tab::make('Jaringan Iklan')
            ->icon(Heroicon::OutlinedSignal)
            ->schema([
                Section::make('Master Switch')
                    ->description('Aktifkan atau nonaktifkan seluruh iklan di platform dalam satu klik.')
                    ->icon(Heroicon::OutlinedBolt)
                    ->schema([
                        Toggle::make('is_enabled')
                            ->label('Aktifkan Monetisasi Iklan')
                            ->helperText('Jika dimatikan, tidak ada iklan yang akan tampil di halaman publik manapun.')
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),

                Section::make('Google AdSense')
                    ->description('Konfigurasikan akun Google AdSense untuk iklan display berkualitas tinggi.')
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->collapsible()
                    ->schema([
                        Toggle::make('adsense_enabled')
                            ->label('Aktifkan Google AdSense')
                            ->onColor('success'),
                        TextInput::make('adsense_client_id')
                            ->label('Client ID (Publisher ID)')
                            ->placeholder('ca-pub-1234567890123456')
                            ->helperText('Salin dari akun Google AdSense Anda di menu Akun > Info Akun.')
                            ->maxLength(64),
                        Toggle::make('adsense_auto_ads')
                            ->label('Aktifkan Auto Ads')
                            ->helperText('Google akan otomatis memilih posisi iklan terbaik. Jika diaktifkan, slot manual tetap berjalan berdampingan.'),
                    ]),

                Section::make('Adsterra')
                    ->description('Konfigurasikan Adsterra untuk format iklan alternatif (Native Banner, Social Bar).')
                    ->icon(Heroicon::OutlinedRocketLaunch)
                    ->collapsible()
                    ->schema([
                        Toggle::make('adsterra_enabled')
                            ->label('Aktifkan Adsterra')
                            ->onColor('success'),
                        Toggle::make('adsterra_social_bar_enabled')
                            ->label('Aktifkan Social Bar (In-Page Push)')
                            ->helperText('Notifikasi interaktif kecil di sudut layar pembaca.'),
                        Textarea::make('adsterra_social_bar_code')
                            ->label('Kode Script Social Bar')
                            ->placeholder('<script ...></script>')
                            ->rows(4),
                        Toggle::make('adsterra_native_enabled')
                            ->label('Aktifkan Native Banners')
                            ->helperText('Format iklan bawaan yang menyatu dengan konten editorial.'),
                        Textarea::make('adsterra_native_code')
                            ->label('Kode Script Native Banner')
                            ->placeholder('<script ...></script>')
                            ->rows(4),
                    ]),
            ]);
    }

    protected function slotPlacementsTab(): Tab
    {
        return Tab::make('Penempatan Slot')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                $this->slotSection('slot_blog_article_top', 'Blog: Atas Artikel', 'Ditampilkan di bawah judul dan featured image artikel blog.'),
                $this->slotSection('slot_blog_article_middle', 'Blog: Tengah Artikel', 'Disisipkan otomatis setelah paragraf ke-3 artikel blog.'),
                $this->slotSection('slot_blog_article_bottom', 'Blog: Bawah Artikel', 'Ditampilkan sebelum komentar dan navigasi prev/next.'),
                $this->slotSection('slot_blog_sidebar', 'Blog: Sidebar Desktop', 'Ditampilkan di sidebar kanan artikel (hanya desktop/layar lebar).'),
                $this->slotSection('slot_blog_feed', 'Blog: In-Feed (Arsip/Kategori/Tag)', 'Disisipkan di antara kartu artikel di halaman arsip blog.'),
                $this->slotSection('slot_directory_native', 'Direktori: Native Listing', 'Ditampilkan sebagai kartu bersponsor di grid direktori restoran.'),
            ]);
    }

    protected function slotSection(string $slotKey, string $title, string $description): Section
    {
        return Section::make($title)
            ->description($description)
            ->collapsible()
            ->collapsed()
            ->schema([
                Toggle::make("{$slotKey}.is_active")
                    ->label('Aktifkan Slot Ini')
                    ->onColor('success'),
                TextInput::make("{$slotKey}.provider")
                    ->label('Provider')
                    ->placeholder('adsense / adsterra / custom')
                    ->helperText('Tuliskan "adsense", "adsterra", atau "custom" sesuai sumber kode iklan.'),
                Textarea::make("{$slotKey}.code")
                    ->label('Kode Unit Iklan (HTML/JS)')
                    ->placeholder('<ins class="adsbygoogle" ... ></ins><script>(adsbygoogle = ...).push({});</script>')
                    ->rows(5),
            ]);
    }

    protected function adsTxtTab(): Tab
    {
        return Tab::make('ads.txt')
            ->icon(Heroicon::OutlinedDocumentText)
            ->schema([
                Section::make('File ads.txt')
                    ->description(new HtmlString('File <code>ads.txt</code> adalah standar IAB yang wajib dimiliki setiap publisher untuk mengotorisasi jaringan iklan yang berhak menjual inventaris iklan Anda. File ini diakses publik di <code>'
                        .e(url('/ads.txt')).'</code>.'))
                    ->schema([
                        Textarea::make('ads_txt_content')
                            ->label('Isi ads.txt')
                            ->placeholder("google.com, pub-XXXXXXXXXX, DIRECT, f08c47fec0942fa0\n# Adsterra records here")
                            ->helperText('Satu baris per entri otorisasi. Salin dari dashboard AdSense & Adsterra Anda.')
                            ->rows(10),
                    ]),
            ]);
    }

    protected function protectedZonesTab(): Tab
    {
        return Tab::make('Zona Terlindungi')
            ->icon(Heroicon::OutlinedShieldCheck)
            ->schema([
                Section::make('Halaman Tanpa Iklan (Zero-Ads Zone)')
                    ->description(new HtmlString('Halaman-halaman berikut <strong>dilindungi secara absolut</strong> dari segala bentuk iklan, demi menjaga pengalaman pelanggan restoran dan kepatuhan kebijakan Google AdSense. Perlindungan ini bersifat permanen dan tidak dapat diubah.'))
                    ->schema([
                        Placeholder::make('info')
                            ->content(new HtmlString('
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Menu Digital & Pemesanan Pelanggan</strong> — <code>/menu/*</code>, <code>/order/*</code>, <code>/cart/*</code>, <code>/checkout/*</code></div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Point of Sale (POS) Kasir</strong> — <code>/pos/*</code>, <code>/cashier/*</code></div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Kitchen Display System (KDS)</strong> — <code>/kds/*</code></div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Panel Internal</strong> — <code>/founder/*</code>, <code>/admin/*</code>, <code>/blogger/*</code></div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Autentikasi & Registrasi</strong> — <code>/login</code>, <code>/daftar</code></div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400">✕</span>
                                        <div><strong>Invoice & Faktur Sewa</strong> — <code>/invoice/*</code>, <code>/receipts/*</code></div>
                                    </div>
                                </div>
                            ')),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = AdSetting::current();
        $setting->fill($data);
        $setting->save();

        Notification::make()
            ->title('Pengaturan iklan berhasil disimpan')
            ->body('Perubahan konfigurasi monetisasi telah diterapkan.')
            ->success()
            ->send();
    }
}
