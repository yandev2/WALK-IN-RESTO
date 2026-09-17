<?php

namespace App\Filament\Blogger\Resources\BlogComments\Pages;

use App\Filament\Blogger\Resources\BlogComments\BlogCommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogComment extends CreateRecord
{
    protected static string $resource = BlogCommentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        if ($user && ! $user->isPlatformOperator()) {
            $post = \App\Models\BlogPost::query()
                ->where('id', $data['blog_post_id'] ?? null)
                ->where('author_id', $user->id)
                ->first();

            if (! $post) {
                abort(403, 'Anda tidak memiliki izin untuk mengomentari artikel ini.');
            }
        }

        return $data;
    }
}
