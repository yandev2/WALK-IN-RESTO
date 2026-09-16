<?php

namespace App\Http\Controllers;

use App\Enums\BlogCommentStatus;
use App\Http\Requests\StoreBlogCommentRequest;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;

class BlogCommentController extends Controller
{
    public function store(StoreBlogCommentRequest $request, string $slug): RedirectResponse
    {
        $post = BlogPost::query()
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->first();

        if (! $post || ! $post->is_active || ! BlogPost::query()->public()->whereKey($post->id)->exists()) {
            abort(404);
        }

        $parentId = $request->validated('parent_id');

        BlogComment::query()->create([
            'blog_post_id' => $post->id,
            'parent_id' => $parentId,
            'is_author_reply' => false,
            'author_name' => $request->validated('author_name'),
            'author_email' => $request->validated('author_email'),
            'content' => $request->validated('content'),
            'status' => BlogCommentStatus::Pending,
            'ip_address' => $request->ip() ?? '0.0.0.0',
            'user_agent' => $request->userAgent(),
        ]);

        $redirect = redirect()
            ->route('blog.show', ['slug' => $slug])
            ->with('comment_success', __('blog.comment_pending'));

        if ($parentId) {
            $redirect = $redirect
                ->with('reply_parent_id', $parentId)
                ->withFragment('comment-'.$parentId);
        }

        return $redirect;
    }
}
