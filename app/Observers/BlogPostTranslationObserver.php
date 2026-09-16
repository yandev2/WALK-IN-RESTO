<?php

namespace App\Observers;

use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use Illuminate\Support\Facades\Storage;

class BlogPostTranslationObserver
{
    /**
     * Handle the BlogPostTranslation "updating" event.
     * If an inline image in content is removed from the text editor, delete the physical file.
     */
    public function updating(BlogPostTranslation $translation): void
    {
        if ($translation->isDirty('content')) {
            $oldContent = (string) $translation->getOriginal('content');
            $newContent = (string) $translation->content;

            $oldPaths = BlogPostObserver::extractInlineStoragePathsFromHtml($oldContent);
            $newPaths = BlogPostObserver::extractInlineStoragePathsFromHtml($newContent);

            $removedPaths = array_diff($oldPaths, $newPaths);
            $disk = Storage::disk('public');

            foreach ($removedPaths as $removedPath) {
                if (
                    BlogPostObserver::isDeletableBlogPath($removedPath)
                    && ! in_array($removedPath, $newPaths, true)
                    && ! $this->isInlinePathReferencedElsewhere($removedPath, (int) $translation->id)
                ) {
                    if ($disk->exists($removedPath)) {
                        $disk->delete($removedPath);
                    }
                }
            }
        }
    }

    private function isInlinePathReferencedElsewhere(string $relativePath, int $currentTranslationId): bool
    {
        $referencedInMeta = BlogPost::query()->where(function ($q) use ($relativePath) {
            $q->where('featured_image', $relativePath)
                ->orWhere('featured_image', 'like', "%{$relativePath}%")
                ->orWhere('og_image', $relativePath)
                ->orWhere('og_image', 'like', "%{$relativePath}%");
        })->exists();

        if ($referencedInMeta) {
            return true;
        }

        return BlogPostTranslation::query()
            ->where('id', '!=', $currentTranslationId)
            ->where('content', 'like', "%{$relativePath}%")
            ->exists();
    }
}
