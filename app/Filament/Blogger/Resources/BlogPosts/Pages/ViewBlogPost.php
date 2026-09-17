<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Pages;

use App\Filament\Blogger\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogPost extends ViewRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $canEdit = $user && ($user->isPlatformOperator() || (int) $this->record->author_id === (int) $user->id);

        return [
            EditAction::make()
                ->visible(fn (): bool => $canEdit),
        ];
    }
}
