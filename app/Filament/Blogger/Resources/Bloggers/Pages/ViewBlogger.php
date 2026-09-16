<?php

namespace App\Filament\Blogger\Resources\Bloggers\Pages;

use App\Filament\Blogger\Resources\Bloggers\BloggerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewBlogger extends ViewRecord
{
    protected static string $resource = BloggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Ubah Penulis')
                ->icon(Heroicon::OutlinedPencilSquare),
        ];
    }
}
