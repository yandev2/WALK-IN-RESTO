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
                Section::make('Warna brand')
                    ->description('Dipakai di home publik dan halaman login panel Admin serta Founder.')
                    ->icon(Heroicon::OutlinedSwatch)
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Primary')
                            ->hex()
                            ->required(),
                    ]),
                Section::make('Identitas & CTA')
                    ->icon(Heroicon::OutlinedBuildingStorefront)
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nama situs')
                            ->required()
                            ->maxLength(80),
                        FileUpload::make('logo_path')
                            ->label('Logo header')
                            ->helperText('Kosongkan untuk memakai ikon rumah dan nama situs. Jika diisi, logo menggantikan keduanya di header home.')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('80')
                            ->panelLayout('compact')
                            ->directory('platform/logo')
                            ->disk('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                        TextInput::make('cta_register_label')
                            ->label('CTA daftar restoran')
                            ->required()
                            ->maxLength(80),
                        TextInput::make('search_placeholder')
                            ->label('Placeholder pencarian')
                            ->required()
                            ->maxLength(120)
                            ->columnSpanFull(),
                        TextInput::make('search_button_label')
                            ->label('Tombol cari')
                            ->required()
                            ->maxLength(40),
                        TextInput::make('location_cta_label')
                            ->label('Tombol izinkan lokasi')
                            ->required()
                            ->maxLength(40),
                    ]),
                Section::make('Hero')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        TextInput::make('hero_eyebrow')
                            ->label('Label atas')
                            ->maxLength(40),
                        TextInput::make('hero_title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(160),
                        TextInput::make('hero_highlight')
                            ->label('Kata yang diwarnai')
                            ->helperText('Harus muncul di dalam judul, misalnya “terdekat”.')
                            ->maxLength(40),
                        Textarea::make('hero_subtitle')
                            ->label('Subjudul')
                            ->rows(3)
                            ->maxLength(400),
                        FileUpload::make('hero_image_path')
                            ->label('Gambar banner')
                            ->helperText('Dipakai sebagai foto latar full-width di home. Rasio 16:9, foto interior atau makanan yang kontras.')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imagePreviewHeight('140')
                            ->panelLayout('compact')
                            ->directory('platform/hero')
                            ->disk('public')
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ]),
                Section::make('Footer')
                    ->description('Tampil di bagian bawah home dan halaman daftar restoran. Kosongkan field opsional untuk menyembunyikannya.')
                    ->icon(Heroicon::OutlinedNewspaper)
                    ->columns(2)
                    ->schema([
                        Textarea::make('footer_about')
                            ->label('Tentang')
                            ->helperText('Boleh memakai {site} agar nama situs ikut berubah.')
                            ->rows(3)
                            ->maxLength(400)
                            ->columnSpanFull(),
                        TextInput::make('footer_email')
                            ->label('Email founder')
                            ->email()
                            ->maxLength(120),
                        TextInput::make('footer_phone')
                            ->label('Kontak / WhatsApp')
                            ->helperText('Nomor ini ditautkan ke WhatsApp jika berisi angka.')
                            ->tel()
                            ->maxLength(40),
                        TextInput::make('footer_address')
                            ->label('Alamat')
                            ->maxLength(160)
                            ->columnSpanFull(),
                        TextInput::make('footer_instagram')
                            ->label('Instagram')
                            ->helperText('Username (contoh restoterdekat) atau URL lengkap.')
                            ->maxLength(160),
                        TextInput::make('footer_copyright')
                            ->label('Copyright')
                            ->helperText('Placeholder: {year} dan {site}.')
                            ->maxLength(160),
                        TextInput::make('footer_privacy_url')
                            ->label('URL kebijakan privasi')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('footer_terms_url')
                            ->label('URL syarat & ketentuan')
                            ->url()
                            ->maxLength(255),
                    ]),
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
