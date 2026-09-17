<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetBlogLocale
{
    /**
     * Handle an incoming request for the localized blog.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! is_string($locale) || ! in_array($locale, Locales::all(), true)) {
            $locale = session('blog_locale') ?? Locales::default();
        }

        app()->setLocale($locale);
        session(['blog_locale' => $locale]);

        // Automatically supply current locale to all route('blog.*') calls
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
