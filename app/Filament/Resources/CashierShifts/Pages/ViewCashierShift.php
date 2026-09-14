<?php

namespace App\Filament\Resources\CashierShifts\Pages;

use App\Filament\Resources\CashierShifts\CashierShiftResource;
use App\Models\CashierShift;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewCashierShift extends ViewRecord
{
    protected static string $resource = CashierShiftResource::class;

    public function getTitle(): string|Htmlable
    {
        /** @var CashierShift $record */
        $record = $this->getRecord();

        return "Detail Shift Kasir #{$record->id}";
    }

    public function getSubheading(): ?string
    {
        /** @var CashierShift $record */
        $record = $this->getRecord();
        $statusText = $record->isOpen() ? 'Shift Sedang Berjalan' : 'Shift Telah Ditutup';
        $kasir = $record->user?->name ?? 'Kasir';

        return "Rekonsiliasi laci kas & pembukuan shift: {$kasir} ({$statusText})";
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Cetak struk shift')
                ->icon(Heroicon::OutlinedPrinter)
                ->color('primary')
                ->url(fn (CashierShift $record): string => route('shifts.print', ['shift' => $record->public_id]))
                ->openUrlInNewTab(),
        ];
    }
}
