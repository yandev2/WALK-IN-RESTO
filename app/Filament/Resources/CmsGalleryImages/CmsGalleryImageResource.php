<?php

namespace App\Filament\Resources\CmsGalleryImages;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\CmsGalleryImages\Pages\ManageCmsGalleryImages;
use App\Filament\Resources\CmsGalleryImages\Pages\TrashCmsGalleryImages;
use App\Filament\Support\TableRightClick;
use App\Models\CmsGalleryImage;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
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

class CmsGalleryImageResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = CmsGalleryImage::class;

    protected static string $permission = 'cms.manage';

    protected static string $subscriptionFeature = 'cms';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $pluralModelLabel  = 'foto galeri';

    protected static ?string $recordTitleAttribute = 'caption';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gambar')
                    ->description('Tampil di grid galeri halaman landing. Rasio 4:3 direkomendasikan.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image_path')
                            ->hiddenLabel()
                            ->image()
                            ->imageAspectRatio('4:3')
                            ->panelAspectRatio('4:3')
                            ->imagePreviewHeight('180')
                            ->panelLayout('integrated')
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('900')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->imageEditorAspectRatios(['4:3'])
                            ->required()
                            ->directory('cms/gallery')
                            ->disk('public')
                            ->maxSize(15360)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WEBP. Rasio 4:3, otomatis dipotong & dikompres (maks. 15 MB).'),
                    ]),
                Section::make('Keterangan')
                    ->columnSpanFull()
                    ->description('Opsional. Ditampilkan di bawah foto di landing.')
                    ->icon(Heroicon::OutlinedChatBubbleBottomCenterText)
                    ->schema([
                        TextInput::make('caption')
                            ->hiddenLabel()
                            ->placeholder('Contoh: Interior ruang utama')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ]),
                Section::make('Tampilan')
                    ->compact()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
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
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),
                ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->imageSize(64),
                TextColumn::make('caption')
                    ->label('Keterangan')
                    ->placeholder('—')
                    ->searchable()
                    ->grow()
                    ->wrap(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->width('sm')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
            'index' => ManageCmsGalleryImages::route('/'),
            'trash' => TrashCmsGalleryImages::route('/trash'),
        ];
    }
}
