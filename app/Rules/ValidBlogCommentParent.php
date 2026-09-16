<?php

namespace App\Rules;

use App\Enums\BlogCommentStatus;
use App\Models\BlogComment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBlogCommentParent implements ValidationRule
{
    public function __construct(private int $blogPostId) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $parent = BlogComment::query()->find($value);

        if (! $parent || $parent->blog_post_id !== $this->blogPostId) {
            $fail(__('blog.invalid_reply_parent', [], app()->getLocale()) ?? 'Komentar ini tidak dapat dibalas.');

            return;
        }

        if ($parent->parent_id !== null) {
            $fail(__('blog.invalid_reply_parent', [], app()->getLocale()) ?? 'Komentar ini tidak dapat dibalas.');

            return;
        }

        if ($parent->status !== BlogCommentStatus::Approved) {
            $fail(__('blog.invalid_reply_parent', [], app()->getLocale()) ?? 'Komentar ini tidak dapat dibalas.');
        }
    }
}
