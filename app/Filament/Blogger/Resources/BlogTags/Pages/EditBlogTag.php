<?php

namespace App\Filament\Blogger\Resources\BlogTags\Pages;

use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Resources\BlogTags\BlogTagResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogTag extends EditRecord
{
    use HandlesTranslatableForm;

    protected static string $resource = BlogTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
