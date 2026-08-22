<?php

namespace App\Filament\Resources\CmsFaqs\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\CmsFaqs\CmsFaqResource;
use Filament\Tables\Columns\TextColumn;

class TrashCmsFaqs extends SoftDeleteTrashPage
{
    protected static string $resource = CmsFaqResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('question')->label('Pertanyaan')->searchable()->limit(60),
            TextColumn::make('sort_order')->label('Urutan'),
        ];
    }
}
