<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main Navigation Menu
        $mainMenu = Menu::updateOrCreate(
            ['name' => 'Main Menu'],
            ['slug' => 'main-menu', 'description' => 'Primary navigation menu']
        );

        $menuItems = [
            [
                'title' => 'Home',
                'url' => '/',
                'sort_order' => 1,
                'parent_id' => null,
                'children' => [],
            ],
            [
                'title' => 'Shop',
                'url' => '/shop',
                'sort_order' => 2,
                'parent_id' => null,
                'children' => [
                    ['title' => 'Electronics', 'url' => '/category/electronics', 'sort_order' => 1],
                    ['title' => 'Fashion', 'url' => '/category/fashion', 'sort_order' => 2],
                    ['title' => 'Home & Garden', 'url' => '/category/home-garden', 'sort_order' => 3],
                    ['title' => 'Sports & Outdoors', 'url' => '/category/sports-outdoors', 'sort_order' => 4],
                    ['title' => 'Beauty & Health', 'url' => '/category/beauty-health', 'sort_order' => 5],
                ],
            ],
            [
                'title' => 'Brands',
                'url' => '/brands',
                'sort_order' => 3,
                'parent_id' => null,
                'children' => [
                    ['title' => 'Apple', 'url' => '/brand/apple', 'sort_order' => 1],
                    ['title' => 'Samsung', 'url' => '/brand/samsung', 'sort_order' => 2],
                    ['title' => 'Nike', 'url' => '/brand/nike', 'sort_order' => 3],
                    ['title' => 'Adidas', 'url' => '/brand/adidas', 'sort_order' => 4],
                    ['title' => 'Sony', 'url' => '/brand/sony', 'sort_order' => 5],
                ],
            ],
            [
                'title' => 'Blog',
                'url' => '/blog',
                'sort_order' => 4,
                'parent_id' => null,
                'children' => [],
            ],
            [
                'title' => 'Pages',
                'url' => '#',
                'sort_order' => 5,
                'parent_id' => null,
                'children' => [
                    ['title' => 'About Us', 'url' => '/page/about-us', 'sort_order' => 1],
                    ['title' => 'Contact', 'url' => '/page/contact-us', 'sort_order' => 2],
                    ['title' => 'FAQ', 'url' => '/page/faq', 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($menuItems as $itemData) {
            $children = $itemData['children'];
            unset($itemData['children']);

            $menuItem = MenuItem::updateOrCreate(
                [
                    'menu_id' => $mainMenu->id,
                    'title' => $itemData['title'],
                    'parent_id' => $itemData['parent_id'],
                ],
                array_merge($itemData, ['menu_id' => $mainMenu->id])
            );

            foreach ($children as $childData) {
                MenuItem::updateOrCreate(
                    [
                        'menu_id' => $mainMenu->id,
                        'title' => $childData['title'],
                        'parent_id' => $menuItem->id,
                    ],
                    array_merge($childData, ['menu_id' => $mainMenu->id])
                );
            }
        }

        // Footer Menu
        $footerMenu = Menu::updateOrCreate(
            ['name' => 'Footer Menu'],
            ['slug' => 'footer-menu', 'description' => 'Footer navigation links']
        );

        $footerItems = [
            ['title' => 'About Us', 'url' => '/page/about-us', 'sort_order' => 1],
            ['title' => 'Contact', 'url' => '/page/contact-us', 'sort_order' => 2],
            ['title' => 'Terms of Service', 'url' => '/page/terms-of-service', 'sort_order' => 3],
            ['title' => 'Privacy Policy', 'url' => '/page/privacy-policy', 'sort_order' => 4],
            ['title' => 'Shipping Policy', 'url' => '/page/shipping-policy', 'sort_order' => 5],
            ['title' => 'Return Policy', 'url' => '/page/return-policy', 'sort_order' => 6],
        ];

        foreach ($footerItems as $itemData) {
            MenuItem::updateOrCreate(
                [
                    'menu_id' => $footerMenu->id,
                    'title' => $itemData['title'],
                ],
                array_merge($itemData, ['menu_id' => $footerMenu->id, 'parent_id' => null])
            );
        }

        // Customer Menu (for logged-in users)
        $customerMenu = Menu::updateOrCreate(
            ['name' => 'Customer Menu'],
            ['slug' => 'customer-menu', 'description' => 'Customer account navigation']
        );

        $customerItems = [
            ['title' => 'Dashboard', 'url' => '/customer/dashboard', 'sort_order' => 1],
            ['title' => 'Orders', 'url' => '/customer/orders', 'sort_order' => 2],
            ['title' => 'Wishlist', 'url' => '/wishlist', 'sort_order' => 3],
            ['title' => 'Addresses', 'url' => '/customer/addresses', 'sort_order' => 4],
            ['title' => 'Profile', 'url' => '/customer/profile', 'sort_order' => 5],
        ];

        foreach ($customerItems as $itemData) {
            MenuItem::updateOrCreate(
                [
                    'menu_id' => $customerMenu->id,
                    'title' => $itemData['title'],
                ],
                array_merge($itemData, ['menu_id' => $customerMenu->id, 'parent_id' => null])
            );
        }
    }
}
