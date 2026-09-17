<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Restaurant;
use App\Support\MediaUrl;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticPages = [
            [
                'url' => route('home'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'url' => route('page.about'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'url' => route('page.terms'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'url' => route('register.restaurant'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];

        // Blog Main & Archive Pages
        $latestPost = BlogPost::query()->public()->latest('published_at')->first();
        $blogLastMod = ($latestPost?->updated_at ?? $latestPost?->published_at ?? now())->toAtomString();

        $blogStaticPages = [
            [
                'url' => route('blog.index', ['locale' => 'id']),
                'lastmod' => $blogLastMod,
                'changefreq' => 'daily',
                'priority' => '0.9',
                'alternates' => [
                    ['hreflang' => 'id', 'href' => route('blog.index', ['locale' => 'id'])],
                    ['hreflang' => 'en', 'href' => route('blog.index', ['locale' => 'en'])],
                    ['hreflang' => 'x-default', 'href' => route('blog.index', ['locale' => 'id'])],
                ],
            ],
            [
                'url' => route('blog.archive', ['locale' => 'id']),
                'lastmod' => $blogLastMod,
                'changefreq' => 'daily',
                'priority' => '0.8',
                'alternates' => [
                    ['hreflang' => 'id', 'href' => route('blog.archive', ['locale' => 'id'])],
                    ['hreflang' => 'en', 'href' => route('blog.archive', ['locale' => 'en'])],
                    ['hreflang' => 'x-default', 'href' => route('blog.archive', ['locale' => 'id'])],
                ],
            ],
        ];

        // Blog Categories
        $blogCategoryPages = [];
        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('sort_order')
            ->get();

        foreach ($categories as $category) {
            $catLastMod = ($category->updated_at ?? now())->toAtomString();
            $alternates = [];

            foreach (['id', 'en'] as $loc) {
                $t = $category->translate($loc);
                if ($t && filled($t->slug)) {
                    $alternates[] = [
                        'hreflang' => $loc,
                        'href' => route('blog.category', ['locale' => $loc, 'slug' => $t->slug]),
                    ];
                }
            }

            if (! empty($alternates)) {
                $alternates[] = [
                    'hreflang' => 'x-default',
                    'href' => $alternates[0]['href'],
                ];
            }

            foreach ($category->translations as $t) {
                if (filled($t->slug)) {
                    $blogCategoryPages[] = [
                        'url' => route('blog.category', ['locale' => $t->locale, 'slug' => $t->slug]),
                        'lastmod' => $catLastMod,
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                        'alternates' => $alternates,
                    ];
                }
            }
        }

        // Blog Tags
        $blogTagPages = [];
        $tags = BlogTag::query()
            ->where('is_active', true)
            ->whereHas('blogPosts', fn ($q) => $q->public())
            ->with('translations')
            ->get();

        foreach ($tags as $tag) {
            $tagLastMod = ($tag->updated_at ?? now())->toAtomString();
            $alternates = [];

            foreach (['id', 'en'] as $loc) {
                $t = $tag->translate($loc);
                if ($t && filled($t->slug)) {
                    $alternates[] = [
                        'hreflang' => $loc,
                        'href' => route('blog.tag', ['locale' => $loc, 'slug' => $t->slug]),
                    ];
                }
            }

            if (! empty($alternates)) {
                $alternates[] = [
                    'hreflang' => 'x-default',
                    'href' => $alternates[0]['href'],
                ];
            }

            foreach ($tag->translations as $t) {
                if (filled($t->slug)) {
                    $blogTagPages[] = [
                        'url' => route('blog.tag', ['locale' => $t->locale, 'slug' => $t->slug]),
                        'lastmod' => $tagLastMod,
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                        'alternates' => $alternates,
                    ];
                }
            }
        }

        // Blog Articles (Posts)
        $blogPostPages = [];
        $posts = BlogPost::query()
            ->public()
            ->with(['translations', 'category.translations'])
            ->orderByDesc('published_at')
            ->get();

        foreach ($posts as $post) {
            $postLastMod = ($post->updated_at ?? $post->published_at ?? now())->toAtomString();
            $alternates = [];

            foreach (['id', 'en'] as $loc) {
                $t = $post->translate($loc);
                if ($t && filled($t->slug)) {
                    $alternates[] = [
                        'hreflang' => $loc,
                        'href' => route('blog.show', ['locale' => $loc, 'slug' => $t->slug]),
                    ];
                }
            }

            if (! empty($alternates)) {
                $alternates[] = [
                    'hreflang' => 'x-default',
                    'href' => $alternates[0]['href'],
                ];
            }

            $image = null;
            if (filled($post->featured_image)) {
                $imageUrl = MediaUrl::public($post->featured_image);
                if ($imageUrl) {
                    $image = [
                        'url' => $imageUrl,
                        'title' => $post->translate('id')?->title ?? $post->translations->first()?->title ?? 'Artikel Blog',
                        'caption' => $post->translate('id')?->featured_image_alt ?? $post->translate('id')?->excerpt,
                    ];
                }
            }

            foreach ($post->translations as $t) {
                if (filled($t->slug) && filled($t->title)) {
                    $itemImage = $image;
                    if ($itemImage && filled($t->title)) {
                        $itemImage['title'] = $t->title;
                        if (filled($t->featured_image_alt)) {
                            $itemImage['caption'] = $t->featured_image_alt;
                        } elseif (filled($t->excerpt)) {
                            $itemImage['caption'] = $t->excerpt;
                        }
                    }

                    $blogPostPages[] = [
                        'url' => route('blog.show', ['locale' => $t->locale, 'slug' => $t->slug]),
                        'lastmod' => $postLastMod,
                        'changefreq' => 'weekly',
                        'priority' => '0.9',
                        'alternates' => $alternates,
                        'image' => $itemImage,
                    ];
                }
            }
        }

        // Restaurant directory pages
        $restaurants = Restaurant::query()
            ->listedInDirectory()
            ->where('landing_enabled', true)
            ->select(['id', 'name', 'slug', 'logo_path', 'updated_at'])
            ->orderBy('id')
            ->get();

        $restaurantPages = [];
        foreach ($restaurants as $restaurant) {
            $lastmod = ($restaurant->updated_at ?? now())->toAtomString();

            $image = null;
            if (filled($restaurant->logo_path)) {
                $imageUrl = MediaUrl::public($restaurant->logo_path);
                if ($imageUrl) {
                    $image = [
                        'url' => $imageUrl,
                        'title' => $restaurant->name,
                    ];
                }
            }

            $restaurantPages[] = array_filter([
                'url' => route('landing.show', $restaurant),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.9',
                'image' => $image,
            ]);

            $restaurantPages[] = [
                'url' => route('landing.menu', $restaurant),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $urls = array_merge(
            $staticPages,
            $blogStaticPages,
            $blogCategoryPages,
            $blogTagPages,
            $blogPostPages,
            $restaurantPages
        );

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
