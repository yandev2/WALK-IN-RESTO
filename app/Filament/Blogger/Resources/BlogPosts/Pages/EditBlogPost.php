<?php

namespace App\Filament\Blogger\Resources\BlogPosts\Pages;

use App\Enums\BlogPostStatus;
use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Concerns\NormalizesBlogPostPublishing;
use App\Filament\Blogger\Resources\BlogPosts\BlogPostResource;
use App\Models\BlogPost;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    use HandlesTranslatableForm {
        mutateFormDataBeforeSave as extractTranslatableSaveData;
    }
    use NormalizesBlogPostPublishing;

    protected static string $resource = BlogPostResource::class;

    public function mount(int|string $record): void
    {
        BlogPost::publishDueScheduled();

        parent::mount($record);

        if ($this->record->status === BlogPostStatus::Scheduled && $this->record->published_at?->lte(now())) {
            $this->record->refresh();
            $this->fillForm();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->normalizePublishingData($data);

        return $this->extractTranslatableSaveData($data);
    }
}
