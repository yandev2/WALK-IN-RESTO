<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\Users\UserResource;
use Filament\Tables\Columns\TextColumn;

class TrashUsers extends SoftDeleteTrashPage
{
    protected static string $resource = UserResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('email')->label('Email')->searchable(),
            TextColumn::make('username')->label('Username')->searchable(),
        ];
    }
}
