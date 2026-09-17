<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogHeroSetting;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Services\BlogLikeService;
use App\Services\RecordVisitService;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, RecordVisitService $visits): View
    {
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $visits->record($request, 'blog_index', locale: $locale);

        $categories = $this->categoriesForFilter($locale);

        // Popular posts (most viewed & liked)
        $popularPosts = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->with(['translations', 'author', 'category.translations', 'tags.translations'])
            ->orderByDesc('views_count')
            ->orderByDesc('likes_count')
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        // Posts grouped by category (3 per category)
        $postsByCategory = [];
        foreach ($categories as $category) {
            $catPosts = BlogPost::query()
                ->public()
                ->forLocale($locale)
                ->where('blog_category_id', $category->id)
                ->with(['translations', 'author', 'category.translations', 'tags.translations'])
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();

            if ($catPosts->isNotEmpty()) {
                $postsByCategory[] = [
                    'category' => $category,
                    'posts' => $catPosts,
                ];
            }
        }

        // Hero featured post (first featured post, or first popular post)
        $featuredPost = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->where('is_featured', true)
            ->with(['translations', 'author', 'category.translations'])
            ->orderByDesc('published_at')
            ->first();

        return view('blog.index', [
            'locale' => $locale,
            'blogHero' => BlogHeroSetting::getSingleton(),
            'categories' => $categories,
            'popularPosts' => $popularPosts,
            'postsByCategory' => $postsByCategory,
            'featuredPost' => $featuredPost ?? $popularPosts->first(),
            'activeCategorySlug' => null,
        ]);
    }

    public function archive(Request $request, RecordVisitService $visits): View
    {
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $visits->record($request, 'blog_archive', locale: $locale);

        $searchQuery = trim((string) $request->query('q', ''));
        $categories = $this->categoriesForFilter($locale);

        $query = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->with(['translations', 'author', 'category.translations', 'tags.translations']);

        if ($searchQuery !== '') {
            $query->whereHas('translations', function (Builder $q) use ($searchQuery, $locale) {
                $q->where('locale', $locale)
                    ->where(function (Builder $sub) use ($searchQuery) {
                        $sub->where('title', 'like', "%{$searchQuery}%")
                            ->orWhere('excerpt', 'like', "%{$searchQuery}%")
                            ->orWhere('content', 'like', "%{$searchQuery}%");
                    });
            });
        }

        $posts = $query->orderByDesc('published_at')->paginate(12)->withQueryString();

        return view('blog.archive', [
            'locale' => $locale,
            'posts' => $posts,
            'categories' => $categories,
            'activeCategorySlug' => null,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function category(Request $request, RecordVisitService $visits): View
    {
        $slug = (string) $request->route('slug');
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $category = BlogCategory::query()
            ->where('is_active', true)
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->with('translations')
            ->first();

        if (! $category) {
            abort(404);
        }

        $matchedTranslation = $category->translations->firstWhere('slug', $slug);
        if (! $category->translate($locale) && $matchedTranslation) {
            $locale = $matchedTranslation->locale;
            app()->setLocale($locale);
        }

        $visits->record($request, 'blog_category', $category, $locale);

        $categories = $this->categoriesForFilter($locale);

        $posts = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->where('blog_category_id', $category->id)
            ->with(['translations', 'author', 'category.translations', 'tags.translations'])
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('blog.category', [
            'locale' => $locale,
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'activeCategorySlug' => $slug,
        ]);
    }

    public function tag(Request $request, RecordVisitService $visits): View
    {
        $slug = (string) $request->route('slug');
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $tag = BlogTag::query()
            ->where('is_active', true)
            ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
            ->with('translations')
            ->first();

        if (! $tag) {
            abort(404);
        }

        $matchedTranslation = $tag->translations->firstWhere('slug', $slug);
        if (! $tag->translate($locale) && $matchedTranslation) {
            $locale = $matchedTranslation->locale;
            app()->setLocale($locale);
        }

        $visits->record($request, 'blog_tag', $tag, $locale);

        $categories = $this->categoriesForFilter($locale);

        $posts = $tag->blogPosts()
            ->public()
            ->forLocale($locale)
            ->with(['translations', 'author', 'category.translations', 'tags.translations'])
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('blog.tag', [
            'locale' => $locale,
            'tag' => $tag,
            'posts' => $posts,
            'categories' => $categories,
            'activeCategorySlug' => null,
        ]);
    }

    public function show(Request $request, BlogLikeService $likes, RecordVisitService $visits): View
    {
        $slug = (string) $request->route('slug');
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        $post = BlogPost::query()
            ->public()
            ->whereHas('translations', function ($q) use ($slug, $locale) {
                $q->where('slug', $slug);
            })
            ->with([
                'translations',
                'category.translations',
                'tags.translations',
                'author',
                'comments' => fn ($q) => $q
                    ->approved()
                    ->whereNull('parent_id')
                    ->with([
                        'replies' => fn ($r) => $r->approved()->orderBy('created_at'),
                    ])
                    ->orderBy('created_at'),
            ])
            ->first();

        if (! $post) {
            // Check cross-locale fallback
            $post = BlogPost::query()
                ->public()
                ->whereHas('translations', fn ($q) => $q->where('slug', $slug))
                ->first();

            if (! $post) {
                abort(404);
            }
        }

        $matchedTranslation = $post->translations->firstWhere('slug', $slug);
        if (! $post->translate($locale) && $matchedTranslation) {
            $locale = $matchedTranslation->locale;
            app()->setLocale($locale);
        }

        // Record visit (automatically debounced and increments views_count)
        $visits->record($request, 'blog_post', $post, $locale);

        // Related posts in same category
        $relatedPosts = $post->blog_category_id
            ? BlogPost::query()
                ->public()
                ->forLocale($locale)
                ->where('blog_category_id', $post->blog_category_id)
                ->whereKeyNot($post->id)
                ->with(['translations', 'category.translations'])
                ->orderByDesc('published_at')
                ->limit(4)
                ->get()
            : collect();

        // Prev & Next posts
        $prevPost = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->where('published_at', '<', $post->published_at)
            ->with(['translations'])
            ->orderByDesc('published_at')
            ->first();

        $nextPost = BlogPost::query()
            ->public()
            ->forLocale($locale)
            ->where('published_at', '>', $post->published_at)
            ->with(['translations'])
            ->orderBy('published_at')
            ->first();

        return view('blog.show', [
            'locale' => $locale,
            'post' => $post,
            'comments' => $post->comments,
            'hasLiked' => $likes->hasLiked($post, $request),
            'relatedPosts' => $relatedPosts,
            'prevPost' => $prevPost,
            'nextPost' => $nextPost,
        ]);
    }

    /**
     * @return Collection<int, BlogCategory>
     */
    private function categoriesForFilter(string $locale): Collection
    {
        return BlogCategory::query()
            ->where('is_active', true)
            ->forLocale($locale)
            ->whereHas('blogPosts', fn ($query) => $query->public()->forLocale($locale))
            ->orderBy('sort_order')
            ->with('translations')
            ->get();
    }

    private function resolveLocale(Request $request): string
    {
        $routeLocale = $request->route('locale');
        if (is_string($routeLocale) && in_array($routeLocale, Locales::all(), true)) {
            session(['blog_locale' => $routeLocale]);

            return $routeLocale;
        }

        $reqLocale = $request->query('lang') ?? $request->query('locale');
        if (is_string($reqLocale) && in_array($reqLocale, Locales::all(), true)) {
            session(['blog_locale' => $reqLocale]);

            return $reqLocale;
        }

        $sessionLocale = session('blog_locale');
        if (is_string($sessionLocale) && in_array($sessionLocale, Locales::all(), true)) {
            return $sessionLocale;
        }

        return app()->getLocale() ?: Locales::default();
    }
}
