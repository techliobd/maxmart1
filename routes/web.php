<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Middleware\SetCurrency;
use App\Http\Middleware\SetLanguage;

/*
|--------------------------------------------------------------------------
| Storefront Routes - MaxMart E-Commerce Platform
|--------------------------------------------------------------------------
*/

// Apply global middleware
Route::middleware([SetCurrency::class, SetLanguage::class])->group(function () {

    // Home Page
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Authentication Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Customer Dashboard
        Route::prefix('account')->name('account.')->group(function () {
            Route::get('/', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
            Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
            Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
            Route::get('/orders/{order}', [CustomerDashboardController::class, 'showOrder'])->name('orders.show');
            Route::get('/addresses', [CustomerDashboardController::class, 'addresses'])->name('addresses');
            Route::post('/addresses', [CustomerDashboardController::class, 'storeAddress'])->name('addresses.store');
            Route::get('/addresses/{address}', [CustomerDashboardController::class, 'editAddress'])->name('addresses.edit');
            Route::put('/addresses/{address}', [CustomerDashboardController::class, 'updateAddress'])->name('addresses.update');
            Route::delete('/addresses/{address}', [CustomerDashboardController::class, 'destroyAddress'])->name('addresses.destroy');
            Route::get('/wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');
            Route::get('/reviews', [CustomerDashboardController::class, 'reviews'])->name('reviews');
        });
    });

    // Shop & Products
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('index');
        Route::get('/category/{category:slug}', [ShopController::class, 'byCategory'])->name('category');
        Route::get('/brand/{brand:slug}', [ShopController::class, 'byBrand'])->name('brand');
        Route::get('/search', [ShopController::class, 'search'])->name('search');
        Route::get('/filter', [ShopController::class, 'filter'])->name('filter');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product:slug}/reviews', [ProductController::class, 'reviews'])->name('reviews');
        Route::post('/{product:slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/{product:slug}/questions', [ProductController::class, 'questions'])->name('questions');
        Route::post('/{product:slug}/questions', [ProductController::class, 'askQuestion'])->name('questions.store');
        Route::post('/{product:slug}/vote', [ProductController::class, 'vote'])->name('vote');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');
        Route::post('/remove-coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');
    });

    // Checkout
    Route::prefix('checkout')->name('checkout.')->middleware('auth')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
        Route::get('/failure', [CheckoutController::class, 'failure'])->name('failure');
    });

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::middleware('auth')->group(function () {
            Route::post('/add/{product}', [WishlistController::class, 'add'])->name('add');
            Route::delete('/remove/{product}', [WishlistController::class, 'remove'])->name('remove');
        });
    });

    // Compare
    Route::prefix('compare')->name('compare.')->group(function () {
        Route::get('/', [CompareController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CompareController::class, 'add'])->name('add');
        Route::delete('/remove/{product}', [CompareController::class, 'remove'])->name('remove');
        Route::post('/clear', [CompareController::class, 'clear'])->name('clear');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

    // Blog
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/category/{category:slug}', [BlogController::class, 'byCategory'])->name('category');
        Route::get('/tag/{tag:slug}', [BlogController::class, 'byTag'])->name('tag');
        Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
        Route::post('/{post:slug}/comments', [BlogController::class, 'storeComment'])->name('comments.store');
    });

    // CMS Pages
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/{page:slug}', [PageController::class, 'show'])->name('show');
    });

    // Track Order
    Route::prefix('track-order')->name('track-order.')->group(function () {
        Route::get('/', [TrackOrderController::class, 'showForm'])->name('form');
        Route::post('/', [TrackOrderController::class, 'track'])->name('track');
        Route::get('/{orderNumber}', [TrackOrderController::class, 'trackByNumber'])->name('track.number');
    });

    // Contact
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

    // Newsletter
    Route::post('/newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
});
