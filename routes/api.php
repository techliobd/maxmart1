<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CompareController;

/*
|--------------------------------------------------------------------------
| API Routes - MaxMart E-Commerce Platform
|--------------------------------------------------------------------------
|
| AJAX endpoints for dynamic functionality without page reloads.
| These routes are used for cart operations, product variations, 
| search suggestions, and other real-time features.
|
*/

Route::prefix('api')->name('api.')->group(function () {

    // Cart AJAX Operations (available for guests and authenticated users)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'getCart'])->name('get');
        Route::post('/add/{product}', [CartController::class, 'ajaxAdd'])->name('add');
        Route::patch('/update/{item}', [CartController::class, 'ajaxUpdate'])->name('update');
        Route::delete('/remove/{item}', [CartController::class, 'ajaxRemove'])->name('remove');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::get('/total', [CartController::class, 'total'])->name('total');
        Route::post('/apply-coupon', [CartController::class, 'ajaxApplyCoupon'])->name('coupon.apply');
        Route::post('/remove-coupon', [CartController::class, 'ajaxRemoveCoupon'])->name('coupon.remove');
        Route::get('/shipping-calculate', [CartController::class, 'calculateShipping'])->name('shipping.calculate');
    });

    // Product Variation AJAX
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/{product}/variation-price', [ProductController::class, 'getVariationPrice'])->name('variation.price');
        Route::get('/{product}/variation-stock', [ProductController::class, 'getVariationStock'])->name('variation.stock');
        Route::get('/{product}/variation-image', [ProductController::class, 'getVariationImage'])->name('variation.image');
        Route::get('/{product}/variations', [ProductController::class, 'getVariations'])->name('variations.list');
    });

    // Search Suggestions (Typeahead/Autocomplete)
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/suggestions', [SearchController::class, 'suggestions'])->name('suggestions');
        Route::get('/products', [SearchController::class, 'searchProducts'])->name('products');
        Route::get('/categories', [SearchController::class, 'searchCategories'])->name('categories');
        Route::get('/brands', [SearchController::class, 'searchBrands'])->name('brands');
    });

    // Wishlist AJAX (requires auth)
    Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/count', [WishlistController::class, 'count'])->name('count');
        Route::post('/add/{product}', [WishlistController::class, 'ajaxAdd'])->name('add');
        Route::delete('/remove/{product}', [WishlistController::class, 'ajaxRemove'])->name('remove');
    });

    // Compare AJAX
    Route::prefix('compare')->name('compare.')->group(function () {
        Route::get('/', [CompareController::class, 'getData'])->name('data');
        Route::post('/toggle/{product}', [CompareController::class, 'toggle'])->name('toggle');
        Route::get('/count', [CompareController::class, 'count'])->name('count');
        Route::post('/add/{product}', [CompareController::class, 'ajaxAdd'])->name('add');
        Route::delete('/remove/{product}', [CompareController::class, 'ajaxRemove'])->name('remove');
        Route::post('/clear', [CompareController::class, 'ajaxClear'])->name('clear');
    });

    // Quick View Product
    Route::get('/quick-view/{product}', [ProductController::class, 'quickView'])->name('quick-view');

    // Newsletter Subscription
    Route::post('/newsletter/subscribe', [SearchController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

    // Contact Form Submission
    Route::post('/contact', [SearchController::class, 'submitContact'])->name('contact.submit');

    // Currency Switcher
    Route::post('/currency/set/{code}', [SearchController::class, 'setCurrency'])->name('currency.set');

    // Language Switcher
    Route::post('/language/set/{code}', [SearchController::class, 'setLanguage'])->name('language.set');

    // Stock Check
    Route::get('/stock/{product}', [ProductController::class, 'checkStock'])->name('stock.check');

    // Related Products
    Route::get('/products/{product}/related', [ProductController::class, 'relatedProducts'])->name('products.related');

    // Recently Viewed Products
    Route::get('/products/recently-viewed', [ProductController::class, 'recentlyViewed'])->name('products.recently-viewed');
});
