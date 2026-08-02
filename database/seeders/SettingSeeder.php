<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            'site_name' => 'MaxMart',
            'site_tagline' => 'Premium E-Commerce Platform',
            'site_description' => 'Your one-stop shop for premium products at great prices.',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.ico',
            'contact_email' => 'support@maxmart.com',
            'contact_phone' => '+1 (555) 123-4567',
            'contact_address' => '123 Commerce Street, New York, NY 10001',
            
            // Social Media
            'facebook_url' => 'https://facebook.com/maxmart',
            'twitter_url' => 'https://twitter.com/maxmart',
            'instagram_url' => 'https://instagram.com/maxmart',
            'youtube_url' => 'https://youtube.com/maxmart',
            
            // Business Settings
            'default_currency_id' => 1,
            'default_language_id' => 1,
            'tax_enabled' => true,
            'shipping_enabled' => true,
            'stock_management_enabled' => true,
            'low_stock_threshold' => 10,
            
            // Checkout Settings
            'guest_checkout_enabled' => true,
            'terms_page_id' => null,
            'privacy_page_id' => null,
            'return_policy_page_id' => null,
            
            // Email Settings
            'mail_from_name' => 'MaxMart',
            'mail_from_address' => 'noreply@maxmart.com',
            
            // SEO Settings
            'meta_title' => 'MaxMart - Premium E-Commerce Platform',
            'meta_description' => 'Shop the best products at MaxMart. Free shipping on orders over $50.',
            'meta_keywords' => 'ecommerce, shopping, online store, maxmart',
            
            // Analytics
            'google_analytics_id' => '',
            'facebook_pixel_id' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => gettype($value)]
            );
        }
    }
}
