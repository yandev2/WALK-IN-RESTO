<?php

namespace App\Filament\Blogger\Resources\BlogComments\Pages;

use App\Filament\Blogger\Resources\BlogComments\BlogCommentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogComment extends EditRecord
{
    protected static string $resource = BlogCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->visible(fn (): bool => auth()->user()?->isPlatformOperator() || (int) $this->record->blogPost?->author_id === (int) auth()->id()),
        ];
    }
}
