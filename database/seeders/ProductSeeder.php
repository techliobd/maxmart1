<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use App\Models\VariationAttributeValue;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronicsCategory = Category::where('slug', 'electronics')->first();
        $fashionCategory = Category::where('slug', 'fashion')->first();
        $homeCategory = Category::where('slug', 'home-garden')->first();
        $sportsCategory = Category::where('slug', 'sports-outdoors')->first();
        $beautyCategory = Category::where('slug', 'beauty-health')->first();

        $appleBrand = Brand::where('slug', 'apple')->first();
        $samsungBrand = Brand::where('slug', 'samsung')->first();
        $sonyBrand = Brand::where('slug', 'sony')->first();
        $nikeBrand = Brand::where('slug', 'nike')->first();
        $adidasBrand = Brand::where('slug', 'adidas')->first();
        $dellBrand = Brand::where('slug', 'dell')->first();
        $canonBrand = Brand::where('slug', 'canon')->first();
        $boseBrand = Brand::where('slug', 'bose')->first();
        $ikeaBrand = Brand::where('slug', 'ikea')->first();

        $colorAttribute = Attribute::where('slug', 'color')->first();
        $sizeAttribute = Attribute::where('slug', 'size')->first();
        $ramAttribute = Attribute::where('slug', 'ram')->first();
        $storageAttribute = Attribute::where('slug', 'storage')->first();
        $screenSizeAttribute = Attribute::where('slug', 'screen-size')->first();
        $materialAttribute = Attribute::where('slug', 'material')->first();

        $products = [
            // Electronics - Smartphones
            [
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'description' => 'The ultimate iPhone with titanium design, A17 Pro chip, and advanced camera system.',
                'short_description' => 'Premium smartphone with titanium design',
                'sku' => 'IPH15PM',
                'price' => 1199.00,
                'compare_price' => 1299.00,
                'cost_price' => 899.00,
                'stock_quantity' => 50,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $appleBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'White', 'Blue', 'Natural Titanium']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['256GB', '512GB', '1TB']],
                ],
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Experience the power of Galaxy AI with the most advanced Samsung smartphone.',
                'short_description' => 'AI-powered flagship smartphone',
                'sku' => 'SGS24U',
                'price' => 1299.00,
                'compare_price' => 1399.00,
                'cost_price' => 950.00,
                'stock_quantity' => 45,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $samsungBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Titanium Black', 'Titanium Gray', 'Titanium Violet', 'Titanium Yellow']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['256GB', '512GB', '1TB']],
                ],
            ],
            [
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'description' => 'A total powerhouse with the A15 Bionic chip and advanced dual-camera system.',
                'short_description' => 'Powerful everyday smartphone',
                'sku' => 'IPH14',
                'price' => 799.00,
                'compare_price' => 899.00,
                'cost_price' => 550.00,
                'stock_quantity' => 80,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $appleBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Midnight', 'Starlight', 'Blue', 'Purple', 'Red']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['128GB', '256GB', '512GB']],
                ],
            ],
            // Electronics - Laptops
            [
                'name' => 'MacBook Pro 16"',
                'slug' => 'macbook-pro-16',
                'description' => 'Supercharged by M3 Pro or M3 Max chips. The most advanced Mac laptops ever built.',
                'short_description' => 'Professional laptop with M3 chip',
                'sku' => 'MBP16',
                'price' => 2499.00,
                'compare_price' => 2699.00,
                'cost_price' => 1800.00,
                'stock_quantity' => 25,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $appleBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Space Black', 'Silver']],
                    ['attribute_id' => $ramAttribute?->id, 'values' => ['18GB', '36GB', '48GB']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['512GB', '1TB', '2TB']],
                ],
            ],
            [
                'name' => 'Dell XPS 15',
                'slug' => 'dell-xps-15',
                'description' => 'Stunning OLED display meets powerful performance in this premium laptop.',
                'short_description' => 'Premium Windows laptop',
                'sku' => 'DXPS15',
                'price' => 1799.00,
                'compare_price' => 1999.00,
                'cost_price' => 1200.00,
                'stock_quantity' => 30,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $dellBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Platinum Silver', 'Frost White']],
                    ['attribute_id' => $ramAttribute?->id, 'values' => ['16GB', '32GB', '64GB']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['512GB', '1TB', '2TB']],
                ],
            ],
            [
                'name' => 'MacBook Air 13" M2',
                'slug' => 'macbook-air-13-m2',
                'description' => 'Strikingly thin and fast. The perfect everyday laptop powered by M2.',
                'short_description' => 'Thin and light everyday laptop',
                'sku' => 'MBA13M2',
                'price' => 1099.00,
                'compare_price' => 1199.00,
                'cost_price' => 750.00,
                'stock_quantity' => 60,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $appleBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Midnight', 'Starlight', 'Space Gray', 'Silver']],
                    ['attribute_id' => $storageAttribute?->id, 'values' => ['256GB', '512GB', '1TB']],
                ],
            ],
            // Electronics - Headphones
            [
                'name' => 'AirPods Pro (2nd Gen)',
                'slug' => 'airpods-pro-2nd-gen',
                'description' => 'Active Noise Cancellation, Adaptive Audio, and Personalized Spatial Audio.',
                'short_description' => 'Premium wireless earbuds',
                'sku' => 'APRO2',
                'price' => 249.00,
                'compare_price' => 279.00,
                'cost_price' => 150.00,
                'stock_quantity' => 100,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $appleBrand?->id,
                'attributes' => [],
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Industry-leading noise cancellation with exceptional sound quality.',
                'short_description' => 'Premium noise-canceling headphones',
                'sku' => 'SWH1000XM5',
                'price' => 399.00,
                'compare_price' => 449.00,
                'cost_price' => 250.00,
                'stock_quantity' => 75,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $sonyBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'Silver']],
                ],
            ],
            [
                'name' => 'Bose QuietComfort Ultra',
                'slug' => 'bose-quietcomfort-ultra',
                'description' => 'World-class noise cancellation with immersive spatial audio.',
                'short_description' => 'Ultimate comfort and sound',
                'sku' => 'BQCU',
                'price' => 429.00,
                'compare_price' => 479.00,
                'cost_price' => 280.00,
                'stock_quantity' => 50,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $boseBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'White Smoke', 'Sandstone']],
                ],
            ],
            // Electronics - Cameras
            [
                'name' => 'Canon EOS R6 Mark II',
                'slug' => 'canon-eos-r6-mark-ii',
                'description' => 'Full-frame mirrorless camera with advanced autofocus and 4K video.',
                'short_description' => 'Professional mirrorless camera',
                'sku' => 'CR6M2',
                'price' => 2499.00,
                'compare_price' => 2699.00,
                'cost_price' => 1800.00,
                'stock_quantity' => 15,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $electronicsCategory?->id,
                'brand_id' => $canonBrand?->id,
                'attributes' => [],
            ],
            // Fashion - Men's Clothing
            [
                'name' => 'Nike Air Max T-Shirt',
                'slug' => 'nike-air-max-tshirt',
                'description' => 'Classic cotton t-shirt with iconic Air Max branding.',
                'short_description' => 'Comfortable everyday t-shirt',
                'sku' => 'NAMTSH',
                'price' => 35.00,
                'compare_price' => 45.00,
                'cost_price' => 15.00,
                'stock_quantity' => 200,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $fashionCategory?->id,
                'brand_id' => $nikeBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'White', 'Navy', 'Red']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['S', 'M', 'L', 'XL', 'XXL']],
                ],
            ],
            [
                'name' => 'Adidas Originals Hoodie',
                'slug' => 'adidas-originals-hoodie',
                'description' => 'Classic pullover hoodie with trefoil logo.',
                'short_description' => 'Warm and stylish hoodie',
                'sku' => 'AOHOO',
                'price' => 75.00,
                'compare_price' => 90.00,
                'cost_price' => 35.00,
                'stock_quantity' => 150,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $fashionCategory?->id,
                'brand_id' => $adidasBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'Gray', 'Navy']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['S', 'M', 'L', 'XL', 'XXL']],
                ],
            ],
            [
                'name' => 'Nike Dri-FIT Running Shorts',
                'slug' => 'nike-dri-fit-running-shorts',
                'description' => 'Lightweight shorts with moisture-wicking technology.',
                'short_description' => 'Performance running shorts',
                'sku' => 'NDFRS',
                'price' => 45.00,
                'compare_price' => 55.00,
                'cost_price' => 20.00,
                'stock_quantity' => 180,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $fashionCategory?->id,
                'brand_id' => $nikeBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'Navy', 'Gray', 'Blue']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['S', 'M', 'L', 'XL']],
                ],
            ],
            // Fashion - Shoes
            [
                'name' => 'Nike Air Force 1',
                'slug' => 'nike-air-force-1',
                'description' => 'The iconic basketball shoe that became a streetwear legend.',
                'short_description' => 'Classic basketball sneakers',
                'sku' => 'NAF1',
                'price' => 110.00,
                'compare_price' => 130.00,
                'cost_price' => 55.00,
                'stock_quantity' => 120,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $fashionCategory?->id,
                'brand_id' => $nikeBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['White', 'Black', 'White/Black']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['7', '8', '9', '10', '11', '12']],
                ],
            ],
            [
                'name' => 'Adidas Ultraboost 23',
                'slug' => 'adidas-ultraboost-23',
                'description' => 'Responsive cushioning meets energy return for amazing runs.',
                'short_description' => 'Premium running shoes',
                'sku' => 'AUB23',
                'price' => 190.00,
                'compare_price' => 220.00,
                'cost_price' => 95.00,
                'stock_quantity' => 90,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $fashionCategory?->id,
                'brand_id' => $adidasBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Core Black', 'Cloud White', 'Grey']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['7', '8', '9', '10', '11', '12']],
                ],
            ],
            // Home & Garden - Furniture
            [
                'name' => 'IKEA POÄNG Armchair',
                'slug' => 'ikea-poang-armchair',
                'description' => 'Iconic bentwood armchair with comfortable cushion.',
                'short_description' => 'Classic Scandinavian armchair',
                'sku' => 'IPOANG',
                'price' => 149.00,
                'compare_price' => 179.00,
                'cost_price' => 75.00,
                'stock_quantity' => 40,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $homeCategory?->id,
                'brand_id' => $ikeaBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Birch veneer', 'Black-brown', 'White']],
                ],
            ],
            [
                'name' => 'IKEA MALM Bed Frame',
                'slug' => 'ikea-malm-bed-frame',
                'description' => 'Classic bed frame with adjustable bed sides.',
                'short_description' => 'Versatile bed frame',
                'sku' => 'IMALM',
                'price' => 249.00,
                'compare_price' => 299.00,
                'cost_price' => 125.00,
                'stock_quantity' => 30,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $homeCategory?->id,
                'brand_id' => $ikeaBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['White', 'Black-brown', 'Oak veneer']],
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['Queen', 'King']],
                ],
            ],
            // Home & Garden - Kitchen
            [
                'name' => 'IKEA 365+ Cookware Set',
                'slug' => 'ikea-365-cookware-set',
                'description' => 'Complete cookware set for everyday cooking.',
                'short_description' => 'Essential cookware collection',
                'sku' => 'I365CW',
                'price' => 199.00,
                'compare_price' => 249.00,
                'cost_price' => 100.00,
                'stock_quantity' => 50,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $homeCategory?->id,
                'brand_id' => $ikeaBrand?->id,
                'attributes' => [],
            ],
            // Sports & Outdoors
            [
                'name' => 'Nike Yoga Mat',
                'slug' => 'nike-yoga-mat',
                'description' => 'Non-slip yoga mat with excellent cushioning.',
                'short_description' => 'Premium yoga mat',
                'sku' => 'NYM',
                'price' => 65.00,
                'compare_price' => 80.00,
                'cost_price' => 30.00,
                'stock_quantity' => 100,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $sportsCategory?->id,
                'brand_id' => $nikeBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'Purple', 'Blue', 'Pink']],
                ],
            ],
            [
                'name' => 'Adidas Dumbbell Set',
                'slug' => 'adidas-dumbbell-set',
                'description' => 'Adjustable dumbbell set for home workouts.',
                'short_description' => 'Versatile weight training',
                'sku' => 'ADS',
                'price' => 149.00,
                'compare_price' => 199.00,
                'cost_price' => 75.00,
                'stock_quantity' => 60,
                'is_featured' => true,
                'is_active' => true,
                'category_id' => $sportsCategory?->id,
                'brand_id' => $adidasBrand?->id,
                'attributes' => [
                    ['attribute_id' => $colorAttribute?->id, 'values' => ['Black', 'Red']],
                ],
            ],
            // Beauty & Health
            [
                'name' => 'Organic Face Serum',
                'slug' => 'organic-face-serum',
                'description' => 'Vitamin C enriched serum for radiant skin.',
                'short_description' => 'Brightening face serum',
                'sku' => 'OFS',
                'price' => 45.00,
                'compare_price' => 60.00,
                'cost_price' => 20.00,
                'stock_quantity' => 150,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $beautyCategory?->id,
                'brand_id' => null,
                'attributes' => [
                    ['attribute_id' => $sizeAttribute?->id, 'values' => ['30ml', '50ml', '100ml']],
                ],
            ],
            [
                'name' => 'Natural Lip Balm Set',
                'slug' => 'natural-lip-balm-set',
                'description' => 'Moisturizing lip balm set with natural ingredients.',
                'short_description' => 'Nourishing lip care',
                'sku' => 'NLBS',
                'price' => 25.00,
                'compare_price' => 35.00,
                'cost_price' => 10.00,
                'stock_quantity' => 200,
                'is_featured' => false,
                'is_active' => true,
                'category_id' => $beautyCategory?->id,
                'brand_id' => null,
                'attributes' => [
                    ['attribute_id' => Attribute::where('slug', 'flavor')->first()?->id, 'values' => ['Original', 'Mint', 'Berry', 'Vanilla']],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $attributes = $productData['attributes'] ?? [];
            unset($productData['attributes']);

            $product = Product::create($productData);

            // Create product images (placeholder URLs)
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => 'https://placehold.co/800x800/f5f5f5/333333?text=' . urlencode($product->name),
                'alt_text' => $product->name,
                'sort_order' => 1,
                'is_primary' => true,
            ]);

            // Create product attributes
            foreach ($attributes as $attrData) {
                $attributeValues = AttributeValue::whereIn('value', $attrData['values'])
                    ->where('attribute_id', $attrData['attribute_id'])
                    ->get();

                foreach ($attributeValues as $attributeValue) {
                    ProductAttribute::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'attribute_id' => $attrData['attribute_id'],
                            'attribute_value_id' => $attributeValue->id,
                        ]
                    );
                }
            }

            // Generate variations if product has attributes
            if (!empty($attributes)) {
                $this->generateVariations($product, $attributes);
            }
        }
    }

    /**
     * Generate all possible variations for a product based on its attributes.
     */
    private function generateVariations(Product $product, array $attributes): void
    {
        $attributeCombinations = $this->generateCombinations($attributes);

        foreach ($attributeCombinations as $combination) {
            $variationName = $product->name;
            $skuSuffix = '';
            $priceAdjustment = 0;

            foreach ($combination as $attributeId => $valueId) {
                $attribute = Attribute::find($attributeId);
                $value = AttributeValue::find($valueId);

                if ($attribute && $value) {
                    $variationName .= ' - ' . $value->value;
                    $skuSuffix .= '-' . strtoupper(substr($value->value, 0, 3));
                }
            }

            $variation = ProductVariation::create([
                'product_id' => $product->id,
                'sku' => $product->sku . $skuSuffix,
                'price' => $product->price + $priceAdjustment,
                'compare_price' => $product->compare_price + $priceAdjustment,
                'stock_quantity' => max(10, intdiv($product->stock_quantity, count($attributeCombinations))),
                'is_active' => true,
            ]);

            // Link attribute values to variation
            foreach ($combination as $attributeId => $valueId) {
                VariationAttributeValue::create([
                    'product_variation_id' => $variation->id,
                    'attribute_id' => $attributeId,
                    'attribute_value_id' => $valueId,
                ]);
            }
        }
    }

    /**
     * Generate all possible combinations of attribute values.
     */
    private function generateCombinations(array $attributes, $index = 0, $current = []): array
    {
        if ($index === count($attributes)) {
            return [$current];
        }

        $combinations = [];
        $attribute = $attributes[$index];
        $attributeId = $attribute['attribute_id'];

        $values = AttributeValue::whereIn('value', $attribute['values'])
            ->where('attribute_id', $attributeId)
            ->get()
            ->pluck('id')
            ->toArray();

        foreach ($values as $valueId) {
            $newCurrent = array_merge($current, [$attributeId => $valueId]);
            $combinations = array_merge(
                $combinations,
                $this->generateCombinations($attributes, $index + 1, $newCurrent)
            );
        }

        return $combinations;
    }
}
