<?php

namespace App\Filament\Pages;

use App\Models\CmsProfile;
use App\Models\Facility;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\CmsMedia;
use App\Support\SubscriptionAccess;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Crypt;

class ManageCmsProfile extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Profil restoran';

    protected static ?string $title = 'Profil restoran';

    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    /**
     * @var list<string>
     */
    private const RESTAURANT_FIELDS = [
        'name',
        'slug',
        'legal_name',
        'logo_path',
        'timezone',
        'currency',
    ];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('cms.manage'))
            && SubscriptionAccess::allows('cms');
    }

    protected function getHeaderActions(): array
    {
        $tenant = Filament::getTenant();

        return [
            Action::make('viewLanding')
                ->label('Lihat halaman publik')
                ->url(fn (): string => route('landing.show', $tenant))
                ->openUrlInNewTab()
                ->visible(filled($tenant?->slug)),
        ];
    }

    public function mount(): void
    {
        $restaurant = $this->restaurant();

        abort_unless($restaurant instanceof Restaurant, 404);

        $profile = CmsProfile::query()->firstOrCreate(
            ['restaurant_id' => $restaurant->getKey()],
        );

        $rawFacilities = $restaurant->facilities ?? [];
        $selectedFacilities = is_array($rawFacilities) && array_is_list($rawFacilities)
            ? array_values(array_filter($rawFacilities, 'is_string'))
            : collect($rawFacilities)
                ->filter()
                ->keys()
                ->values()
                ->all();

        $this->form->fill([
            ...$restaurant->only(self::RESTAURANT_FIELDS),
            'legal_name' => filled($restaurant->legal_name) ? $restaurant->legal_name : $restaurant->name,
            'price_level' => $restaurant->price_level,
            'category_ids' => $restaurant->categories()->pluck('restaurant_categories.id')->all(),
            'facilities' => $selectedFacilities,
            ...$profile->only([
                'headline',
                'about_html',
                'hero_image_path',
                'how_to_image_path',
                'about_image_path',
                'map_embed_url',
                'cta_label',
                'cta_url',
                'primary_color',
                'accent_color',
            ]),
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
                Grid::make(3)
                    ->schema([
                        Section::make('Identitas')
                            ->description('Nama, URL, dan data legal restoran.')
                            ->icon(Heroicon::OutlinedBuildingStorefront)
                            ->columnSpan(2)
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama restoran')
                                    ->required()
                                    ->maxLength(120)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                        if (blank($get('legal_name'))) {
                                            $set('legal_name', $state);
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->prefix('/')
                                    ->helperText('Landing: /{slug} · Admin: /admin/{slug}')
                                    ->required()
                                    ->maxLength(80)
                                    ->unique(Restaurant::class, 'slug', ignorable: fn () => $this->restaurant()),
                                TextInput::make('legal_name')
                                    ->label('Nama legal')
                                    ->placeholder(fn (Get $get) => $get('name'))
                                    ->maxLength(191)
                                    ->columnSpanFull(),
                                Select::make('timezone')
                                    ->label('Zona waktu')
                                    ->options([
                                        'Asia/Jakarta' => 'WIB — Jakarta',
                                        'Asia/Makassar' => 'WITA — Makassar',
                                        'Asia/Jayapura' => 'WIT — Jayapura',
                                    ])
                                    ->required()
                                    ->native(false),
                                TextInput::make('currency')
                                    ->label('Mata uang')
                                    ->required()
                                    ->maxLength(3)
                                    ->default('IDR'),
                            ]),
                        Section::make('Logo')
                            ->icon(Heroicon::OutlinedPhoto)
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('logo_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->imageAspectRatio('1:1')
                                    ->panelAspectRatio('1:1')
                                    ->imagePreviewHeight('180')
                                    ->panelLayout('integrated')
                                    ->automaticallyCropImagesToAspectRatio()
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyResizeImagesToWidth('800')
                                    ->automaticallyResizeImagesToHeight('800')
                                    ->automaticallyUpscaleImagesWhenResizing(false)
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    ->imageEditorAspectRatios(['1:1'])
                                    ->directory('restaurants/logos')
                                    ->disk('public')
                                    ->maxSize(15360)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->helperText('Format: JPG, PNG, WEBP transparan. Rasio 1:1 persegi, otomatis dipotong & dikompres (maks. 15 MB).'),
                            ]),
                    ]),
                Section::make('Directory publik')
                    ->description('Data ini tampil di halaman daftar restoran.')
                    ->icon(Heroicon::OutlinedTag)
                    ->schema([
                        ToggleButtons::make('price_level')
                            ->label('Level harga')
                            ->options([
                                1 => '$',
                                2 => '$$',
                                3 => '$$$',
                                4 => '$$$$',
                            ])
                            ->inline()
                            ->grouped()
                            ->nullable()
                            ->helperText('Kisaran harga rata-rata. Klik opsi yang sama sekali lagi untuk mengosongkan.'),
                        ToggleButtons::make('category_ids')
                            ->label('Kategori restoran')
                            ->options(fn () => RestaurantCategory::query()
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->multiple()
                            ->inline(),
                        ToggleButtons::make('facilities')
                            ->label('Fasilitas')
                            ->options(fn () => Facility::activeOptions())
                            ->multiple()
                            ->inline()
                            ->icons(Facility::iconMap()),
                    ]),
                Section::make('WhatsApp (Fonnte)')
                    ->description('Struk dikirim sebagai pesan teks berisi ringkasan dan link unduh PDF (7 hari). Cocok untuk paket Fonnte gratis.')
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->compact()
                    ->schema([
                        TextInput::make('fonnte_api_key')
                            ->label('API key Fonnte')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ]),
                Grid::make(3)
                    ->schema([
                        Section::make('Konten landing')
                            ->description('Teks dan tautan yang tampil di halaman publik.')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('headline')
                                    ->label('Headline')
                                    ->maxLength(191),
                                RichEditor::make('about_html')
                                    ->label('Tentang restoran')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'undo',
                                        'redo',
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('cta_label')
                                            ->label('Teks tombol CTA')
                                            ->placeholder('Lihat lokasi')
                                            ->maxLength(80),
                                        TextInput::make('cta_url')
                                            ->label('URL tombol CTA')
                                            ->placeholder('https://www.google.com/maps/search/...')
                                            ->url()
                                            ->maxLength(500)
                                            ->suffixAction(
                                                Action::make('quickCtaPreset')
                                                    ->icon(Heroicon::OutlinedSparkles)
                                                    ->color('primary')
                                                    ->tooltip('Pilih tautan cepat (Google Maps, WhatsApp, atau Instagram)')
                                                    ->modalHeading('Pilih Tautan Cepat Tombol CTA')
                                                    ->modalDescription('Pilih tujuan tombol aksi utama (Call to Action) di halaman landing:')
                                                    ->form([
                                                        Radio::make('preset_type')
                                                            ->label('Tipe Tautan')
                                                            ->options([
                                                                'maps' => 'Buka Google Maps Outlet (Teks: "Lihat lokasi")',
                                                                'wa' => 'Hubungi via WhatsApp (Teks: "Hubungi via WhatsApp")',
                                                                'ig' => 'Kunjungi Instagram (Teks: "Kunjungi Instagram")',
                                                            ])
                                                            ->default('maps')
                                                            ->required(),
                                                    ])
                                                    ->modalSubmitActionLabel('Terapkan')
                                                    ->action(function (array $data, Set $set) {
                                                        $outlet = $this->restaurant()?->defaultOutlet;
                                                        if (! $outlet) {
                                                            Notification::make()
                                                                ->warning()
                                                                ->title('Outlet tidak ditemukan')
                                                                ->send();

                                                            return;
                                                        }

                                                        if ($data['preset_type'] === 'maps') {
                                                            $mapsUrl = CmsMedia::mapsSearchUrl($outlet->address, $outlet->latitude, $outlet->longitude);
                                                            if (blank($mapsUrl)) {
                                                                Notification::make()
                                                                    ->warning()
                                                                    ->title('Alamat atau GPS outlet belum diisi')
                                                                    ->body('Lengkapi alamat atau latitude/longitude pada menu Pengaturan > Outlet.')
                                                                    ->send();

                                                                return;
                                                            }
                                                            $set('cta_label', 'Lihat lokasi');
                                                            $set('cta_url', $mapsUrl);
                                                        } elseif ($data['preset_type'] === 'wa') {
                                                            $waUrl = CmsMedia::whatsappUrl($outlet->phone);
                                                            if (blank($waUrl)) {
                                                                Notification::make()
                                                                    ->warning()
                                                                    ->title('Nomor telepon outlet belum diisi')
                                                                    ->body('Lengkapi nomor telepon pada menu Pengaturan > Outlet.')
                                                                    ->send();

                                                                return;
                                                            }
                                                            $set('cta_label', 'Hubungi via WhatsApp');
                                                            $set('cta_url', $waUrl);
                                                        } elseif ($data['preset_type'] === 'ig') {
                                                            $igUrl = CmsMedia::instagramUrl($outlet->instagram);
                                                            if (blank($igUrl)) {
                                                                Notification::make()
                                                                    ->warning()
                                                                    ->title('Instagram outlet belum diisi')
                                                                    ->body('Lengkapi akun Instagram pada menu Pengaturan > Outlet.')
                                                                    ->send();

                                                                return;
                                                            }
                                                            $set('cta_label', 'Kunjungi Instagram');
                                                            $set('cta_url', $igUrl);
                                                        }

                                                        Notification::make()
                                                            ->success()
                                                            ->title('Tautan CTA Berhasil Diterapkan')
                                                            ->send();
                                                    })
                                            )
                                            ->helperText('Klik ikon bintang di ujung kolom untuk memilih cepat link Google Maps, WhatsApp, atau Instagram.'),
                                    ]),
                                TextInput::make('map_embed_url')
                                    ->label('URL embed peta')
                                    ->placeholder('https://maps.google.com/maps?q=...&output=embed')
                                    ->url()
                                    ->maxLength(500)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, Set $set) {
                                        if (blank($state)) {
                                            return;
                                        }

                                        $cleaned = CmsMedia::extractMapEmbedUrl($state);
                                        if ($cleaned !== $state) {
                                            $set('map_embed_url', $cleaned);
                                            Notification::make()
                                                ->info()
                                                ->title('Kode iframe terdeteksi')
                                                ->body('URL embed berhasil diekstrak otomatis dari kode iframe HTML.')
                                                ->send();
                                        }
                                    })
                                    ->suffixAction(
                                        Action::make('generateFromOutlet')
                                            ->icon(Heroicon::OutlinedMapPin)
                                            ->color('primary')
                                            ->tooltip('Isi otomatis embed peta dari lokasi/GPS outlet default')
                                            ->action(function (Set $set) {
                                                $outlet = $this->restaurant()?->defaultOutlet;
                                                if (! $outlet) {
                                                    Notification::make()
                                                        ->warning()
                                                        ->title('Outlet tidak ditemukan')
                                                        ->send();

                                                    return;
                                                }

                                                $embedUrl = CmsMedia::mapsEmbedUrl(null, $outlet->latitude, $outlet->longitude);

                                                if (blank($embedUrl) && filled($outlet->address)) {
                                                    $embedUrl = 'https://maps.google.com/maps?q='.urlencode($outlet->address).'&z=16&output=embed';
                                                }

                                                if (blank($embedUrl)) {
                                                    Notification::make()
                                                        ->warning()
                                                        ->title('Lokasi outlet belum lengkap')
                                                        ->body('Lengkapi latitude & longitude atau alamat pada menu Pengaturan > Outlet.')
                                                        ->send();

                                                    return;
                                                }

                                                $set('map_embed_url', $embedUrl);

                                                Notification::make()
                                                    ->success()
                                                    ->title('URL Embed Dibuat')
                                                    ->body('URL peta berhasil digenerate dari lokasi outlet.')
                                                    ->send();
                                            })
                                    )
                                    ->hintAction(
                                        Action::make('mapGuide')
                                            ->label('Panduan Salin Peta')
                                            ->icon(Heroicon::OutlinedQuestionMarkCircle)
                                            ->color('gray')
                                            ->modalHeading('Cara Mengambil Embed Peta dari Google Maps')
                                            ->modalContent(view('filament.forms.components.cms-map-guide-modal'))
                                            ->modalSubmitAction(false)
                                            ->modalCancelActionLabel('Tutup')
                                    )
                                    ->helperText('Bisa paste link Google Maps atau kode <iframe> langsung. Kosongkan untuk menggunakan peta otomatis dari titik GPS outlet.'),
                                Placeholder::make('map_embed_preview')
                                    ->hiddenLabel()
                                    ->view('filament.forms.components.cms-map-preview'),
                            ]),
                        Group::make([
                            Section::make('Warna brand')
                                ->description('Landing publik, halaman tamu, dan panel admin restoran.')
                                ->icon(Heroicon::OutlinedSwatch)
                                ->schema([
                                    ColorPicker::make('primary_color')
                                        ->label('Primary')
                                        ->hex(),
                                    ColorPicker::make('accent_color')
                                        ->label('Accent')
                                        ->hex(),
                                ]),
                            Section::make('Gambar hero')
                                ->description('Banner utama di bagian atas landing.')
                                ->icon(Heroicon::OutlinedPhoto)
                                ->schema([
                                    FileUpload::make('hero_image_path')
                                        ->hiddenLabel()
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
                                        ->imageEditorMode(2)
                                        ->imageEditorAspectRatios(['16:9'])
                                        ->directory('cms/hero')
                                        ->disk('public')
                                        ->maxSize(15360)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Format: JPG, PNG, WEBP. Rasio 16:9, otomatis dipotong & dikompres maks. 1 MB (file awal hingga 15 MB).'),
                                ]),
                            Section::make('Gambar cara pesan')
                                ->description('Foto samping timeline Walk-in, scan, bayar.')
                                ->icon(Heroicon::OutlinedPhoto)
                                ->schema([
                                    FileUpload::make('how_to_image_path')
                                        ->hiddenLabel()
                                        ->image()
                                        ->imageAspectRatio('1:1')
                                        ->panelAspectRatio('1:1')
                                        ->imagePreviewHeight('160')
                                        ->panelLayout('integrated')
                                        ->automaticallyCropImagesToAspectRatio()
                                        ->automaticallyResizeImagesMode('cover')
                                        ->automaticallyResizeImagesToWidth('800')
                                        ->automaticallyResizeImagesToHeight('800')
                                        ->automaticallyUpscaleImagesWhenResizing(false)
                                        ->imageEditor()
                                        ->imageEditorMode(2)
                                        ->imageEditorAspectRatios(['1:1'])
                                        ->directory('cms/how-to')
                                        ->disk('public')
                                        ->maxSize(15360)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Format: JPG, PNG, WEBP. Rasio 1:1, otomatis dipotong & dikompres (maks. 15 MB).'),
                                ]),
                            Section::make('Gambar tentang')
                                ->description('Foto samping bagian Cerita di balik dapur.')
                                ->icon(Heroicon::OutlinedPhoto)
                                ->schema([
                                    FileUpload::make('about_image_path')
                                        ->hiddenLabel()
                                        ->image()
                                        ->imageAspectRatio('4:3')
                                        ->panelAspectRatio('4:3')
                                        ->imagePreviewHeight('160')
                                        ->panelLayout('integrated')
                                        ->automaticallyCropImagesToAspectRatio()
                                        ->automaticallyResizeImagesMode('cover')
                                        ->automaticallyResizeImagesToWidth('1200')
                                        ->automaticallyResizeImagesToHeight('900')
                                        ->automaticallyUpscaleImagesWhenResizing(false)
                                        ->imageEditor()
                                        ->imageEditorMode(2)
                                        ->imageEditorAspectRatios(['4:3'])
                                        ->directory('cms/about')
                                        ->disk('public')
                                        ->maxSize(15360)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->helperText('Format: JPG, PNG, WEBP. Rasio 4:3, otomatis dipotong & dikompres (maks. 15 MB).'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public function save(): void
    {
        abort_if(SubscriptionAccess::isReadOnly(), 403);

        $restaurant = $this->restaurant();

        abort_unless($restaurant instanceof Restaurant, 404);

        $data = $this->form->getState();

        $restaurantPayload = collect($data)->only(self::RESTAURANT_FIELDS)->all();
        if (blank($restaurantPayload['legal_name'] ?? null) && filled($restaurantPayload['name'] ?? null)) {
            $restaurantPayload['legal_name'] = $restaurantPayload['name'];
        }
        $restaurantPayload['price_level'] = filled($data['price_level'] ?? null)
            ? (int) $data['price_level']
            : null;
        $restaurantPayload['facilities'] = collect(Facility::knownKeys())
            ->mapWithKeys(fn (string $key) => [$key => in_array($key, $data['facilities'] ?? [], true)])
            ->all();

        if (filled($data['fonnte_api_key'] ?? null)) {
            $restaurantPayload['fonnte_api_key_encrypted'] = Crypt::encryptString($data['fonnte_api_key']);
        }

        $oldRestaurant = $restaurant->only([...self::RESTAURANT_FIELDS, 'fonnte_api_key_encrypted']);
        $oldSlug = $restaurant->slug;

        $restaurant->update($restaurantPayload);
        $restaurant->categories()->sync(
            collect($data['category_ids'] ?? [])
                ->filter(fn ($id) => filled($id))
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all(),
        );

        $profile = CmsProfile::query()->firstOrCreate(
            ['restaurant_id' => $restaurant->getKey()],
        );

        $profilePayload = collect($data)->except([
            ...self::RESTAURANT_FIELDS,
            'fonnte_api_key',
            'price_level',
            'category_ids',
            'facilities',
        ])->all();

        $oldProfile = $profile->only(['headline', 'cta_label', 'cta_url', 'primary_color', 'accent_color']);
        $profile->update($profilePayload);

        ActivityLogger::log('cms.update_profile', [
            'old' => [
                'restaurant' => $oldRestaurant,
                'profile' => $oldProfile,
            ],
            'new' => [
                'restaurant' => $restaurant->fresh()->only([...self::RESTAURANT_FIELDS, 'fonnte_api_key_encrypted']),
                'profile' => $profile->only(['headline', 'cta_label', 'cta_url', 'primary_color', 'accent_color']),
            ],
        ]);

        Notification::make()
            ->title('Profil restoran disimpan')
            ->success()
            ->send();

        if ($restaurant->slug !== $oldSlug) {
            $this->redirect(static::getUrl(tenant: $restaurant));
        }
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
                                ->action('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    private function restaurant(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
    }
}
