<?php

namespace App\Services;

use App\Enums\BlogCommentStatus;
use App\Enums\BlogPostStatus;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\VisitLog;
use App\Support\FilamentTranslatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BlogAnalyticsService
{
    /** @var array<int, string> */
    public const BLOG_PAGE_KEYS = ['blog_index', 'blog_archive', 'blog_category', 'blog_tag', 'blog_post'];

    /**
     * Build base query for visit logs, with optional author scoping.
     * When $authorId is provided, only visits for articles authored by that user are queried.
     */
    public function baseQuery(?Carbon $from = null, ?int $authorId = null): Builder
    {
        $query = VisitLog::query();

        if ($authorId !== null) {
            $query->where('page_key', 'blog_post')
                ->where(function (Builder $q) {
                    $q->where('visitable_type', BlogPost::class)
                        ->orWhere('visitable_type', (new BlogPost)->getMorphClass());
                })
                ->whereIn('visitable_id', BlogPost::query()->where('author_id', $authorId)->select('id'));
        } else {
            $query->whereIn('page_key', self::BLOG_PAGE_KEYS);
        }

        if ($from !== null) {
            $query->where('created_at', '>=', $from);
        }

        return $query;
    }

    public function totalViews(?Carbon $from = null, ?int $authorId = null): int
    {
        return $this->baseQuery($from, $authorId)->count();
    }

    public function viewsToday(?int $authorId = null): int
    {
        return $this->baseQuery(now()->startOfDay(), $authorId)->count();
    }

    public function viewsLastDays(int $days, ?int $authorId = null): int
    {
        return $this->baseQuery(now()->subDays($days)->startOfDay(), $authorId)->count();
    }

    public function uniqueVisitors(int $days, ?int $authorId = null): int
    {
        return (int) $this->baseQuery(now()->subDays($days)->startOfDay(), $authorId)
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');
    }

    /**
     * @return array{current: int, previous: int, percentage: float, is_positive: bool}
     */
    public function viewsGrowthRate(int $days, ?int $authorId = null): array
    {
        $current = $this->baseQuery(now()->subDays($days)->startOfDay(), $authorId)->count();

        $prevQuery = VisitLog::query();
        if ($authorId !== null) {
            $prevQuery->where('page_key', 'blog_post')
                ->where(function (Builder $q) {
                    $q->where('visitable_type', BlogPost::class)
                        ->orWhere('visitable_type', (new BlogPost)->getMorphClass());
                })
                ->whereIn('visitable_id', BlogPost::query()->where('author_id', $authorId)->select('id'));
        } else {
            $prevQuery->whereIn('page_key', self::BLOG_PAGE_KEYS);
        }

        $previous = $prevQuery
            ->whereBetween('created_at', [
                now()->subDays($days * 2)->startOfDay(),
                now()->subDays($days)->startOfDay(),
            ])
            ->count();

        $percentage = 0.0;
        if ($previous > 0) {
            $percentage = round((($current - $previous) / $previous) * 100, 1);
        } elseif ($current > 0) {
            $percentage = 100.0;
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percentage' => abs($percentage),
            'is_positive' => $current >= $previous,
        ];
    }

    /**
     * @return array{current: int, previous: int, percentage: float, is_positive: bool}
     */
    public function visitorsGrowthRate(int $days, ?int $authorId = null): array
    {
        $current = $this->uniqueVisitors($days, $authorId);

        $prevQuery = VisitLog::query();
        if ($authorId !== null) {
            $prevQuery->where('page_key', 'blog_post')
                ->where(function (Builder $q) {
                    $q->where('visitable_type', BlogPost::class)
                        ->orWhere('visitable_type', (new BlogPost)->getMorphClass());
                })
                ->whereIn('visitable_id', BlogPost::query()->where('author_id', $authorId)->select('id'));
        } else {
            $prevQuery->whereIn('page_key', self::BLOG_PAGE_KEYS);
        }

        $previous = (int) $prevQuery
            ->whereBetween('created_at', [
                now()->subDays($days * 2)->startOfDay(),
                now()->subDays($days)->startOfDay(),
            ])
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');

        $percentage = 0.0;
        if ($previous > 0) {
            $percentage = round((($current - $previous) / $previous) * 100, 1);
        } elseif ($current > 0) {
            $percentage = 100.0;
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percentage' => abs($percentage),
            'is_positive' => $current >= $previous,
        ];
    }

    /**
     * @return array{
     *     total_published: int,
     *     draft_count: int,
     *     scheduled_count: int,
     *     published_this_month: int,
     *     published_last_month: int,
     *     monthly_target: int,
     *     monthly_target_percent: int,
     *     total_likes: int,
     *     total_comments: int,
     *     avg_reading_time: float
     * }
     */
    public function contentProgress(?int $authorId = null): array
    {
        $postQuery = BlogPost::query();
        if ($authorId !== null) {
            $postQuery->where('author_id', $authorId);
        }

        $published = (clone $postQuery)->where('status', BlogPostStatus::Published)->count();
        $draft = (clone $postQuery)->where('status', BlogPostStatus::Draft)->count();
        $scheduled = (clone $postQuery)->where('status', BlogPostStatus::Scheduled)->count();

        $thisMonth = (clone $postQuery)
            ->where('status', BlogPostStatus::Published)
            ->where('published_at', '>=', now()->startOfMonth())
            ->count();

        $lastMonth = (clone $postQuery)
            ->where('status', BlogPostStatus::Published)
            ->whereBetween('published_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth(),
            ])
            ->count();

        $monthlyTarget = $authorId !== null ? 2 : 6;
        $targetPercent = (int) min(100, round(($thisMonth / max(1, $monthlyTarget)) * 100));

        $totalLikes = (int) (clone $postQuery)->sum('likes_count');

        $commentQuery = BlogComment::query()->where('status', BlogCommentStatus::Approved);
        if ($authorId !== null) {
            $commentQuery->whereHas('blogPost', fn ($q) => $q->where('author_id', $authorId));
        }
        $totalComments = (int) $commentQuery->count();

        $avgReadingTime = round((float) ((clone $postQuery)->where('status', BlogPostStatus::Published)->avg('reading_time_minutes') ?? 0), 1);

        return [
            'total_published' => $published,
            'draft_count' => $draft,
            'scheduled_count' => $scheduled,
            'published_this_month' => $thisMonth,
            'published_last_month' => $lastMonth,
            'monthly_target' => $monthlyTarget,
            'monthly_target_percent' => $targetPercent,
            'total_likes' => $totalLikes,
            'total_comments' => $totalComments,
            'avg_reading_time' => $avgReadingTime,
        ];
    }

    /**
     * @return array{labels: array<int, string>, views: array<int, int>, visitors: array<int, int>, articles: array<int, int>}
     */
    public function viewsByDayDetailed(int $days, ?int $authorId = null): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();
        $labels = [];
        $views = [];
        $visitors = [];
        $articles = [];

        $dailyTotalQuery = $this->baseQuery($startDate, $authorId);
        $dailyTotal = (clone $dailyTotalQuery)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyVisitors = (clone $dailyTotalQuery)
            ->whereNotNull('visitor_hash')
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT visitor_hash) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $articleQuery = VisitLog::query()
            ->where('page_key', 'blog_post')
            ->where('created_at', '>=', $startDate);

        if ($authorId !== null) {
            $articleQuery->whereIn('visitable_id', BlogPost::query()->where('author_id', $authorId)->select('id'));
        }

        $dailyArticles = $articleQuery
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $views[] = (int) ($dailyTotal[$key] ?? 0);
            $visitors[] = (int) ($dailyVisitors[$key] ?? 0);
            $articles[] = (int) ($dailyArticles[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'views' => $views,
            'visitors' => $visitors,
            'articles' => $articles,
        ];
    }

    /**
     * @return Collection<int, array{post_id: int, title: string, period_views: int, views_count: int, likes_count: int}>
     */
    public function topPosts(int $limit = 10, int $days = 30, ?int $authorId = null): Collection
    {
        $from = now()->subDays($days)->startOfDay();

        $postQuery = BlogPost::query();
        if ($authorId !== null) {
            $postQuery->where('author_id', $authorId);
        }

        $rows = VisitLog::query()
            ->where('page_key', 'blog_post')
            ->where('created_at', '>=', $from)
            ->whereNotNull('visitable_id')
            ->whereIn('visitable_id', (clone $postQuery)->select('id'))
            ->select('visitable_id', DB::raw('COUNT(*) as period_views'))
            ->groupBy('visitable_id')
            ->orderByDesc('period_views')
            ->limit($limit)
            ->get();

        if ($rows->isEmpty()) {
            // Fallback: list top posts by cumulative views_count
            $fallbackPosts = (clone $postQuery)
                ->with('translations')
                ->orderByDesc('views_count')
                ->limit($limit)
                ->get();

            return $fallbackPosts->map(function ($post) {
                $title = FilamentTranslatable::label($post, 'title');

                return [
                    'post_id' => (int) $post->id,
                    'title' => filled($title) ? $title : ($post->title ?? '—'),
                    'period_views' => 0,
                    'views_count' => (int) ($post->views_count ?? 0),
                    'likes_count' => (int) ($post->likes_count ?? 0),
                ];
            });
        }

        $posts = BlogPost::query()
            ->with('translations')
            ->whereIn('id', $rows->pluck('visitable_id'))
            ->get()
            ->keyBy('id');

        return $rows->map(function ($row) use ($posts) {
            $post = $posts->get($row->visitable_id);
            if (! $post) {
                return null;
            }

            $title = FilamentTranslatable::label($post, 'title');

            return [
                'post_id' => (int) $row->visitable_id,
                'title' => filled($title) ? $title : ($post->title ?? '—'),
                'period_views' => (int) $row->period_views,
                'views_count' => (int) ($post->views_count ?? 0),
                'likes_count' => (int) ($post->likes_count ?? 0),
            ];
        })->filter()->values();
    }

    /**
     * @return Collection<int, array{name: string, views: int, percent: float}>
     */
    public function topCategories(int $limit = 5, int $days = 30, ?int $authorId = null): Collection
    {
        $from = now()->subDays($days)->startOfDay();

        $postQuery = BlogPost::query();
        if ($authorId !== null) {
            $postQuery->where('author_id', $authorId);
        }

        $postViews = VisitLog::query()
            ->where('page_key', 'blog_post')
            ->where('created_at', '>=', $from)
            ->whereNotNull('visitable_id')
            ->whereIn('visitable_id', (clone $postQuery)->select('id'))
            ->select('visitable_id', DB::raw('COUNT(*) as views'))
            ->groupBy('visitable_id')
            ->pluck('views', 'visitable_id');

        if ($postViews->isEmpty()) {
            return collect();
        }

        $posts = BlogPost::query()
            ->with(['category.translations'])
            ->whereIn('id', $postViews->keys())
            ->get();

        $categoryViews = [];
        $totalArticleViews = (int) $postViews->sum();

        foreach ($posts as $post) {
            $category = $post->category;
            $catName = $category ? FilamentTranslatable::label($category, 'name') : 'Umum';
            $views = (int) ($postViews[$post->id] ?? 0);
            $categoryViews[$catName] = ($categoryViews[$catName] ?? 0) + $views;
        }

        arsort($categoryViews);

        return collect($categoryViews)->take($limit)->map(function ($views, $name) use ($totalArticleViews) {
            return [
                'name' => $name,
                'views' => $views,
                'percent' => $totalArticleViews > 0 ? round(($views / $totalArticleViews) * 100, 1) : 0.0,
            ];
        })->values();
    }

    /**
     * @return array{desktop: int, mobile: int, tablet: int, total: int, desktop_percent: float, mobile_percent: float, tablet_percent: float}
     */
    public function audienceDevices(int $days = 30, ?int $authorId = null): array
    {
        $from = now()->subDays($days)->startOfDay();

        $logs = $this->baseQuery($from, $authorId)
            ->whereNotNull('user_agent')
            ->pluck('user_agent');

        $desktop = 0;
        $mobile = 0;
        $tablet = 0;

        foreach ($logs as $ua) {
            $uaLower = strtolower($ua);
            if (str_contains($uaLower, 'ipad') || str_contains($uaLower, 'tablet')) {
                $tablet++;
            } elseif (preg_match('/(mobile|android|iphone|ipod|blackberry|opera mini|iemobile)/i', $uaLower)) {
                $mobile++;
            } else {
                $desktop++;
            }
        }

        $total = max(1, $desktop + $mobile + $tablet);

        return [
            'desktop' => $desktop,
            'mobile' => $mobile,
            'tablet' => $tablet,
            'total' => $logs->count(),
            'desktop_percent' => round(($desktop / $total) * 100, 1),
            'mobile_percent' => round(($mobile / $total) * 100, 1),
            'tablet_percent' => round(($tablet / $total) * 100, 1),
        ];
    }

    /**
     * @return Collection<int, array{locale: string, views: int}>
     */
    public function viewsByLocale(int $days = 30, ?int $authorId = null): Collection
    {
        $from = now()->subDays($days)->startOfDay();

        return $this->baseQuery($from, $authorId)
            ->whereNotNull('locale')
            ->select('locale', DB::raw('COUNT(*) as views'))
            ->groupBy('locale')
            ->orderByDesc('views')
            ->get()
            ->map(fn ($row) => [
                'locale' => strtoupper((string) $row->locale),
                'views' => (int) $row->views,
            ]);
    }

    /**
     * @return Collection<int, array{referer: string, views: int}>
     */
    public function topReferrers(int $limit = 10, int $days = 30, ?int $authorId = null): Collection
    {
        $from = now()->subDays($days)->startOfDay();

        return $this->baseQuery($from, $authorId)
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->select('referer', DB::raw('COUNT(*) as views'))
            ->groupBy('referer')
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'referer' => (string) $row->referer,
                'views' => (int) $row->views,
            ]);
    }

    /**
     * @return array{label: string, url: string}
     */
    public function formatReferrer(string $referer): array
    {
        $referer = trim($referer);

        if ($referer === '') {
            return ['label' => 'Direct / Langsung', 'url' => $referer];
        }

        $parts = parse_url($referer);

        if (! is_array($parts) || empty($parts['host'])) {
            return ['label' => $referer, 'url' => $referer];
        }

        $host = strtolower($parts['host']);
        $siteUrl = config('app.url', 'http://walk-in-resto.test');
        $siteHost = strtolower((string) parse_url($siteUrl, PHP_URL_HOST) ?? '');

        if ($siteHost !== '' && ($host === $siteHost || str_ends_with($host, '.'.$siteHost))) {
            $path = $parts['path'] ?? '/';
            $query = isset($parts['query']) ? '?'.$parts['query'] : '';

            return [
                'label' => 'Internal · '.$path.$query,
                'url' => $referer,
            ];
        }

        $domain = preg_replace('/^www\./', '', $host) ?? $host;

        $known = [
            'google.com' => 'Google Search',
            'google.co.id' => 'Google Indonesia',
            't.co' => 'X (Twitter)',
            'twitter.com' => 'X (Twitter)',
            'x.com' => 'X (Twitter)',
            'facebook.com' => 'Facebook',
            'instagram.com' => 'Instagram',
            'linkedin.com' => 'LinkedIn',
            'youtube.com' => 'YouTube',
            'whatsapp.com' => 'WhatsApp',
            'tiktok.com' => 'TikTok',
            'bing.com' => 'Bing',
        ];

        foreach ($known as $pattern => $name) {
            if ($domain === $pattern || str_ends_with($domain, '.'.$pattern)) {
                return ['label' => $name, 'url' => $referer];
            }
        }

        return ['label' => $domain, 'url' => $referer];
    }
}
