<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Pages;

use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Concerns\NormalizesBlogPostPublishing;
use App\Filament\Blogger\Resources\BlogPosts\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    use HandlesTranslatableForm {
        mutateFormDataBeforeCreate as extractTranslatableCreateData;
    }
    use NormalizesBlogPostPublishing;

    protected static string $resource = BlogPostResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! (auth()->user()?->isPlatformOperator() ?? false)) {
            $data['author_id'] = auth()->id();
        } elseif (blank($data['author_id'] ?? null)) {
            $data['author_id'] = auth()->id();
        }

        $data = $this->normalizePublishingData($data);

        return $this->extractTranslatableCreateData($data);
    }
}
