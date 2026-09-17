<?php

namespace App\Filament\Resources\CmsBanners;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\CmsBanners\Pages\ManageCmsBanners;
use App\Filament\Resources\CmsBanners\Pages\TrashCmsBanners;
use App\Filament\Support\TableRightClick;
use App\Models\CmsBanner;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CmsBannerResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = CmsBanner::class;

    protected static string $permission = 'cms.manage';

    protected static string $subscriptionFeature = 'cms';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Banner';

    protected static ?string $pluralModelLabel  = 'banner';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten promo')
                    ->description('Teks yang tampil di kartu banner landing.')
                    ->icon(Heroicon::OutlinedMegaphone)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->maxLength(120)
                            ->columnSpanFull(),
                        Textarea::make('subtitle')
                            ->label('Deskripsi singkat')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        TextInput::make('badge_text')
                            ->label('Label badge')
                            ->placeholder('Baru!')
                            ->maxLength(40),
                        TextInput::make('price_label')
                            ->label('Label harga')
                            ->placeholder('Rp 45.000')
                            ->maxLength(60),
                        TextInput::make('cta_label')
                            ->label('Teks tombol')
                            ->placeholder('Lihat promo')
                            ->maxLength(60),
                        TextInput::make('link_url')
                            ->label('URL tautan')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://'),
                    ]),
                Section::make('Gambar')
                    ->description('Rasio 16:9, tampil sebagai slide promo di landing.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image_path')
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
                            ->required()
                            ->directory('cms/banners')
                            ->disk('public')
                            ->maxSize(15360)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WEBP. Rasio 16:9, otomatis dipotong & dikompres maks. 1 MB (file awal hingga 15 MB).'),
                    ]),
                Section::make('Publikasi')
                    ->description('Banner tampil jika aktif dan waktu sekarang di antara mulai–selesai.')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Mulai')
                            ->required()
                            ->seconds(false)
                            ->native(false),
                        DateTimePicker::make('ends_at')
                            ->label('Selesai')
                            ->required()
                            ->seconds(false)
                            ->native(false)
                            ->after('starts_at'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required()
                            ->helperText('Angka kecil tampil lebih dulu. Bisa juga digeser di tabel.'),
                        Toggle::make('is_active')
                            ->label('Tampilkan di landing')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                ImageColumn::make('image_path')->label('Gambar')->disk('public')->square()
                    ->imageSize(48),
                TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                TextColumn::make('starts_at')->label('Mulai')->dateTime('d M Y H:i'),
                TextColumn::make('ends_at')->label('Selesai')->dateTime('d M Y H:i'),
                TextColumn::make('sort_order')->label('Urutan')->sortable()->alignCenter()->badge()->color('success'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');

        return TableRightClick::apply($table, fn(): array => [
            EditAction::make()->modalWidth('2xl'),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCmsBanners::route('/'),
            'trash' => TrashCmsBanners::route('/trash'),
        ];
    }
}
