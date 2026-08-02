<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            // Welcome coupon for new customers
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off for new customers',
                'type' => 'percentage',
                'value' => 10,
                'minimum_purchase' => 50.00,
                'maximum_discount' => 50.00,
                'usage_limit' => null,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(365),
                'is_active' => true,
                'applicable_to' => 'all',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // Summer sale
            [
                'code' => 'SUMMER25',
                'name' => 'Summer Sale',
                'description' => '25% off summer collection',
                'type' => 'percentage',
                'value' => 25,
                'minimum_purchase' => 100.00,
                'maximum_discount' => 100.00,
                'usage_limit' => 1000,
                'usage_limit_per_user' => 3,
                'starts_at' => now(),
                'expires_at' => now()->addDays(60),
                'is_active' => true,
                'applicable_to' => 'categories',
                'category_ids' => [], // Will be set dynamically
                'product_ids' => [],
                'user_ids' => [],
            ],
            // Fixed amount discount
            [
                'code' => 'SAVE20',
                'name' => '$20 Off',
                'description' => 'Get $20 off your order',
                'type' => 'fixed',
                'value' => 20,
                'minimum_purchase' => 150.00,
                'maximum_discount' => 20.00,
                'usage_limit' => 500,
                'usage_limit_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'is_active' => true,
                'applicable_to' => 'all',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // Free shipping coupon
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Free shipping on any order',
                'type' => 'free_shipping',
                'value' => 0,
                'minimum_purchase' => 75.00,
                'maximum_discount' => null,
                'usage_limit' => null,
                'usage_limit_per_user' => 5,
                'starts_at' => now(),
                'expires_at' => now()->addDays(90),
                'is_active' => true,
                'applicable_to' => 'all',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // First purchase bonus
            [
                'code' => 'FIRSTBUY',
                'name' => 'First Purchase Bonus',
                'description' => 'Special discount for first-time buyers',
                'type' => 'percentage',
                'value' => 15,
                'minimum_purchase' => 30.00,
                'maximum_discount' => 75.00,
                'usage_limit' => null,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(180),
                'is_active' => true,
                'applicable_to' => 'all',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // Electronics flash deal
            [
                'code' => 'TECH15',
                'name' => 'Tech Flash Deal',
                'description' => '15% off electronics',
                'type' => 'percentage',
                'value' => 15,
                'minimum_purchase' => 200.00,
                'maximum_discount' => 150.00,
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(7),
                'is_active' => true,
                'applicable_to' => 'categories',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // VIP customer exclusive
            [
                'code' => 'VIP30',
                'name' => 'VIP Exclusive',
                'description' => '30% off for VIP members',
                'type' => 'percentage',
                'value' => 30,
                'minimum_purchase' => 0,
                'maximum_discount' => 200.00,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addDays(14),
                'is_active' => true,
                'applicable_to' => 'users',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
            // Clearance sale
            [
                'code' => 'CLEARANCE50',
                'name' => 'Clearance Sale',
                'description' => '50% off clearance items',
                'type' => 'percentage',
                'value' => 50,
                'minimum_purchase' => 0,
                'maximum_discount' => 500.00,
                'usage_limit' => null,
                'usage_limit_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addDays(45),
                'is_active' => false, // Inactive until needed
                'applicable_to' => 'products',
                'category_ids' => [],
                'product_ids' => [],
                'user_ids' => [],
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::updateOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }
    }
}
