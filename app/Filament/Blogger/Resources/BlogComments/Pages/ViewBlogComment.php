<?php

namespace App\Filament\Blogger\Resources\BlogComments\Pages;

use App\Filament\Blogger\Resources\BlogComments\BlogCommentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogComment extends ViewRecord
{
    protected static string $resource = BlogCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => auth()->user()?->isPlatformOperator() || (int) $this->record->blogPost?->author_id === (int) auth()->id()),
        ];
    }
}
