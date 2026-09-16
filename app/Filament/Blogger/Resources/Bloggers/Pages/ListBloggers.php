<?php

namespace App\Filament\Blogger\Resources\Bloggers\Pages;

use App\Filament\Blogger\Resources\Bloggers\BloggerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListBloggers extends ListRecords
{
    protected static string $resource = BloggerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Penulis Baru')
                ->icon(Heroicon::OutlinedUserPlus),
        ];
    }
}
