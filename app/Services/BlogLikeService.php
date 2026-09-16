<?php

namespace App\Services;

use App\Models\BlogLike;
use App\Models\BlogPost;
use App\Support\VisitorHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogLikeService
{
    public function hasLiked(BlogPost $post, Request $request): bool
    {
        return BlogLike::query()
            ->where('blog_post_id', $post->id)
            ->where('visitor_hash', VisitorHash::fromRequest($request))
            ->exists();
    }

    /**
     * @return array{liked: bool, likes_count: int}
     */
    public function toggle(BlogPost $post, Request $request): array
    {
        $visitorHash = VisitorHash::fromRequest($request);
        $ipAddress = $request->ip() ?? '0.0.0.0';

        return DB::transaction(function () use ($post, $visitorHash, $ipAddress) {
            $lockedPost = BlogPost::query()
                ->whereKey($post->id)
                ->lockForUpdate()
                ->firstOrFail();

            $existingLike = BlogLike::query()
                ->where('blog_post_id', $lockedPost->id)
                ->where('visitor_hash', $visitorHash)
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $lockedPost->decrement('likes_count');
                $liked = false;
            } else {
                BlogLike::query()->create([
                    'blog_post_id' => $lockedPost->id,
                    'visitor_hash' => $visitorHash,
                    'ip_address' => $ipAddress,
                ]);
                $lockedPost->increment('likes_count');
                $liked = true;
            }

            if ($lockedPost->likes_count < 0) {
                $lockedPost->updateQuietly(['likes_count' => 0]);
            }

            return [
                'liked' => $liked,
                'likes_count' => (int) $lockedPost->fresh()->likes_count,
            ];
        });
    }
}
