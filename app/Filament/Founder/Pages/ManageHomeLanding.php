<?php

namespace App\Filament\Founder\Pages;

use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class ManageHomeLanding extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Tampilan home';

    protected static ?string $title = 'Tampilan home';

    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->isPlatformOperator();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewHome')
                ->label('Lihat halaman utama')
                ->url(fn (): string => route('home'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $this->form->fill(PlatformSetting::current()->only([
            'primary_color',
            'site_name',
            'logo_path',
            'favicon_path',
            'auth_background_path',
            'hero_eyebrow',
            'hero_title',
            'hero_highlight',
            'hero_subtitle',
            'hero_image_path',
            'cta_register_label',
            'search_placeholder',
            'search_button_label',
            'location_cta_label',
            'footer_about',
            'footer_email',
            'footer_phone',
            'footer_address',
            'footer_instagram',
            'footer_copyright',
            'footer_privacy_url',
            'footer_terms_url',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'og_image_path',
            'canonical_url',
        ]));
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('HomeLandingSettings')
                    ->tabs([
                        Tab::make('Identitas & Warna')
                            ->icon(Heroicon::OutlinedSwatch)
                            ->schema([
                                Section::make('Identitas Situs & Brand')
                                    ->description('Nama platform dan warna utama yang diterapkan di direktori publik serta panel.')
                                    ->icon(Heroicon::OutlinedBuildingStorefront)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama situs')
                                            ->placeholder('RestoTerdekat')
                                            ->required()
                                            ->maxLength(80),
                                        ColorPicker::make('primary_color')
                                            ->label('Warna brand (Primary)')
                                            ->hex()
                                            ->required(),
                                    ]),
                                Section::make('Logo & Favicon Browser')
                                    ->description('Gambar identitas yang tampil pada header situs dan tab browser.')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('logo_path')
                                            ->label('Logo header')
                                            ->helperText('Kosongkan untuk memakai ikon rumah dan nama situs. Format: PNG/WEBP transparan (maks. 15 MB).')
                                            ->image()
                                            ->imageAspectRatio('1:1')
                                            ->panelAspectRatio('1:1')
                                            ->imagePreviewHeight('160')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('contain')
                                            ->automaticallyResizeImagesToWidth('800')
                                            ->automaticallyResizeImagesToHeight('800')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1:1'])
                                            ->directory('platform/logo')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                                        FileUpload::make('favicon_path')
                                            ->label('Favicon browser')
                                            ->helperText('Wajib rasio 1:1 (persegi, min. 48x48 px, saran 192x192 atau 512x512). Otomatis dipotong & dikompres (maks. 15 MB).')
                                            ->image()
                                            ->imageAspectRatio('1:1')
                                            ->panelAspectRatio('1:1')
                                            ->imagePreviewHeight('120')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('512')
                                            ->automaticallyResizeImagesToHeight('512')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1:1'])
                                            ->directory('platform/favicon')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon']),
                                    ]),
                                Section::make('Latar Belakang Login')
                                    ->description('Latar belakang halaman login Admin, login Founder, dan pendaftaran restoran.')
                                    ->icon(Heroicon::OutlinedLockClosed)
                                    ->schema([
                                        FileUpload::make('auth_background_path')
                                            ->label('Gambar latar login (16:9)')
                                            ->helperText('Foto resolusi tinggi bernuansa restoran. Rasio 16:9, otomatis dikompres (maks. 15 MB).')
                                            ->image()
                                            ->imageAspectRatio('16:9')
                                            ->panelAspectRatio('16:9')
                                            ->imagePreviewHeight('180')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('1600')
                                            ->automaticallyResizeImagesToHeight('900')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['16:9'])
                                            ->directory('platform/auth')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                                    ]),
                            ]),

                        Tab::make('Beranda & Hero')
                            ->icon(Heroicon::OutlinedHome)
                            ->schema([
                                Section::make('Banner Hero Utama')
                                    ->description('Konten teks dan gambar latar utama di bagian paling atas halaman beranda.')
                                    ->icon(Heroicon::OutlinedPhoto)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('hero_eyebrow')
                                            ->label('Label atas (Eyebrow)')
                                            ->placeholder('Walk-in')
                                            ->maxLength(40),
                                        TextInput::make('hero_highlight')
                                            ->label('Kata yang diwarnai')
                                            ->placeholder('terdekat')
                                            ->helperText('Harus muncul di dalam judul hero agar otomatis diberi warna aksen.')
                                            ->maxLength(40),
                                        TextInput::make('hero_title')
                                            ->label('Judul hero utama')
                                            ->placeholder('Temukan restoran terdekat & terbaik')
                                            ->required()
                                            ->maxLength(160)
                                            ->columnSpanFull(),
                                        Textarea::make('hero_subtitle')
                                            ->label('Subjudul hero')
                                            ->placeholder('Jelajahi restoran di sekitar Anda...')
                                            ->rows(3)
                                            ->maxLength(400)
                                            ->columnSpanFull(),
                                        FileUpload::make('hero_image_path')
                                            ->label('Foto latar hero (16:9)')
                                            ->helperText('Foto interior atau makanan kontras. Rasio 16:9, otomatis dipotong & dikompres maks. 1 MB (file awal hingga 15 MB).')
                                            ->image()
                                            ->imageAspectRatio('16:9')
                                            ->panelAspectRatio('16:9')
                                            ->imagePreviewHeight('180')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('1600')
                                            ->automaticallyResizeImagesToHeight('900')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['16:9'])
                                            ->directory('platform/hero')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Pencarian & CTA Direktori')
                                    ->description('Teks kolom pencarian dan tombol ajakan bertindak (CTA).')
                                    ->icon(Heroicon::OutlinedMagnifyingGlass)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('search_placeholder')
                                            ->label('Placeholder kolom pencarian')
                                            ->required()
                                            ->maxLength(120)
                                            ->columnSpanFull(),
                                        TextInput::make('search_button_label')
                                            ->label('Label tombol cari')
                                            ->required()
                                            ->maxLength(40),
                                        TextInput::make('location_cta_label')
                                            ->label('Label tombol izin lokasi')
                                            ->required()
                                            ->maxLength(40),
                                        TextInput::make('cta_register_label')
                                            ->label('Label CTA pendaftaran resto')
                                            ->helperText('Tampil di navigasi header dan footer untuk pemilik restoran.')
                                            ->required()
                                            ->maxLength(80)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('SEO & Medsos')
                            ->icon(Heroicon::OutlinedGlobeAlt)
                            ->schema([
                                Section::make('Search Engine Optimization (Google SERP)')
                                    ->description('Pengaturan meta tag agar halaman direktori optimal di indeks pencarian Google.')
                                    ->icon(Heroicon::OutlinedMagnifyingGlass)
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->helperText('Judul di Google & tab browser. Mendukung placeholder {site}. Rekomendasi: 50–60 karakter.')
                                            ->maxLength(120),
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->helperText('Cuplikan deskripsi di hasil pencarian Google. Mendukung placeholder {site}. Rekomendasi: 150–160 karakter.')
                                            ->rows(3)
                                            ->maxLength(300),
                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Daftar kata kunci pencarian direktori, pisahkan dengan tanda koma.')
                                            ->maxLength(255),
                                        TextInput::make('canonical_url')
                                            ->label('Canonical URL Kustom')
                                            ->helperText('Kosongkan untuk otomatis menggunakan URL beranda saat ini.')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                                Section::make('Social Share Preview (Open Graph & Twitter)')
                                    ->description('Gambar preview saat tautan situs dibagikan ke WhatsApp, Telegram, Facebook, dan Twitter.')
                                    ->icon(Heroicon::OutlinedShare)
                                    ->schema([
                                        FileUpload::make('og_image_path')
                                            ->label('Banner share media sosial (1200x630px / 1.91:1)')
                                            ->helperText('Kosongkan untuk otomatis memakai foto banner hero atau logo platform. Rasio 1.91:1 (maks. 15 MB).')
                                            ->image()
                                            ->imageAspectRatio('1.91:1')
                                            ->panelAspectRatio('1.91:1')
                                            ->imagePreviewHeight('160')
                                            ->panelLayout('integrated')
                                            ->automaticallyCropImagesToAspectRatio()
                                            ->automaticallyResizeImagesMode('cover')
                                            ->automaticallyResizeImagesToWidth('1200')
                                            ->automaticallyResizeImagesToHeight('630')
                                            ->automaticallyUpscaleImagesWhenResizing(false)
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1.91:1'])
                                            ->directory('platform/seo')
                                            ->disk('public')
                                            ->maxSize(15360)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                                    ]),
                            ]),

                        Tab::make('Footer & Kontak')
                            ->icon(Heroicon::OutlinedNewspaper)
                            ->schema([
                                Section::make('Informasi Footer & Hak Cipta')
                                    ->description('Deskripsi singkat platform dan hak cipta di bagian bawah halaman.')
                                    ->icon(Heroicon::OutlinedInformationCircle)
                                    ->schema([
                                        Textarea::make('footer_about')
                                            ->label('Tentang platform di footer')
                                            ->helperText('Boleh memakai {site} agar nama situs otomatis terisi.')
                                            ->rows(3)
                                            ->maxLength(400),
                                        TextInput::make('footer_copyright')
                                            ->label('Teks copyright')
                                            ->helperText('Mendukung token {year} dan {site}.')
                                            ->maxLength(160),
                                    ]),
                                Section::make('Kontak & Media Sosial')
                                    ->description('Informasi narahubung yang ditampilkan di footer direktori.')
                                    ->icon(Heroicon::OutlinedPhone)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('footer_email')
                                            ->label('Email founder')
                                            ->email()
                                            ->maxLength(120),
                                        TextInput::make('footer_phone')
                                            ->label('Kontak / WhatsApp')
                                            ->helperText('Nomor ini otomatis ditautkan ke WhatsApp jika berisi angka.')
                                            ->tel()
                                            ->maxLength(40),
                                        TextInput::make('footer_address')
                                            ->label('Alamat kantor / operasional')
                                            ->maxLength(160)
                                            ->columnSpanFull(),
                                        TextInput::make('footer_instagram')
                                            ->label('Instagram resmi')
                                            ->helperText('Username (@restoterdekat) atau URL profil lengkap.')
                                            ->maxLength(160)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Tautan Kebijakan Eksternal (Opsional)')
                                    ->description('Kosongkan untuk otomatis menggunakan halaman statis bawaan platform.')
                                    ->icon(Heroicon::OutlinedDocumentText)
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('footer_privacy_url')
                                            ->label('URL kebijakan privasi kustom')
                                            ->url()
                                            ->maxLength(255),
                                        TextInput::make('footer_terms_url')
                                            ->label('URL syarat & ketentuan kustom')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $setting = PlatformSetting::current();
        $setting->update($this->form->getState());

        Notification::make()
            ->title('Tampilan home disimpan')
            ->success()
            ->send();
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
                                ->label('Simpan')
                                ->submit('save'),
                        ]),
                    ]),
            ]);
    }
}
