<?php

namespace App\Filament\Blogger\Resources\BlogTags\Pages;

use App\Filament\Blogger\Resources\BlogTags\BlogTagResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogTag extends ViewRecord
{
    protected static string $resource = BlogTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => auth()->user()?->isPlatformOperator() ?? false),
        ];
    }
}
