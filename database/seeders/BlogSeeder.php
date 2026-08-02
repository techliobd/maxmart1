<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Latest tech news and reviews'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Style trends and tips'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Living your best life'],
            ['name' => 'Health & Wellness', 'slug' => 'health-wellness', 'description' => 'Tips for a healthier you'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement ideas'],
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                array_merge($categoryData, ['is_active' => true])
            );
        }

        $techCategory = BlogCategory::where('slug', 'technology')->first();
        $fashionCategory = BlogCategory::where('slug', 'fashion')->first();
        $lifestyleCategory = BlogCategory::where('slug', 'lifestyle')->first();

        $posts = [
            [
                'title' => 'Top 10 Smartphones of 2024',
                'slug' => 'top-10-smartphones-2024',
                'excerpt' => 'Discover the best smartphones available this year with our comprehensive guide.',
                'content' => '<p>The smartphone market continues to evolve rapidly in 2024. From cutting-edge AI features to improved camera systems, this year\'s flagship phones offer incredible value.</p><h2>1. iPhone 15 Pro Max</h2><p>Apple\'s latest flagship brings titanium design and the powerful A17 Pro chip.</p><h2>2. Samsung Galaxy S24 Ultra</h2><p>Samsung pushes boundaries with Galaxy AI and an impressive 200MP camera.</p><h2>3. Google Pixel 8 Pro</h2><p>Google\'s AI prowess shines through in photography and smart features.</p>',
                'meta_title' => 'Best Smartphones 2024 - Top 10 Reviews',
                'meta_description' => 'Compare the top 10 smartphones of 2024. Find the perfect phone for your needs.',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'category_id' => $techCategory?->id,
                'author_id' => null,
            ],
            [
                'title' => 'Spring Fashion Trends You Need to Know',
                'slug' => 'spring-fashion-trends-2024',
                'excerpt' => 'Stay ahead of the curve with these must-have spring fashion trends.',
                'content' => '<p>Spring 2024 brings fresh colors and silhouettes to refresh your wardrobe. Here are the key trends to watch:</p><h2>Pastel Colors</h2><p>Soft hues dominate this season, from mint green to lavender.</p><h2>Oversized Blazers</h2><p>Power dressing gets a relaxed update with oversized tailoring.</p><h2>Sustainable Fashion</h2><p>Eco-friendly materials and ethical production take center stage.</p>',
                'meta_title' => 'Spring 2024 Fashion Trends',
                'meta_description' => 'Discover the hottest spring fashion trends for 2024.',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'category_id' => $fashionCategory?->id,
                'author_id' => null,
            ],
            [
                'title' => '10 Tips for a Productive Home Office',
                'slug' => 'productive-home-office-tips',
                'excerpt' => 'Transform your workspace with these expert productivity tips.',
                'content' => '<p>Working from home requires discipline and the right environment. Follow these tips to boost your productivity:</p><h2>1. Create a Dedicated Workspace</h2><p>Having a specific area for work helps separate professional and personal life.</p><h2>2. Invest in Ergonomic Furniture</h2><p>A good chair and desk can prevent back pain and improve focus.</p><h2>3. Minimize Distractions</h2><p>Set boundaries with family members and limit social media use during work hours.</p>',
                'meta_title' => 'Home Office Productivity Tips',
                'meta_description' => 'Learn how to create a productive home office environment.',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'category_id' => $lifestyleCategory?->id,
                'author_id' => null,
            ],
            [
                'title' => 'The Ultimate Guide to Wireless Headphones',
                'slug' => 'wireless-headphones-guide',
                'excerpt' => 'Everything you need to know before buying wireless headphones.',
                'content' => '<p>Wireless headphones have become essential for music lovers and professionals alike. Here\'s what to consider:</p><h2>Noise Cancellation</h2><p>Active noise cancellation (ANC) blocks external sounds for immersive listening.</p><h2>Battery Life</h2><p>Look for at least 20 hours of playback for all-day use.</p><h2>Comfort</h2><p>Memory foam ear cushions and adjustable headbands ensure long-term comfort.</p>',
                'meta_title' => 'Wireless Headphones Buying Guide',
                'meta_description' => 'Complete guide to choosing the best wireless headphones.',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'category_id' => $techCategory?->id,
                'author_id' => null,
            ],
            [
                'title' => 'Morning Routine for Better Health',
                'slug' => 'morning-routine-better-health',
                'excerpt' => 'Start your day right with this healthy morning routine.',
                'content' => '<p>A consistent morning routine sets the tone for a productive day. Here\'s how to optimize yours:</p><h2>Wake Up Early</h2><p>Rising at the same time each day regulates your circadian rhythm.</p><h2>Hydrate First</h2><p>Drink a glass of water immediately after waking to rehydrate.</p><h2>Exercise</h2><p>Even 10 minutes of movement boosts energy and mood.</p>',
                'meta_title' => 'Healthy Morning Routine Guide',
                'meta_description' => 'Build a morning routine that improves your health and productivity.',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(25),
                'category_id' => $lifestyleCategory?->id,
                'author_id' => null,
            ],
        ];

        foreach ($posts as $postData) {
            BlogPost::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }
    }
}
