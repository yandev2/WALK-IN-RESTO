<?php

namespace App\Filament\Resources\ExportFiles;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\ExportFiles\Pages\ListExportFiles;
use App\Filament\Resources\ExportFiles\Pages\TrashExportFiles;
use App\Filament\Support\TableRightClick;
use App\Models\ExportFile;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ExportFileResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = ExportFile::class;

    protected static string $permission = 'analytics.view';

    protected static string $subscriptionFeature = 'analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Riwayat ekspor';

    protected static ?string $modelLabel = 'ekspor';

    protected static ?string $pluralModelLabel = 'riwayat ekspor';

    protected static ?string $tenantOwnershipRelationshipName = 'restaurant';

    protected static ?string $recordTitleAttribute = 'filename';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('module_label')->label('Modul'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('format')->badge(),
                        TextEntry::make('user.name')->label('Dibuat oleh'),
                        TextEntry::make('filename')->columnSpanFull(),
                        TextEntry::make('readable_size')->label('Ukuran'),
                        TextEntry::make('created_at')->dateTime('d M Y H:i'),
                        TextEntry::make('error_message')
                            ->label('Pesan error')
                            ->columnSpanFull()
                            ->visible(fn (ExportFile $record): bool => filled($record->error_message)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return TableRightClick::apply(
            $table
                ->defaultSort('created_at', 'desc')
                ->columns([
                    TextColumn::make('created_at')
                        ->label('Waktu')
                        ->dateTime('d M Y H:i')
                        ->sortable(),
                    TextColumn::make('module_label')
                        ->label('Modul')
                        ->searchable(query: function ($query, string $search) {
                            $keys = collect(ExportFile::moduleLabels())
                                ->filter(fn (string $label): bool => str_contains(strtolower($label), strtolower($search)))
                                ->keys()
                                ->all();

                            return $query->whereIn('module', $keys)->orWhere('module', 'like', "%{$search}%");
                        }),
                    TextColumn::make('format')->badge(),
                    TextColumn::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            ExportFile::STATUS_COMPLETED => 'success',
                            ExportFile::STATUS_FAILED => 'danger',
                            ExportFile::STATUS_PROCESSING => 'warning',
                            default => 'gray',
                        }),
                    TextColumn::make('user.name')->label('Oleh')->toggleable(),
                    TextColumn::make('readable_size')->label('Ukuran'),
                    TextColumn::make('filename')->searchable()->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    SelectFilter::make('module')
                        ->label('Modul')
                        ->options(ExportFile::moduleLabels()),
                    SelectFilter::make('status')
                        ->options([
                            ExportFile::STATUS_QUEUED => 'Queued',
                            ExportFile::STATUS_PROCESSING => 'Processing',
                            ExportFile::STATUS_COMPLETED => 'Completed',
                            ExportFile::STATUS_FAILED => 'Failed',
                        ]),
                    SelectFilter::make('format')
                        ->options([
                            ExportFile::FORMAT_EXCEL => 'Excel',
                            ExportFile::FORMAT_PDF => 'PDF',
                        ]),
                ]),
            fn (): array => [
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->url(fn (ExportFile $record): string => $record->downloadUrl())
                    ->openUrlInNewTab()
                    ->visible(fn (ExportFile $record): bool => $record->isDownloadable()),
                ViewAction::make(),
                DeleteAction::make(),
            ],
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExportFiles::route('/'),
            'trash' => TrashExportFiles::route('/trash'),
        ];
    }
}
