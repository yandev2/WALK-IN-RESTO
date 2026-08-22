<?php

namespace App\Filament\Resources\ExportFiles\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\ExportFiles\ExportFileResource;
use Filament\Tables\Columns\TextColumn;

class TrashExportFiles extends SoftDeleteTrashPage
{
    protected static string $resource = ExportFileResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('filename')->label('File')->searchable(),
            TextColumn::make('module_label')->label('Modul'),
            TextColumn::make('format')->badge(),
            TextColumn::make('user.name')->label('Oleh'),
        ];
    }
}
