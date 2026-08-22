<?php

namespace App\Filament\Pages;

use App\Filament\Support\TableRightClick;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leek\FilamentRightClick\Menu\ContextMenuItem;

abstract class SoftDeleteTrashPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.trash';

    public function getTitle(): string
    {
        return 'Trash';
    }

    public function table(Table $table): Table
    {
        $table = $table
            ->query($this->getTrashTableQuery())
            ->columns([
                ...$this->getTrashTableColumns(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('deleted_at', 'desc')
            ->toolbarActions([
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ])
            ->contextMenuBulkActions([
                ContextMenuItem::forBulkAction(RestoreBulkAction::make()),
                ContextMenuItem::forBulkAction(ForceDeleteBulkAction::make()),
            ])
            ->emptyStateHeading('Tidak ada data di tempat sampah');

        return TableRightClick::apply($table, fn (): array => [
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ]);
    }

    protected function getTrashTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()->onlyTrashed();
    }

    /**
     * @return array<int, Column>
     */
    abstract protected function getTrashTableColumns(): array;
}
