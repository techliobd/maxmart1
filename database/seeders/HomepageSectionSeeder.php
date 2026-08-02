<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Hero Banner',
                'type' => 'hero',
                'title' => 'Welcome to MaxMart',
                'subtitle' => 'Discover Premium Products at Unbeatable Prices',
                'button_text' => 'Shop Now',
                'button_url' => '/shop',
                'image' => 'https://placehold.co/1920x600/f5f5f5/333333?text=Hero+Banner',
                'is_active' => true,
                'sort_order' => 1,
                'settings' => ['auto_rotate' => true, 'rotation_interval' => 5],
            ],
            [
                'name' => 'Featured Categories',
                'type' => 'featured_categories',
                'title' => 'Shop by Category',
                'subtitle' => 'Explore our most popular categories',
                'button_text' => 'View All',
                'button_url' => '/shop',
                'image' => null,
                'is_active' => true,
                'sort_order' => 2,
                'settings' => ['columns' => 6, 'show_counts' => true],
            ],
            [
                'name' => 'Flash Sale',
                'type' => 'flash_sale',
                'title' => 'Flash Sale',
                'subtitle' => 'Limited Time Offers - Up to 50% Off',
                'button_text' => 'Grab Deals',
                'button_url' => '/flash-sales',
                'image' => 'https://placehold.co/400x300/ff6b6b/ffffff?text=Flash+Sale',
                'is_active' => true,
                'sort_order' => 3,
                'settings' => ['countdown_end' => now()->addDays(3)->toDateTimeString()],
            ],
            [
                'name' => 'Featured Products',
                'type' => 'featured_products',
                'title' => 'Featured Products',
                'subtitle' => 'Handpicked selections just for you',
                'button_text' => 'See All',
                'button_url' => '/shop?featured=true',
                'image' => null,
                'is_active' => true,
                'sort_order' => 4,
                'settings' => ['limit' => 8, 'columns' => 4],
            ],
            [
                'name' => 'New Arrivals',
                'type' => 'new_arrivals',
                'title' => 'New Arrivals',
                'subtitle' => 'Check out the latest products',
                'button_text' => 'Shop New',
                'button_url' => '/shop?sort=newest',
                'image' => null,
                'is_active' => true,
                'sort_order' => 5,
                'settings' => ['limit' => 8, 'columns' => 4],
            ],
            [
                'name' => 'Brand Showcase',
                'type' => 'brands',
                'title' => 'Top Brands',
                'subtitle' => 'Shop from your favorite brands',
                'button_text' => 'All Brands',
                'button_url' => '/brands',
                'image' => null,
                'is_active' => true,
                'sort_order' => 6,
                'settings' => ['limit' => 10, 'logo_style' => 'grayscale'],
            ],
            [
                'name' => 'Testimonials',
                'type' => 'testimonials',
                'title' => 'What Our Customers Say',
                'subtitle' => 'Real reviews from verified buyers',
                'button_text' => null,
                'button_url' => null,
                'image' => null,
                'is_active' => true,
                'sort_order' => 7,
                'settings' => ['auto_rotate' => true, 'show_images' => true],
            ],
            [
                'name' => 'Blog Posts',
                'type' => 'blog',
                'title' => 'From Our Blog',
                'subtitle' => 'Latest news, tips, and trends',
                'button_text' => 'Read More',
                'button_url' => '/blog',
                'image' => null,
                'is_active' => true,
                'sort_order' => 8,
                'settings' => ['limit' => 3, 'show_excerpt' => true],
            ],
            [
                'name' => 'Newsletter',
                'type' => 'newsletter',
                'title' => 'Subscribe to Our Newsletter',
                'subtitle' => 'Get 10% off your first order when you sign up!',
                'button_text' => 'Subscribe',
                'button_url' => null,
                'image' => 'https://placehold.co/600x400/4f46e5/ffffff?text=Newsletter',
                'is_active' => true,
                'sort_order' => 9,
                'settings' => ['discount_code' => 'WELCOME10', 'show_social' => true],
            ],
        ];

        foreach ($sections as $sectionData) {
            HomepageSection::updateOrCreate(
                ['name' => $sectionData['name']],
                $sectionData
            );
        }
    }
}
