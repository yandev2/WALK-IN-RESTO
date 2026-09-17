<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\BlogLikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogLikeController extends Controller
{
    public function toggle(Request $request, BlogLikeService $likes): JsonResponse
    {
        $slug = (string) $request->route('slug');
        $post = BlogPost::query()
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->first();

        if (! $post || ! $post->is_active || ! BlogPost::query()->public()->whereKey($post->id)->exists()) {
            abort(404);
        }

        $result = $likes->toggle($post, $request);

        return response()->json($result);
    }
}
