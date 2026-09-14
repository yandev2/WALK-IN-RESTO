<?php

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
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /founder\nDisallow: /order\nDisallow: /export-files\n\nSitemap: {$sitemapUrl}\n";

    return response($content, 200, ['Content-Type' => 'text/plain']);
});

Route::get('/daftar', RegisterRestaurant::class)
    ->middleware(['guest', 'throttle:10,1'])
    ->name('register.restaurant');

Route::get('/receipts/{order:public_id}/download', OrderReceiptDownloadController::class)
    ->middleware(['signed', 'throttle:30,1'])
    ->name('receipts.download');

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
        ->middleware(EnsureRestaurantOperations::class)
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

$landingSlugPattern = '^(?!admin$|livewire$|storage$|up$|filament$|order$|api$|export-files$|founder$|daftar$|receipts$|tentang$|syarat-dan-ketentuan$|sitemap\.xml$|robots\.txt$)[A-Za-z0-9_-]+$';

Route::get('/{restaurant:slug}/menu', RestaurantMenuCatalog::class)
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.menu');

Route::get('/{restaurant:slug}', [RestaurantLandingController::class, 'show'])
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.show');
