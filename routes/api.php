<?php

use App\Http\Controllers\Api\V1\Guest\ReviewController;
use App\Http\Controllers\Api\V1\Guest\CartController;
use App\Http\Controllers\Api\V1\Guest\CheckoutController;
use App\Http\Controllers\Api\V1\Guest\MenuController;
use App\Http\Controllers\Api\V1\Guest\OrderController;
use App\Http\Controllers\Api\V1\Guest\SessionController;
use App\Http\Controllers\Api\V1\Guest\TableController;
use App\Http\Controllers\Api\V1\Guest\VisitController;
use App\Http\Controllers\Api\V1\Public\RestaurantController;
use App\Http\Controllers\Api\V1\Public\RestaurantMenuController;
use App\Http\Controllers\Api\V1\Public\RestaurantReviewController;
use App\Http\Middleware\EnsureRestaurantOperations;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::middleware('throttle:60,1')->group(function (): void {
        Route::get('/restaurants', [RestaurantController::class, 'index']);
        Route::get('/restaurants/{restaurant:slug}', [RestaurantController::class, 'show']);
        Route::get('/restaurants/{restaurant:slug}/menu', [RestaurantMenuController::class, 'index']);
        Route::get('/restaurants/{restaurant:slug}/reviews', [RestaurantReviewController::class, 'index']);

        Route::middleware('identify.api.guest')->group(function (): void {
            Route::post('/guest/session', [SessionController::class, 'store']);
            Route::get('/guest/tables/{token}', [TableController::class, 'show'])
                ->where('token', '[A-Za-z0-9_-]+');

            Route::middleware(['require.api.guest', 'throttle:10,1', EnsureRestaurantOperations::class])->group(function (): void {
                Route::post('/guest/tables/{token}/claim', [TableController::class, 'claim'])
                    ->where('token', '[A-Za-z0-9_-]+');
                Route::post('/guest/tables/{token}/join', [TableController::class, 'join'])
                    ->where('token', '[A-Za-z0-9_-]+');
            });

            Route::middleware(['guest.api.visit', EnsureRestaurantOperations::class])->group(function (): void {
                Route::get('/guest/visit', [VisitController::class, 'show']);
                Route::get('/guest/menu', [MenuController::class, 'index']);
                Route::get('/guest/cart', [CartController::class, 'show']);
                Route::post('/guest/cart/items', [CartController::class, 'store']);
                Route::patch('/guest/cart/items/{cartItem}', [CartController::class, 'update']);
                Route::delete('/guest/cart/items/{cartItem}', [CartController::class, 'destroy']);
                Route::post('/guest/checkout', [CheckoutController::class, 'store'])
                    ->middleware('throttle:10,1');
                Route::get('/guest/orders', [OrderController::class, 'index']);
                Route::get('/guest/orders/{order:public_id}', [OrderController::class, 'show']);
                Route::post('/guest/orders/{order:public_id}/proof', [OrderController::class, 'storeProof']);
                Route::get('/guest/review', [ReviewController::class, 'show']);
                Route::post('/guest/review', [ReviewController::class, 'store'])
                    ->middleware('throttle:10,1');
            });
        });
    });
});
