<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MaxMart Configuration
    |--------------------------------------------------------------------------
    |
    | All configuration settings for the MaxMart e-commerce platform.
    |
    */

    // Site Settings
    'site_name' => env('SITE_NAME', 'MaxMart'),
    'site_tagline' => env('SITE_TAGLINE', 'Premium E-Commerce Platform'),
    'site_description' => env('SITE_DESCRIPTION', 'Your one-stop shop for premium products'),

    // Currency Settings
    'default_currency' => env('DEFAULT_CURRENCY', 'USD'),
    'supported_currencies' => ['USD', 'EUR', 'GBP', 'BDT'],

    // Language Settings
    'default_language' => env('DEFAULT_LANGUAGE', 'en'),
    'supported_languages' => ['en', 'bn', 'es', 'fr'],

    // Pagination
    'products_per_page' => 12,
    'orders_per_page' => 20,
    'customers_per_page' => 20,
    'blog_posts_per_page' => 9,

    // Cart Settings
    'cart_expiry_days' => 30,
    'min_order_amount' => 10.00,
    'max_order_amount' => 10000.00,

    // Image Settings
    'product_image_path' => 'images/products',
    'category_image_path' => 'images/categories',
    'brand_image_path' => 'images/brands',
    'banner_image_path' => 'images/banners',
    'blog_image_path' => 'images/blog',
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
    'max_image_size' => 5120, // KB (5MB)

    // Product Settings
    'low_stock_threshold' => 10,
    'enable_product_reviews' => true,
    'enable_product_questions' => true,
    'require_review_approval' => true,
    'enable_wishlist' => true,
    'enable_compare' => true,

    // Order Settings
    'order_prefix' => 'ORD-',
    'invoice_prefix' => 'INV-',
    'allow_guest_checkout' => true,
    'auto_confirm_orders' => false,

    // Shipping Settings
    'free_shipping_threshold' => 100.00,
    'default_shipping_zone' => 'Domestic',

    // Tax Settings
    'tax_included_in_price' => false,
    'default_tax_class' => 'standard',

    // Coupon Settings
    'max_coupon_uses_per_user' => 5,
    'coupon_code_min_length' => 4,
    'coupon_code_max_length' => 20,

    // Flash Sale Settings
    'flash_sale_notification_enabled' => true,
    'flash_sale_email_enabled' => true,

    // SEO Settings
    'enable_seo' => true,
    'auto_generate_meta' => true,
    'sitemap_cache_hours' => 24,

    // Email Settings
    'order_confirmation_email' => true,
    'shipping_notification_email' => true,
    'password_reset_email' => true,
    'newsletter_enabled' => true,

    // SMS Settings
    'sms_enabled' => false,
    'order_confirmation_sms' => false,
    'shipping_notification_sms' => false,

    // Security Settings
    'enable_captcha' => false,
    'max_login_attempts' => 5,
    'lockout_duration_minutes' => 30,

    // Admin Settings
    'admin_email' => env('ADMIN_EMAIL', 'admin@maxmart.com'),
    'dashboard_chart_days' => 30,

    // Cache Settings
    'cache_categories' => true,
    'cache_brands' => true,
    'cache_homepage_sections' => true,
    'cache_duration_minutes' => 60,

    // Feature Toggles
    'enable_blog' => true,
    'enable_cms_pages' => true,
    'enable_menus' => true,
    'enable_testimonials' => true,
    'enable_newsletter' => true,
    'enable_contact_form' => true,
    'enable_abandoned_cart_recovery' => true,
];
