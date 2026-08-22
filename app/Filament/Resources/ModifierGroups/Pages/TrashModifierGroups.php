<?php

namespace App\Filament\Resources\ModifierGroups\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\ModifierGroups\ModifierGroupResource;
use Filament\Tables\Columns\TextColumn;

class TrashModifierGroups extends SoftDeleteTrashPage
{
    protected static string $resource = ModifierGroupResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('min_select')->label('Min'),
            TextColumn::make('max_select')->label('Max'),
        ];
    }
}
