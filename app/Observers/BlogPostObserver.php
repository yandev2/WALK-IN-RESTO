<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostObserver
{
    /**
     * Handle the BlogPost "deleting" event.
     */
    public function deleting(BlogPost $post): void
    {
        // If the model does not use soft deletes or is already being force-deleted, purge immediately
        if (! in_array(SoftDeletes::class, class_uses_recursive($post), true) || $post->isForceDeleting()) {
            $this->purgeAllPostFiles($post);
        }
    }

    /**
     * Handle the BlogPost "forceDeleting" event.
     */
    public function forceDeleting(BlogPost $post): void
    {
        $this->purgeAllPostFiles($post);
    }

    /**
     * Handle the BlogPost "updating" event.
     */
    public function updating(BlogPost $post): void
    {
        if ($post->isDirty('featured_image')) {
            $oldFeatured = $post->getOriginal('featured_image');
            if ($this->shouldDeleteOldImage($oldFeatured, [$post->featured_image, $post->og_image], $post->id)) {
                $this->deleteFile($oldFeatured);
            }
        }

        if ($post->isDirty('og_image')) {
            $oldOg = $post->getOriginal('og_image');
            if ($this->shouldDeleteOldImage($oldOg, [$post->og_image, $post->featured_image], $post->id)) {
                $this->deleteFile($oldOg);
            }
        }
    }

    /**
     * Purge all physical files associated with a post (featured image, og image, and inline content attachments).
     */
    public function purgeAllPostFiles(BlogPost $post): void
    {
        $pathsToDelete = [];

        if ($path = self::extractBlogStoragePath($post->featured_image)) {
            if (self::isDeletableBlogPath($path)) {
                $pathsToDelete[] = $path;
            }
        }

        if ($path = self::extractBlogStoragePath($post->og_image)) {
            if (self::isDeletableBlogPath($path)) {
                $pathsToDelete[] = $path;
            }
        }

        // Clean inline attachments from translations
        foreach ($post->translations as $translation) {
            if (filled($translation->content)) {
                $inlinePaths = self::extractInlineStoragePathsFromHtml((string) $translation->content);
                foreach ($inlinePaths as $inlinePath) {
                    if (self::isDeletableBlogPath($inlinePath)) {
                        $pathsToDelete[] = $inlinePath;
                    }
                }
            }
        }

        $disk = Storage::disk('public');
        foreach (array_unique($pathsToDelete) as $relativePath) {
            if (self::isPathReferencedGlobally($relativePath, $post->id)) {
                continue;
            }

            if ($disk->exists($relativePath)) {
                $disk->delete($relativePath);
            }
        }
    }

    public static function extractBlogStoragePath(?string $src): ?string
    {
        if (blank($src)) {
            return null;
        }

        $src = trim((string) $src);

        $parsedPath = parse_url($src, PHP_URL_PATH);
        $path = $parsedPath !== false && $parsedPath !== null ? $parsedPath : $src;

        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        return $path;
    }

    public static function isDeletableBlogPath(string $path): bool
    {
        return Str::startsWith($path, 'blog/');
    }

    public static function deleteFile(?string $rawPath): void
    {
        $path = self::extractBlogStoragePath($rawPath);
        if ($path && self::isDeletableBlogPath($path)) {
            $disk = Storage::disk('public');
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    private function shouldDeleteOldImage(?string $oldPath, array $newPaths, int $currentPostId): bool
    {
        if (blank($oldPath)) {
            return false;
        }

        $relativeOld = self::extractBlogStoragePath($oldPath);
        if (! $relativeOld || ! self::isDeletableBlogPath($relativeOld)) {
            return false;
        }

        foreach ($newPaths as $new) {
            if ($relativeOld === self::extractBlogStoragePath($new)) {
                return false;
            }
        }

        return ! self::isPathReferencedGlobally($relativeOld, $currentPostId);
    }

    public static function isPathReferencedGlobally(string $relativePath, ?int $ignorePostId = null): bool
    {
        $query = BlogPost::query();
        if ($ignorePostId !== null) {
            $query->where('id', '!=', $ignorePostId);
        }

        $referencedInMeta = (clone $query)->where(function ($q) use ($relativePath) {
            $q->where('featured_image', $relativePath)
                ->orWhere('featured_image', 'like', "%{$relativePath}%")
                ->orWhere('og_image', $relativePath)
                ->orWhere('og_image', 'like', "%{$relativePath}%");
        })->exists();

        if ($referencedInMeta) {
            return true;
        }

        return (clone $query)->whereHas('translations', function ($q) use ($relativePath) {
            $q->where('content', 'like', "%{$relativePath}%");
        })->exists();
    }

    public static function extractInlineStoragePathsFromHtml(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $paths = [];
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);

        foreach ($matches[1] ?? [] as $src) {
            $path = self::extractBlogStoragePath($src);
            if ($path) {
                $paths[] = $path;
            }
        }

        return array_unique($paths);
    }
}
