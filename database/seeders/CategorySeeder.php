<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Electronics (1)
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets and electronic devices',
                'parent_id' => null,
                'icon' => 'device-laptop',
                'sort_order' => 1,
                'is_active' => true,
                'children' => [
                    ['name' => 'Smartphones', 'slug' => 'smartphones', 'icon' => 'phone'],
                    ['name' => 'Laptops', 'slug' => 'laptops', 'icon' => 'laptop'],
                    ['name' => 'Tablets', 'slug' => 'tablets', 'icon' => 'tablet'],
                    ['name' => 'Headphones', 'slug' => 'headphones', 'icon' => 'headphones'],
                    ['name' => 'Cameras', 'slug' => 'cameras', 'icon' => 'camera'],
                    ['name' => 'Smart Watches', 'slug' => 'smart-watches', 'icon' => 'watch'],
                ]
            ],
            // Fashion (2)
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Trendy clothing and accessories',
                'parent_id' => null,
                'icon' => 'shirt',
                'sort_order' => 2,
                'is_active' => true,
                'children' => [
                    ['name' => "Men's Clothing", 'slug' => 'mens-clothing', 'icon' => 'user'],
                    ['name' => "Women's Clothing", 'slug' => 'womens-clothing', 'icon' => 'user'],
                    ['name' => 'Shoes', 'slug' => 'shoes', 'icon' => 'footprints'],
                    ['name' => 'Bags', 'slug' => 'bags', 'icon' => 'bag'],
                    ['name' => 'Jewelry', 'slug' => 'jewelry', 'icon' => 'gem'],
                    ['name' => 'Watches', 'slug' => 'watches', 'icon' => 'clock'],
                ]
            ],
            // Home & Garden (3)
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Everything for your home and garden',
                'parent_id' => null,
                'icon' => 'home',
                'sort_order' => 3,
                'is_active' => true,
                'children' => [
                    ['name' => 'Furniture', 'slug' => 'furniture', 'icon' => 'sofa'],
                    ['name' => 'Kitchen', 'slug' => 'kitchen', 'icon' => 'utensils'],
                    ['name' => 'Bedding', 'slug' => 'bedding', 'icon' => 'bed'],
                    ['name' => 'Decor', 'slug' => 'decor', 'icon' => 'image'],
                    ['name' => 'Garden Tools', 'slug' => 'garden-tools', 'icon' => 'shovel'],
                    ['name' => 'Lighting', 'slug' => 'lighting', 'icon' => 'lightbulb'],
                ]
            ],
            // Sports & Outdoors (4)
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Gear up for your adventures',
                'parent_id' => null,
                'icon' => 'activity',
                'sort_order' => 4,
                'is_active' => true,
                'children' => [
                    ['name' => 'Fitness Equipment', 'slug' => 'fitness-equipment', 'icon' => 'dumbbell'],
                    ['name' => 'Camping', 'slug' => 'camping', 'icon' => 'tent'],
                    ['name' => 'Cycling', 'slug' => 'cycling', 'icon' => 'bike'],
                    ['name' => 'Running', 'slug' => 'running', 'icon' => 'shoe-prints'],
                    ['name' => 'Team Sports', 'slug' => 'team-sports', 'icon' => 'football-ball'],
                ]
            ],
            // Beauty & Health (5)
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Look and feel your best',
                'parent_id' => null,
                'icon' => 'heart',
                'sort_order' => 5,
                'is_active' => true,
                'children' => [
                    ['name' => 'Skincare', 'slug' => 'skincare', 'icon' => 'spa'],
                    ['name' => 'Makeup', 'slug' => 'makeup', 'icon' => 'brush'],
                    ['name' => 'Hair Care', 'slug' => 'hair-care', 'icon' => 'scissors'],
                    ['name' => 'Fragrances', 'slug' => 'fragrances', 'icon' => 'flower'],
                    ['name' => 'Vitamins', 'slug' => 'vitamins', 'icon' => 'pill'],
                ]
            ],
            // Books & Media (6)
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Expand your knowledge and entertainment',
                'parent_id' => null,
                'icon' => 'book',
                'sort_order' => 6,
                'is_active' => true,
                'children' => [
                    ['name' => 'Fiction', 'slug' => 'fiction', 'icon' => 'book-open'],
                    ['name' => 'Non-Fiction', 'slug' => 'non-fiction', 'icon' => 'book'],
                    ['name' => 'Educational', 'slug' => 'educational', 'icon' => 'graduation-cap'],
                    ['name' => 'Music', 'slug' => 'music', 'icon' => 'music'],
                    ['name' => 'Movies', 'slug' => 'movies', 'icon' => 'film'],
                ]
            ],
            // Toys & Games (7)
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Fun for all ages',
                'parent_id' => null,
                'icon' => 'gamepad',
                'sort_order' => 7,
                'is_active' => true,
                'children' => [
                    ['name' => 'Action Figures', 'slug' => 'action-figures', 'icon' => 'robot'],
                    ['name' => 'Board Games', 'slug' => 'board-games', 'icon' => 'dice'],
                    ['name' => 'Puzzles', 'slug' => 'puzzles', 'icon' => 'puzzle-piece'],
                    ['name' => 'Outdoor Toys', 'slug' => 'outdoor-toys', 'icon' => 'baseball-ball'],
                    ['name' => 'Educational Toys', 'slug' => 'educational-toys', 'icon' => 'blocks'],
                ]
            ],
            // Automotive (8)
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Parts and accessories for your vehicle',
                'parent_id' => null,
                'icon' => 'car',
                'sort_order' => 8,
                'is_active' => true,
                'children' => [
                    ['name' => 'Car Accessories', 'slug' => 'car-accessories', 'icon' => 'car-side'],
                    ['name' => 'Motorcycle Parts', 'slug' => 'motorcycle-parts', 'icon' => 'motorcycle'],
                    ['name' => 'Tools', 'slug' => 'auto-tools', 'icon' => 'tools'],
                    ['name' => 'Car Care', 'slug' => 'car-care', 'icon' => 'spray-can'],
                ]
            ],
            // Pet Supplies (9)
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'description' => 'Everything for your furry friends',
                'parent_id' => null,
                'icon' => 'paw',
                'sort_order' => 9,
                'is_active' => true,
                'children' => [
                    ['name' => 'Dog Supplies', 'slug' => 'dog-supplies', 'icon' => 'dog'],
                    ['name' => 'Cat Supplies', 'slug' => 'cat-supplies', 'icon' => 'cat'],
                    ['name' => 'Bird Supplies', 'slug' => 'bird-supplies', 'icon' => 'feather'],
                    ['name' => 'Fish Supplies', 'slug' => 'fish-supplies', 'icon' => 'fish'],
                ]
            ],
            // Office Supplies (10)
            [
                'name' => 'Office Supplies',
                'slug' => 'office-supplies',
                'description' => 'Essentials for your workspace',
                'parent_id' => null,
                'icon' => 'briefcase',
                'sort_order' => 10,
                'is_active' => true,
                'children' => [
                    ['name' => 'Stationery', 'slug' => 'stationery', 'icon' => 'pen'],
                    ['name' => 'Paper Products', 'slug' => 'paper-products', 'icon' => 'file-alt'],
                    ['name' => 'Desk Accessories', 'slug' => 'desk-accessories', 'icon' => 'thumbtack'],
                    ['name' => 'Office Electronics', 'slug' => 'office-electronics', 'icon' => 'printer'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            foreach ($children as $childData) {
                Category::updateOrCreate(
                    ['slug' => $childData['slug']],
                    array_merge($childData, ['parent_id' => $parent->id])
                );
            }
        }
    }
}
