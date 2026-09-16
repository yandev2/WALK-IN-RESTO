<?php

namespace App\Filament\Blogger\Resources\Bloggers\Pages;

use App\Filament\Blogger\Resources\Bloggers\BloggerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogger extends CreateRecord
{
    protected static string $resource = BloggerResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignRole('blogger');
    }
}
