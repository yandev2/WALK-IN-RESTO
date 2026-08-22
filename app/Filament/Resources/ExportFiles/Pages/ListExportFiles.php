<?php

namespace App\Filament\Resources\ExportFiles\Pages;

use App\Filament\Pages\GenerateReport;
use App\Filament\Resources\ExportFiles\ExportFileResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListExportFiles extends ListRecords
{
    protected static string $resource = ExportFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportFileResource::trashPageAction(),
            Action::make('generate')
                ->label('Buat laporan')
                ->icon(Heroicon::OutlinedDocumentChartBar)
                ->url(GenerateReport::getUrl()),
        ];
    }
}
