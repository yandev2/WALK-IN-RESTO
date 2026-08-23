<?php

namespace App\Filament\Resources\CmsFaqs;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\CmsFaqs\Pages\ManageCmsFaqs;
use App\Filament\Resources\CmsFaqs\Pages\TrashCmsFaqs;
use App\Filament\Support\TableRightClick;
use App\Models\CmsFaq;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CmsFaqResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = CmsFaq::class;

    protected static string $permission = 'cms.manage';

    protected static string $subscriptionFeature = 'cms';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|UnitEnum|null $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $pluralModelLabel  = 'FAQ';

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Isi FAQ')
                    ->description('Tampil di blok FAQ halaman landing.')
                    ->columnSpanFull()
                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                    ->schema([
                        TextInput::make('question')
                            ->label('Pertanyaan')
                            ->placeholder('Apakah perlu reservasi?')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('answer_html')
                            ->label('Jawaban')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('Tampilan')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(2)
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
                TextColumn::make('question')->label('Pertanyaan')->searchable()->wrap()->grow(),
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
            'index' => ManageCmsFaqs::route('/'),
            'trash' => TrashCmsFaqs::route('/trash'),
        ];
    }
}
