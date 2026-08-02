<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Cart;
use Carbon\Carbon;

class CouponService
{
    /**
     * Validate and apply coupon to cart
     */
    public function applyToCart(Cart $cart, string $code): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code'];
        }

        // Check if coupon is active
        if (!$coupon->is_active) {
            return ['success' => false, 'message' => 'This coupon has been deactivated'];
        }

        // Check date range
        $now = Carbon::now();
        if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
            return ['success' => false, 'message' => 'This coupon is not yet valid'];
        }
        if ($coupon->valid_until && $now->gt($coupon->valid_until)) {
            return ['success' => false, 'message' => 'This coupon has expired'];
        }

        // Check usage limits
        if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit'];
        }

        // Check per-user usage limit
        if ($coupon->usage_limit_per_user && auth()->check()) {
            $userUsage = $coupon->orders()->where('user_id', auth()->id())->count();
            if ($userUsage >= $coupon->usage_limit_per_user) {
                return ['success' => false, 'message' => 'You have reached the usage limit for this coupon'];
            }
        }

        // Check minimum cart value
        $subtotal = $cart->items()->sum(fn($item) => $item->unit_price * $item->quantity);
        if ($coupon->minimum_cart_value && $subtotal < $coupon->minimum_cart_value) {
            return [
                'success' => false,
                'message' => sprintf('Minimum cart value of %s required', $coupon->minimum_cart_value)
            ];
        }

        // Check product restrictions
        if ($coupon->productRestrictions()->count() > 0) {
            $cartProductIds = $cart->items()->pluck('product_id')->toArray();
            $restrictedProductIds = $coupon->productRestrictions()->pluck('product_id')->toArray();
            
            if ($coupon->restriction_type === 'exclude') {
                if (array_intersect($cartProductIds, $restrictedProductIds)) {
                    return ['success' => false, 'message' => 'Coupon cannot be applied to items in your cart'];
                }
            } elseif ($coupon->restriction_type === 'include') {
                if (!array_intersect($cartProductIds, $restrictedProductIds)) {
                    return ['success' => false, 'message' => 'Coupon only applies to specific products'];
                }
            }
        }

        // Check category restrictions
        if ($coupon->categoryRestrictions()->count() > 0) {
            $cartProductIds = $cart->items()->with('product.category')->get()->pluck('product.category.id')->unique()->toArray();
            $restrictedCategoryIds = $coupon->categoryRestrictions()->pluck('category_id')->toArray();
            
            if ($coupon->category_restriction_type === 'exclude') {
                if (array_intersect($cartProductIds, $restrictedCategoryIds)) {
                    return ['success' => false, 'message' => 'Coupon cannot be applied to categories in your cart'];
                }
            } elseif ($coupon->category_restriction_type === 'include') {
                if (!array_intersect($cartProductIds, $restrictedCategoryIds)) {
                    return ['success' => false, 'message' => 'Coupon only applies to specific categories'];
                }
            }
        }

        // Calculate discount amount
        $discountAmount = $this->calculateDiscount($coupon, $subtotal);

        return [
            'success' => true,
            'message' => 'Coupon applied successfully',
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
        ];
    }

    /**
     * Calculate discount amount based on coupon type
     */
    protected function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($coupon->discount_type === 'percentage') {
            $discount = $subtotal * ($coupon->discount_value / 100);
            
            // Apply maximum discount cap if set
            if ($coupon->maximum_discount) {
                $discount = min($discount, $coupon->maximum_discount);
            }
        } else {
            // Fixed discount
            $discount = $coupon->discount_value;
        }

        return min($discount, $subtotal); // Don't exceed subtotal
    }

    /**
     * Increment coupon usage count
     */
    public function incrementUsage(Coupon $coupon): void
    {
        $coupon->increment('times_used');
    }

    /**
     * Get all active coupons
     */
    public function getActiveCoupons(): array
    {
        return Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', Carbon::now());
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Generate unique coupon code
     */
    public function generateCode(string $prefix = '', int $length = 8): string
    {
        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
            $code = $prefix . $random;
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    /**
     * Validate coupon data before creation/update
     */
    public function validateCouponData(array $data): array
    {
        $errors = [];

        if (!empty($data['discount_value']) && $data['discount_value'] <= 0) {
            $errors[] = 'Discount value must be greater than 0';
        }

        if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
            $errors[] = 'Percentage discount cannot exceed 100%';
        }

        if (!empty($data['valid_from']) && !empty($data['valid_until'])) {
            if (Carbon::parse($data['valid_from'])->gt(Carbon::parse($data['valid_until']))) {
                $errors[] = 'Valid from date must be before valid until date';
            }
        }

        if (!empty($data['maximum_discount']) && $data['discount_type'] !== 'percentage') {
            $errors[] = 'Maximum discount only applies to percentage discounts';
        }

        return $errors;
    }

    /**
     * Get coupon statistics
     */
    public function getCouponStatistics(?int $couponId = null): array
    {
        $query = Coupon::query();
        
        if ($couponId) {
            $query->where('id', $couponId);
        }

        $coupons = $query->get();

        return [
            'total_coupons' => $coupons->count(),
            'active_coupons' => $coupons->where('is_active', true)->count(),
            'total_uses' => $coupons->sum('times_used'),
            'total_discount_given' => round($coupons->sum(fn($c) => $c->orders()->sum('discount_amount')), 2),
        ];
    }
}
