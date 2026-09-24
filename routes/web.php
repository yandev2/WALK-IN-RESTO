<?php

use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogLikeController;
use App\Http\Controllers\ExportFileDownloadController;
use App\Http\Controllers\OrderReceiptDownloadController;
use App\Http\Controllers\OrderReceiptPrintController;
use App\Http\Controllers\PlatformPageController;
use App\Http\Controllers\RestaurantLandingController;
use App\Http\Middleware\EnsureRestaurantOperations;
use App\Livewire\Auth\RegisterRestaurant;
use App\Livewire\Guest\GuestCart;
use App\Livewire\Guest\GuestCheckout;
use App\Livewire\Guest\GuestMenu;
use App\Livewire\Guest\GuestPay;
use App\Livewire\Guest\GuestReview;
use App\Livewire\Guest\GuestStatus;
use App\Livewire\Guest\ScanTable;
use App\Livewire\Landing\RestaurantDirectory;
use App\Livewire\Landing\RestaurantMenuCatalog;
use Illuminate\Support\Facades\Route;

Route::get('/', RestaurantDirectory::class)->name('home');

Route::get('/tentang', [PlatformPageController::class, 'about'])->name('page.about');
Route::get('/syarat-dan-ketentuan', [PlatformPageController::class, 'terms'])->name('page.terms');
Route::get('/sitemap.xml', \App\Http\Controllers\SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemapUrl = route('sitemap');
    $content = "User-agent: *\nAllow: /\nAllow: /id/blog\nAllow: /blog\nDisallow: /en/\nDisallow: /en/blog\nDisallow: /admin\nDisallow: /founder\nDisallow: /blogger\nDisallow: /order\nDisallow: /export-files\nDisallow: /*?*search=\nDisallow: /*?*kategori=\nDisallow: /*?*sort=\n\nSitemap: {$sitemapUrl}\n";

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
});
Route::get('/ads.txt', function () {
    $content = \App\Models\AdSetting::current()->ads_txt_content ?? '';

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('ads.txt');

Route::get('/daftar', RegisterRestaurant::class)
    ->middleware(['guest', 'throttle:10,1'])
    ->name('register.restaurant');

Route::get('/receipts/{order:public_id}/download', OrderReceiptDownloadController::class)
    ->middleware(['signed', 'throttle:30,1'])
    ->name('receipts.download');

Route::get('/orders/{order:public_id}/payments/{payment:public_id}/proof', \App\Http\Controllers\PaymentProofViewController::class)
    ->middleware(['identify.guest', 'throttle:60,1'])
    ->name('payments.proof.show');

Route::middleware('auth')->group(function (): void {
    Route::get('/export-files/{exportFile}/download', ExportFileDownloadController::class)
        ->name('export-files.download');

    Route::get('/receipts/{order:public_id}/print', OrderReceiptPrintController::class)
        ->middleware('throttle:30,1')
        ->name('receipts.print');

    Route::get('/receipts/{order:public_id}/print/pdf', [OrderReceiptPrintController::class, 'pdf'])
        ->middleware('throttle:30,1')
        ->name('receipts.print.pdf');

    Route::get('/shifts/{shift:public_id}/print', \App\Http\Controllers\CashierShiftPrintController::class)
        ->middleware('throttle:30,1')
        ->name('shifts.print');
});

Route::middleware('identify.guest')->prefix('order')->group(function (): void {
    Route::view('/need-scan', 'guest.need-scan')->name('guest.need-scan');
    Route::get('/t/{token}', ScanTable::class)
        ->middleware(['throttle:30,1', EnsureRestaurantOperations::class])
        ->where('token', '[A-Za-z0-9_-]+')
        ->name('guest.scan');

    Route::middleware(['guest.visit', EnsureRestaurantOperations::class])->group(function (): void {
        Route::get('/menu', GuestMenu::class)->name('guest.menu');
        Route::get('/cart', GuestCart::class)->name('guest.cart');
        Route::get('/checkout', GuestCheckout::class)->name('guest.checkout');
        Route::get('/pay/{order:public_id}', GuestPay::class)->name('guest.pay');
        Route::get('/status', GuestStatus::class)->name('guest.status');
        Route::get('/review', GuestReview::class)->name('guest.review');
    });
});

// Localized blog routes: /{locale}/blog/... (e.g. /id/blog, /en/blog)
Route::prefix('{locale}/blog')
    ->where(['locale' => 'id|en'])
    ->middleware([\App\Http\Middleware\SetBlogLocale::class])
    ->group(function (): void {
        Route::get('/', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/articles', [BlogController::class, 'archive'])->name('blog.archive');
        Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
        Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
        Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
        Route::post('/{slug}/comments', [BlogCommentController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('blog.comments.store');
        Route::post('/{slug}/like', [BlogLikeController::class, 'toggle'])
            ->middleware('throttle:30,1')
            ->name('blog.likes.toggle');
    });

// Fallback & SEO 301 Permanent Redirects for legacy /blog paths
Route::prefix('blog')->group(function (): void {
    $redirectWithQuery = function (\Illuminate\Http\Request $request, string $pathTemplate, ?string $slug = null) {
        $locale = $request->query('lang') ?? $request->query('locale') ?? 'id';
        if ($locale !== 'en') {
            $locale = 'id';
        }

        $targetPath = str_replace(['{locale}', '{slug}'], [$locale, $slug ?? ''], $pathTemplate);
        $extraParams = collect($request->query())->except(['lang', 'locale'])->all();
        $queryString = ! empty($extraParams) ? '?' . http_build_query($extraParams) : '';

        return redirect()->to($targetPath . $queryString, 301);
    };

    Route::get('/', fn (\Illuminate\Http\Request $request) => $redirectWithQuery($request, '/{locale}/blog'));
    Route::get('/articles', fn (\Illuminate\Http\Request $request) => $redirectWithQuery($request, '/{locale}/blog/articles'));
    Route::get('/category/{slug}', fn (\Illuminate\Http\Request $request, string $slug) => $redirectWithQuery($request, '/{locale}/blog/category/{slug}', $slug));
    Route::get('/tag/{slug}', fn (\Illuminate\Http\Request $request, string $slug) => $redirectWithQuery($request, '/{locale}/blog/tag/{slug}', $slug));
    Route::get('/{slug}', fn (\Illuminate\Http\Request $request, string $slug) => $redirectWithQuery($request, '/{locale}/blog/{slug}', $slug));
    Route::post('/{slug}/comments', [\App\Http\Controllers\BlogCommentController::class, 'store'])
        ->middleware('throttle:5,1');
    Route::post('/{slug}/like', [\App\Http\Controllers\BlogLikeController::class, 'toggle'])
        ->middleware('throttle:30,1');
});

$landingSlugPattern = '^(?!admin$|blogger$|blog$|id$|en$|livewire$|storage$|up$|filament$|order$|api$|export-files$|founder$|daftar$|receipts$|tentang$|syarat-dan-ketentuan$|sitemap\.xml$|robots\.txt$)[A-Za-z0-9_-]+$';

Route::get('/{restaurant:slug}/menu', RestaurantMenuCatalog::class)
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.menu');

Route::get('/{restaurant:slug}', [RestaurantLandingController::class, 'show'])
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.show');
