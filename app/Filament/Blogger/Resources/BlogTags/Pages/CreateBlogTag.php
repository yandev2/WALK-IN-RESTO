<?php

namespace App\Filament\Blogger\Resources\BlogTags\Pages;

use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Resources\BlogTags\BlogTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogTag extends CreateRecord
{
    use HandlesTranslatableForm;

    protected static string $resource = BlogTagResource::class;
}
