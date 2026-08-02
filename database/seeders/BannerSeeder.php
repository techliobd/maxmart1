<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'name' => 'Homepage Hero - Main',
                'location' => 'homepage_hero',
                'image' => 'https://placehold.co/1920x600/4f46e5/ffffff?text=New+Arrivals+Shop+Now',
                'title' => 'New Arrivals',
                'subtitle' => 'Discover the latest trends',
                'button_text' => 'Shop Now',
                'button_url' => '/shop?sort=newest',
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'Homepage Hero - Sale',
                'location' => 'homepage_hero',
                'image' => 'https://placehold.co/1920x600/ef4444/ffffff?text=Summer+Sale+Up+to+50+Off',
                'title' => 'Summer Sale',
                'subtitle' => 'Up to 50% off selected items',
                'button_text' => 'Grab Deals',
                'button_url' => '/flash-sales',
                'is_active' => true,
                'sort_order' => 2,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ],
            [
                'name' => 'Sidebar Electronics',
                'location' => 'sidebar_electronics',
                'image' => 'https://placehold.co/400x500/1f2937/ffffff?text=Tech+Deals',
                'title' => 'Tech Deals',
                'subtitle' => 'Save on gadgets',
                'button_text' => 'Browse',
                'button_url' => '/category/electronics',
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'Sidebar Fashion',
                'location' => 'sidebar_fashion',
                'image' => 'https://placehold.co/400x500/ec4899/ffffff?text=Fashion+Trends',
                'title' => 'Fashion Trends',
                'subtitle' => 'Spring collection',
                'button_text' => 'Explore',
                'button_url' => '/category/fashion',
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'Category Page Top - Electronics',
                'location' => 'category_top',
                'image' => 'https://placehold.co/1200x300/3b82f6/ffffff?text=Electronics+Category+Banner',
                'title' => 'Electronics',
                'subtitle' => 'Latest gadgets and devices',
                'button_text' => null,
                'button_url' => null,
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
                'target_category_id' => null,
            ],
            [
                'name' => 'Checkout Promo',
                'location' => 'checkout_sidebar',
                'image' => 'https://placehold.co/400x300/10b981/ffffff?text=Free+Shipping+Over+50',
                'title' => 'Free Shipping',
                'subtitle' => 'On orders over $50',
                'button_text' => null,
                'button_url' => null,
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
            ],
            [
                'name' => 'Mobile App Promo',
                'location' => 'footer_banner',
                'image' => 'https://placehold.co/800x200/8b5cf6/ffffff?text=Download+Our+App',
                'title' => 'Download Our App',
                'subtitle' => 'Get exclusive mobile deals',
                'button_text' => 'Download',
                'button_url' => '#',
                'is_active' => true,
                'sort_order' => 1,
                'start_date' => now(),
                'end_date' => null,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::updateOrCreate(
                ['name' => $bannerData['name']],
                $bannerData
            );
        }
    }
}
