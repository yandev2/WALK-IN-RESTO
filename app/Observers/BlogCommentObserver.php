<?php

namespace App\Observers;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use App\Models\BlogPost;

class BlogCommentObserver
{
    public function created(BlogComment $comment): void
    {
        if ($comment->status !== BlogCommentStatus::Approved) {
            return;
        }

        $this->adjustCommentsCount($comment, 1);

        if (! $comment->approved_at) {
            $comment->updateQuietly(['approved_at' => now()]);
        }
    }

    public function updated(BlogComment $comment): void
    {
        if (! $comment->wasChanged('status')) {
            return;
        }

        $original = $comment->getOriginal('status');
        $wasApproved = $original === BlogCommentStatus::Approved->value
            || $original === BlogCommentStatus::Approved;

        $isApproved = $comment->status === BlogCommentStatus::Approved;

        if ($wasApproved && ! $isApproved) {
            $this->adjustCommentsCount($comment, -1);
        }

        if (! $wasApproved && $isApproved) {
            $this->adjustCommentsCount($comment, 1);

            if (! $comment->approved_at) {
                $comment->updateQuietly(['approved_at' => now()]);
            }
        }
    }

    public function deleting(BlogComment $comment): void
    {
        $decrement = 0;

        if ($comment->status === BlogCommentStatus::Approved) {
            $decrement++;
        }

        if ($comment->exists && $comment->parent_id === null) {
            $decrement += $comment->replies()
                ->where('status', BlogCommentStatus::Approved)
                ->count();
        }

        if ($decrement > 0) {
            $this->adjustCommentsCount($comment, -$decrement);
        }
    }

    private function adjustCommentsCount(BlogComment $comment, int $delta): void
    {
        $post = BlogPost::query()->find($comment->blog_post_id);

        if (! $post || $delta === 0) {
            return;
        }

        $next = max(0, (int) $post->comments_count + $delta);

        $post->updateQuietly(['comments_count' => $next]);
    }
}
