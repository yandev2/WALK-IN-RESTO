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
        $user = auth()->user();
        $isOperator = $user?->isPlatformOperator() ?? false;
        $isAuthor = $user && (int) $this->record->author_id === (int) $user->id;

        return [
            ViewAction::make(),
            DeleteAction::make()
                ->visible(fn (): bool => $isOperator || $isAuthor),
            ForceDeleteAction::make()
                ->visible(fn (): bool => $isOperator),
            RestoreAction::make()
                ->visible(fn (): bool => $isOperator || $isAuthor),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! (auth()->user()?->isPlatformOperator() ?? false)) {
            unset($data['author_id']);
        }

        $data = $this->normalizePublishingData($data);

        return $this->extractTranslatableSaveData($data);
    }
}
