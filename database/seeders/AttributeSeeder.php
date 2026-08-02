<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Orange', 'Purple', 'Pink', 'Gray', 'Silver', 'Gold', 'Brown', 'Navy', 'Beige'],
            ],
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            ],
            [
                'name' => 'RAM',
                'slug' => 'ram',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['4GB', '6GB', '8GB', '12GB', '16GB', '32GB', '64GB'],
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB', '2TB'],
            ],
            [
                'name' => 'Screen Size',
                'slug' => 'screen-size',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['5.5 inches', '6.1 inches', '6.5 inches', '6.7 inches', '13 inches', '14 inches', '15.6 inches', '16 inches', '24 inches', '27 inches', '32 inches'],
            ],
            [
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['Cotton', 'Polyester', 'Leather', 'Denim', 'Silk', 'Wool', 'Linen', 'Nylon', 'Canvas', 'Metal', 'Plastic', 'Wood', 'Glass'],
            ],
            [
                'name' => 'Weight',
                'slug' => 'weight',
                'type' => 'text',
                'is_filterable' => false,
                'is_visible_on_product' => true,
                'values' => [],
            ],
            [
                'name' => 'Flavor',
                'slug' => 'flavor',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['Original', 'Chocolate', 'Vanilla', 'Strawberry', 'Mint', 'Lemon', 'Orange', 'Apple', 'Grape', 'Watermelon'],
            ],
            [
                'name' => 'Connectivity',
                'slug' => 'connectivity',
                'type' => 'multiselect',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['WiFi', 'Bluetooth', 'USB-C', 'USB 3.0', 'HDMI', 'Ethernet', 'NFC', '5G', '4G LTE'],
            ],
            [
                'name' => 'Battery Life',
                'slug' => 'battery-life',
                'type' => 'select',
                'is_filterable' => true,
                'is_visible_on_product' => true,
                'values' => ['Up to 8 hours', 'Up to 12 hours', 'Up to 16 hours', 'Up to 20 hours', 'Up to 24 hours'],
            ],
        ];

        foreach ($attributes as $attributeData) {
            $values = $attributeData['values'];
            unset($attributeData['values']);

            $attribute = Attribute::updateOrCreate(
                ['slug' => $attributeData['slug']],
                $attributeData
            );

            foreach ($values as $valueName) {
                AttributeValue::updateOrCreate(
                    [
                        'attribute_id' => $attribute->id,
                        'value' => $valueName,
                    ],
                    ['slug' => strtolower(str_replace(' ', '-', $valueName))]
                );
            }
        }
    }
}
