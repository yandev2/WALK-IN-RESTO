<?php

namespace App\Filament\Resources\DiningTables\Pages;

use App\Filament\Resources\DiningTables\DiningTableResource;
use App\Models\DiningTable;
use App\Support\ActivityLogger;
use App\Support\TableFloorPlan;
use App\Support\TenantContext;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;

class ManageDiningTables extends ManageRecords
{
    protected static string $resource = DiningTableResource::class;

    #[Url(as: 'area')]
    public ?string $activeTab = null;

    public bool $isEditingLayout = false;

    public function getDefaultActionSchemaResolver(Action $action): ?\Closure
    {
        return match (true) {
            $action instanceof CreateAction, $action instanceof EditAction => fn (Schema $schema): Schema => $this->form($schema->columns(1)),
            default => parent::getDefaultActionSchemaResolver($action),
        };
    }

    public function mount(): void
    {
        parent::mount();

        $this->loadDefaultActiveTab();
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $outletId = TenantContext::outletId();

        if ($outletId === null) {
            return [];
        }

        $tabs = [];

        foreach (TableFloorPlan::areasForOutlet($outletId) as $area) {
            $key = TableFloorPlan::areaTabKey($area);

            $tabs[$key] = Tab::make(TableFloorPlan::areaTabLabel($area))
                ->badge(fn (): int => $this->areaCount($area))
                ->modifyQueryUsing(fn (Builder $query): Builder => TableFloorPlan::applyAreaScope($query, $area));
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            DiningTableResource::trashPageAction(),
            Action::make('toggleLayoutEdit')
                ->label(fn (): string => $this->isEditingLayout ? 'Selesai edit' : 'Edit denah')
                ->icon(fn (): Heroicon => $this->isEditingLayout ? Heroicon::OutlinedCheck : Heroicon::OutlinedArrowsPointingOut)
                ->color(fn (): string => $this->isEditingLayout ? 'success' : 'gray')
                ->action(function (): void {
                    $this->isEditingLayout = ! $this->isEditingLayout;
                }),
            Action::make('autoLayout')
                ->label('Atur otomatis')
                ->icon(Heroicon::OutlinedSquares2x2)
                ->color('gray')
                ->visible(fn (): bool => $this->isEditingLayout)
                ->requiresConfirmation()
                ->modalHeading('Atur ulang denah area ini?')
                ->modalDescription('Semua meja di area aktif akan di-grid otomatis. Posisi manual saat ini akan diganti.')
                ->action(function (): void {
                    $this->autoLayoutCurrentArea();
                }),
            CreateAction::make(),
        ];
    }

    public function saveTablePosition(int $tableId, float $x, float $y): void
    {
        if (! $this->isEditingLayout) {
            return;
        }

        $table = DiningTable::withoutRestaurantScope()->findOrFail($tableId);

        try {
            TableFloorPlan::assertSameOutlet($table, TenantContext::outletId());
            TableFloorPlan::savePosition($table, $x, $y);

            ActivityLogger::log('table.move_layout', [
                'new' => [
                    'table_id' => $table->id,
                    'floor_x_pct' => $table->fresh()->floor_x_pct,
                    'floor_y_pct' => $table->fresh()->floor_y_pct,
                ],
            ]);
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('Gagal menyimpan posisi meja')
                ->body(collect($exception->errors())->flatten()->first() ?? 'Posisi tidak valid.')
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Posisi meja disimpan')
            ->success()
            ->send();
    }

    public function autoLayoutCurrentArea(): void
    {
        $outletId = TenantContext::outletId();

        if ($outletId === null) {
            return;
        }

        $area = $this->activeAreaValue();
        $updated = TableFloorPlan::autoLayoutArea($outletId, $area);

        Notification::make()
            ->title('Denah diatur otomatis')
            ->body($updated.' meja di area '.TableFloorPlan::areaTabLabel($area).' telah di-grid ulang.')
            ->success()
            ->send();

        $this->resetTable();
    }

    public function updatedIsEditingLayout(bool $value): void
    {
        if ($value) {
            return;
        }

        $this->resetTable();
    }

    private function activeAreaValue(): ?string
    {
        if (! is_string($this->activeTab)) {
            return null;
        }

        foreach (TableFloorPlan::areasForOutlet(TenantContext::outletId()) as $area) {
            if (TableFloorPlan::areaTabKey($area) === $this->activeTab) {
                return $area;
            }
        }

        return null;
    }

    private function areaCount(?string $area): int
    {
        $query = DiningTableResource::getEloquentQuery();

        return TableFloorPlan::applyAreaScope($query, $area)->count();
    }
}
