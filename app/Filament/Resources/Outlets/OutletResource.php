<?php

namespace App\Filament\Resources\Outlets;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Resources\Outlets\Pages\ManageOutlet;
use App\Models\Outlet;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class OutletResource extends Resource
{
    use ChecksBusinessPermission;

    protected static ?string $model = Outlet::class;

    protected static string $permission = 'settings.manage';

    protected static string $subscriptionFeature = 'settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Outlet';

    protected static ?string $pluralModelLabel = 'outlet';

    protected static ?int $navigationSort = 1;

    /**
     * @var array<int, string>
     */
    private const DAY_LABELS = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil outlet')
                    ->description('Identitas lokasi dan status operasional.')
                    ->icon(Heroicon::OutlinedBuildingStorefront)
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (): bool => false)
                            ->helperText('Kode internal, tidak bisa diubah.'),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(32),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Toggle::make('is_open')
                            ->label('Buka (open/closed)')
                            ->helperText('Off = selalu tutup. On = ikut jam operasional hari ini.')
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Nonaktifkan untuk menutup outlet sepenuhnya.')
                            ->inline(false),
                    ]),
                Section::make('Jam operasional')
                    ->description('Atur jam buka per hari. Centang libur untuk hari tanpa operasional.')
                    ->icon(Heroicon::OutlinedClock)
                    ->schema([
                        Repeater::make('operatingHours')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                Select::make('day_of_week')
                                    ->label('Hari')
                                    ->options(self::DAY_LABELS)
                                    ->required(),
                                TimePicker::make('opens_at')
                                    ->label('Buka')
                                    ->seconds(false)
                                    ->native(true),
                                TimePicker::make('closes_at')
                                    ->label('Tutup')
                                    ->seconds(false)
                                    ->native(true),
                                Toggle::make('is_closed')
                                    ->label('Libur')
                                    ->inline(false),
                            ])
                            ->addActionAlignment(Alignment::Start)
                            ->columns(4)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addActionLabel('Tambah hari')
                            ->collapsed()
                            ->itemLabel(fn (array $state): string => self::DAY_LABELS[$state['day_of_week'] ?? ''] ?? 'Jam operasional'),
                    ]),
                Grid::make(2)
                    ->schema([
                        Section::make('Lokasi')
                            ->description('Koordinat untuk peta landing dan directory.')
                            ->icon(Heroicon::OutlinedMapPin)
                            ->columns(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.0000001),
                                TextInput::make('longitude')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.0000001),
                                TextInput::make('geofence_radius_m')
                                    ->label('Radius geofence (m)')
                                    ->numeric()
                                    ->default(30)
                                    ->required()
                                    ->visible(fn (): bool => SubscriptionAccess::allows('settings_full')),
                                TextInput::make('gps_accuracy_max_m')
                                    ->label('Akurasi GPS maks (m)')
                                    ->numeric()
                                    ->default(50)
                                    ->required()
                                    ->visible(fn (): bool => SubscriptionAccess::allows('settings_full')),
                            ]),
                        Section::make('QRIS statis')
                            ->description('Gambar QR untuk pembayaran non-tunai.')
                            ->icon(Heroicon::OutlinedQrCode)
                            ->visible(fn (): bool => SubscriptionAccess::allows('settings_full'))
                            ->schema([
                                FileUpload::make('qris_image_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->imagePreviewHeight('180')
                                    ->panelLayout('compact')
                                    ->directory('outlets/qris')
                                    ->disk('public')
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                            ]),
                    ]),
                Section::make('Pajak & antrian kasir')
                    ->description('Persentase pajak/service dan batas waktu antrian tamu.')
                    ->icon(Heroicon::OutlinedReceiptPercent)
                    ->columns([
                        'default' => 2,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 5,
                        '2xl' => 5,
                    ])
                    ->visible(fn (): bool => SubscriptionAccess::allows('settings_full'))
                    ->schema([
                        TextInput::make('pb1_pct')
                            ->label('PB1 (%)')
                            ->numeric()
                            ->default(10)
                            ->required()
                            ->suffix('%'),
                        TextInput::make('service_pct')
                            ->label('Service charge (%)')
                            ->numeric()
                            ->default(5)
                            ->required()
                            ->suffix('%'),
                        TextInput::make('tax_mode')
                            ->label('Mode pajak')
                            ->disabled()
                            ->dehydrated(false)
                            ->default('exclusive')
                              ->hintAction(self::ttlHintAction(
                                'Pajak',
                                'Mode pajak',
                                'Saat ini hanya tersedia mode exclusive.',
                            )),
                        TextInput::make('claim_ttl_minutes')
                            ->label('TTL klaim meja')
                            ->numeric()
                            ->default(10)
                            ->suffix('Menit')
                            ->required()
                            ->hintAction(self::ttlHintAction(
                                'claimTtlHelp',
                                'TTL klaim meja',
                                'Batas waktu setelah tamu scan QR dan mengunci meja. Jika belum checkout sampai waktu habis, sesi ditutup dan meja otomatis kosong. Tidak berlaku jika sudah ada pesanan menunggu kasir atau sudah lunas (tamu sedang makan).',
                            )),
                        TextInput::make('awaiting_cashier_ttl_minutes')
                            ->label('TTL antrian kasir')
                            ->numeric()
                            ->default(20)
                            ->suffix('Menit')
                            ->required()
                            ->hintAction(self::ttlHintAction(
                                'awaitingCashierTtlHelp',
                                'TTL antrian kasir',
                                'Batas waktu pesanan menunggu kasir menekan Terima atau Tolak. Jika kasir tidak merespons sampai waktu habis, pesanan dibatalkan otomatis dan digit unik QRIS dilepas. Dapur tidak masak sebelum lunas.',
                            )),
                        Toggle::make('auto_print_receipt')
                            ->label('Cetak struk otomatis setelah terima bayar')
                            ->helperText('Membuka PDF struk yang sama (80mm) di dialog cetak browser. Matikan jika kasir ingin cetak manual.')
                            ->inline(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function ttlHintAction(string $name, string $heading, string $description): Action
    {
        return Action::make($name)
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->iconButton()
            ->color('gray')
            ->label($heading)
            ->modalHeading($heading)
            ->modalDescription($description)
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOutlet::route('/'),
        ];
    }
}
