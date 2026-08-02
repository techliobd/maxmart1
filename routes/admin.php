<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\TrackActivity;

/*
|--------------------------------------------------------------------------
| Admin Routes - MaxMart E-Commerce Platform
|--------------------------------------------------------------------------
|
| All admin routes are prefixed with /admin and protected by 
| authentication and admin authorization middleware.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', AdminAuth::class, TrackActivity::class])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart');

        // Products Management
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
            Route::post('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
            
            // Product Images
            Route::post('/{product}/images', [ProductController::class, 'uploadImages'])->name('images.upload');
            Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('images.delete');
            Route::post('/images/reorder', [ProductController::class, 'reorderImages'])->name('images.reorder');
            
            // Product Variations
            Route::get('/{product}/variations', [ProductController::class, 'variations'])->name('variations.index');
            Route::post('/{product}/variations', [ProductController::class, 'generateVariations'])->name('variations.generate');
            Route::put('/variations/{variation}', [ProductController::class, 'updateVariation'])->name('variations.update');
            Route::delete('/variations/{variation}', [ProductController::class, 'deleteVariation'])->name('variations.delete');
            Route::post('/variations/bulk-update', [ProductController::class, 'bulkUpdateVariations'])->name('variations.bulk-update');
        });

        // Categories Management
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
            Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Brands Management
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::get('/create', [BrandController::class, 'create'])->name('create');
            Route::post('/', [BrandController::class, 'store'])->name('store');
            Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('edit');
            Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
            Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
            Route::post('/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Attributes Management
        Route::prefix('attributes')->name('attributes.')->group(function () {
            Route::get('/', [AttributeController::class, 'index'])->name('index');
            Route::get('/create', [AttributeController::class, 'create'])->name('create');
            Route::post('/', [AttributeController::class, 'store'])->name('store');
            Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->name('edit');
            Route::put('/{attribute}', [AttributeController::class, 'update'])->name('update');
            Route::delete('/{attribute}', [AttributeController::class, 'destroy'])->name('destroy');
            
            // Attribute Values
            Route::post('/{attribute}/values', [AttributeController::class, 'addValue'])->name('values.add');
            Route::put('/values/{value}', [AttributeController::class, 'updateValue'])->name('values.update');
            Route::delete('/values/{value}', [AttributeController::class, 'deleteValue'])->name('values.delete');
        });

        // Orders Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
            Route::post('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/send-notification', [OrderController::class, 'sendNotification'])->name('send-notification');
            Route::post('/{order}/refund', [OrderController::class, 'processRefund'])->name('refund');
            Route::get('/export', [OrderController::class, 'export'])->name('export');
        });

        // Customers Management
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
            Route::get('/{customer}/orders', [CustomerController::class, 'orders'])->name('orders');
            Route::post('/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Coupons Management
        Route::prefix('coupons')->name('coupons.')->group(function () {
            Route::get('/', [CouponController::class, 'index'])->name('index');
            Route::get('/create', [CouponController::class, 'create'])->name('create');
            Route::post('/', [CouponController::class, 'store'])->name('store');
            Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
            Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
            Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
            Route::post('/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Flash Sales Management
        Route::prefix('flash-sales')->name('flash-sales.')->group(function () {
            Route::get('/', [FlashSaleController::class, 'index'])->name('index');
            Route::get('/create', [FlashSaleController::class, 'create'])->name('create');
            Route::post('/', [FlashSaleController::class, 'store'])->name('store');
            Route::get('/{flashSale}/edit', [FlashSaleController::class, 'edit'])->name('edit');
            Route::put('/{flashSale}', [FlashSaleController::class, 'update'])->name('update');
            Route::delete('/{flashSale}', [FlashSaleController::class, 'destroy'])->name('destroy');
            Route::post('/{flashSale}/toggle-status', [FlashSaleController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Blog Management
        Route::prefix('blog')->name('blog.')->group(function () {
            Route::get('/posts', [BlogController::class, 'indexPosts'])->name('posts.index');
            Route::get('/posts/create', [BlogController::class, 'createPost'])->name('posts.create');
            Route::post('/posts', [BlogController::class, 'storePost'])->name('posts.store');
            Route::get('/posts/{post}/edit', [BlogController::class, 'editPost'])->name('posts.edit');
            Route::put('/posts/{post}', [BlogController::class, 'updatePost'])->name('posts.update');
            Route::delete('/posts/{post}', [BlogController::class, 'deletePost'])->name('posts.delete');
            
            Route::get('/categories', [BlogController::class, 'indexCategories'])->name('categories.index');
            Route::post('/categories', [BlogController::class, 'storeCategory'])->name('categories.store');
            Route::put('/categories/{category}', [BlogController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{category}', [BlogController::class, 'deleteCategory'])->name('categories.delete');
            
            Route::get('/comments', [BlogController::class, 'indexComments'])->name('comments.index');
            Route::post('/comments/{comment}/approve', [BlogController::class, 'approveComment'])->name('comments.approve');
            Route::post('/comments/{comment}/reject', [BlogController::class, 'rejectComment'])->name('comments.reject');
        });

        // CMS Pages Management
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('index');
            Route::get('/create', [PageController::class, 'create'])->name('create');
            Route::post('/', [PageController::class, 'store'])->name('store');
            Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit');
            Route::put('/{page}', [PageController::class, 'update'])->name('update');
            Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');
            Route::post('/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Menu Management
        Route::prefix('menus')->name('menus.')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('index');
            Route::get('/create', [MenuController::class, 'create'])->name('create');
            Route::post('/', [MenuController::class, 'store'])->name('store');
            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('edit');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('update');
            Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('destroy');
            Route::post('/items/reorder', [MenuController::class, 'reorderItems'])->name('items.reorder');
            Route::post('/items', [MenuController::class, 'addItem'])->name('items.add');
            Route::put('/items/{item}', [MenuController::class, 'updateItem'])->name('items.update');
            Route::delete('/items/{item}', [MenuController::class, 'deleteItem'])->name('items.delete');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/general', [SettingController::class, 'updateGeneral'])->name('update.general');
            Route::put('/email', [SettingController::class, 'updateEmail'])->name('update.email');
            Route::put('/sms', [SettingController::class, 'updateSms'])->name('update.sms');
            Route::put('/payment', [SettingController::class, 'updatePayment'])->name('update.payment');
            Route::put('/shipping', [SettingController::class, 'updateShipping'])->name('update.shipping');
            Route::put('/tax', [SettingController::class, 'updateTax'])->name('update.tax');
            Route::put('/seo', [SettingController::class, 'updateSeo'])->name('update.seo');
        });

        // Appearance Management
        Route::prefix('appearance')->name('appearance.')->group(function () {
            Route::get('/', [AppearanceController::class, 'index'])->name('index');
            Route::put('/theme', [AppearanceController::class, 'updateTheme'])->name('update.theme');
            Route::put('/colors', [AppearanceController::class, 'updateColors'])->name('update.colors');
            Route::put('/logo', [AppearanceController::class, 'updateLogo'])->name('update.logo');
            Route::put('/favicon', [AppearanceController::class, 'updateFavicon'])->name('update.favicon');
            
            // Homepage Sections
            Route::get('/homepage-sections', [AppearanceController::class, 'homepageSections'])->name('homepage-sections');
            Route::post('/homepage-sections', [AppearanceController::class, 'addSection'])->name('sections.add');
            Route::put('/homepage-sections/{section}', [AppearanceController::class, 'updateSection'])->name('sections.update');
            Route::delete('/homepage-sections/{section}', [AppearanceController::class, 'deleteSection'])->name('sections.delete');
            Route::post('/homepage-sections/reorder', [AppearanceController::class, 'reorderSections'])->name('sections.reorder');
        });

        // Staff & Roles Management
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [StaffController::class, 'index'])->name('index');
            Route::get('/create', [StaffController::class, 'create'])->name('create');
            Route::post('/', [StaffController::class, 'store'])->name('store');
            Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
            Route::put('/{staff}', [StaffController::class, 'update'])->name('update');
            Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
            Route::post('/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('toggle-status');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [StaffController::class, 'rolesIndex'])->name('index');
            Route::get('/create', [StaffController::class, 'createRole'])->name('create');
            Route::post('/', [StaffController::class, 'storeRole'])->name('store');
            Route::get('/{role}/edit', [StaffController::class, 'editRole'])->name('edit');
            Route::put('/{role}', [StaffController::class, 'updateRole'])->name('update');
            Route::delete('/{role}', [StaffController::class, 'deleteRole'])->name('destroy');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/products', [ReportController::class, 'products'])->name('products');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });

        // SEO Management
        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/', [SeoController::class, 'index'])->name('index');
            Route::get('/sitemap', [SeoController::class, 'sitemap'])->name('sitemap');
            Route::post('/sitemap/generate', [SeoController::class, 'generateSitemap'])->name('sitemap.generate');
            Route::get('/robots', [SeoController::class, 'robots'])->name('robots');
            Route::post('/robots/update', [SeoController::class, 'updateRobots'])->name('robots.update');
            Route::get('/meta-tags', [SeoController::class, 'metaTags'])->name('meta-tags');
            Route::put('/meta-tags', [SeoController::class, 'updateMetaTags'])->name('meta-tags.update');
        });

        // Backup Management
        Route::prefix('backups')->name('backups.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::post('/create', [BackupController::class, 'create'])->name('create');
            Route::get('/download/{backup}', [BackupController::class, 'download'])->name('download');
            Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{backup}', [BackupController::class, 'restore'])->name('restore');
        });

        // Media Manager
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaController::class, 'index'])->name('index');
            Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
            Route::get('/folder', [MediaController::class, 'folder'])->name('folder');
            Route::post('/folder/create', [MediaController::class, 'createFolder'])->name('folder.create');
            Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
            Route::put('/{media}/rename', [MediaController::class, 'rename'])->name('rename');
            Route::post('/move', [MediaController::class, 'move'])->name('move');
        });

        // Activity Log
        Route::prefix('activity-log')->name('activity-log.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
            Route::delete('/clear', [ActivityLogController::class, 'clear'])->name('clear');
        });
    });
