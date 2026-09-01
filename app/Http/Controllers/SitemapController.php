<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $restaurants = Restaurant::query()
            ->listedInDirectory()
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->get();

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

        $restaurantPages = [];
        foreach ($restaurants as $restaurant) {
            $lastmod = ($restaurant->updated_at ?? now())->toAtomString();

            $restaurantPages[] = [
                'url' => route('landing.show', $restaurant),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];

            $restaurantPages[] = [
                'url' => route('landing.menu', $restaurant),
                'lastmod' => $lastmod,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        $urls = array_merge($staticPages, $restaurantPages);

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
