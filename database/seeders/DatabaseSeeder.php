<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core Settings
        $this->call([
            SettingSeeder::class,
            CurrencySeeder::class,
            LanguageSeeder::class,
        ]);

        // Admin & Staff Users
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Catalog Data
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
        ]);

        // Content
        $this->call([
            BlogSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            HomepageSectionSeeder::class,
            TestimonialSeeder::class,
            BannerSeeder::class,
        ]);

        // Promotions
        $this->call([
            CouponSeeder::class,
        ]);
    }
}
