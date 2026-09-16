<?php

namespace App\Filament\Blogger\Resources\BlogComments\Pages;

use App\Filament\Blogger\Resources\BlogComments\BlogCommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogComment extends CreateRecord
{
    protected static string $resource = BlogCommentResource::class;
}
