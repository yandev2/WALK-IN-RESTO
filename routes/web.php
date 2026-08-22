<?php

use App\Http\Controllers\ExportFileDownloadController;
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

Route::get('/daftar', RegisterRestaurant::class)
    ->middleware(['guest', 'throttle:10,1'])
    ->name('register.restaurant');

Route::middleware('auth')->group(function (): void {
    Route::get('/export-files/{exportFile}/download', ExportFileDownloadController::class)
        ->name('export-files.download');
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

$landingSlugPattern = '^(?!admin$|livewire$|storage$|up$|filament$|order$|api$|export-files$|founder$|daftar$)[A-Za-z0-9_-]+$';

Route::get('/{restaurant:slug}/menu', RestaurantMenuCatalog::class)
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.menu');

Route::get('/{restaurant:slug}', [RestaurantLandingController::class, 'show'])
    ->where('restaurant', $landingSlugPattern)
    ->name('landing.show');
