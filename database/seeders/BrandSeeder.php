<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'Think Different', 'website' => 'https://apple.com', 'is_featured' => true],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'Do What You Cant', 'website' => 'https://samsung.com', 'is_featured' => true],
            ['name' => 'Sony', 'slug' => 'sony', 'description' => 'Be Moved', 'website' => 'https://sony.com', 'is_featured' => true],
            ['name' => 'Nike', 'slug' => 'nike', 'description' => 'Just Do It', 'website' => 'https://nike.com', 'is_featured' => true],
            ['name' => 'Adidas', 'slug' => 'adidas', 'description' => 'Impossible Is Nothing', 'website' => 'https://adidas.com', 'is_featured' => true],
            ['name' => 'LG', 'slug' => 'lg', 'description' => 'Life is Good', 'website' => 'https://lg.com', 'is_featured' => false],
            ['name' => 'Dell', 'slug' => 'dell', 'description' => 'The power to do more', 'website' => 'https://dell.com', 'is_featured' => true],
            ['name' => 'HP', 'slug' => 'hp', 'description' => 'Keep Reinventing', 'website' => 'https://hp.com', 'is_featured' => false],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'description' => 'For Those Who Do', 'website' => 'https://lenovo.com', 'is_featured' => false],
            ['name' => 'Canon', 'slug' => 'canon', 'description' => 'Delighting You Always', 'website' => 'https://canon.com', 'is_featured' => true],
            ['name' => 'Bose', 'slug' => 'bose', 'description' => 'Better Sound Through Research', 'website' => 'https://bose.com', 'is_featured' => false],
            ['name' => 'Puma', 'slug' => 'puma', 'description' => 'Forever Faster', 'website' => 'https://puma.com', 'is_featured' => false],
            ['name' => 'Zara', 'slug' => 'zara', 'description' => 'Fashion at your fingertips', 'website' => 'https://zara.com', 'is_featured' => false],
            ['name' => 'H&M', 'slug' => 'hm', 'description' => 'Fashion and quality at the best price', 'website' => 'https://hm.com', 'is_featured' => false],
            ['name' => 'IKEA', 'slug' => 'ikea', 'description' => 'Create a better everyday life', 'website' => 'https://ikea.com', 'is_featured' => true],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                array_merge($brand, ['logo' => null, 'is_active' => true])
            );
        }
    }
}
