<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'usage_count',
        'usage_limit_per_user',
        'is_active',
        'starts_at',
        'expires_at',
        'apply_to_sale_items',
        'restriction_type',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'usage_limit_per_user' => 'integer',
            'is_active' => 'boolean',
            'apply_to_sale_items' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the products this coupon applies to.
     */
    public function products(): HasMany
    {
        return $this->hasMany(CouponProduct::class);
    }

    /**
     * Get the categories this coupon applies to.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(CouponCategory::class);
    }

    /**
     * Get user usage records.
     */
    public function userUsages(): HasMany
    {
        return $this->hasMany(CouponUser::class);
    }

    /**
     * Check if coupon is valid for a cart.
     */
    public function isValid(Cart $cart): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check date range
        $now = now();
        if ($this->starts_at && $now < $this->starts_at) {
            return false;
        }
        if ($this->expires_at && $now > $this->expires_at) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        // Check minimum order amount
        if ($this->min_order_amount && $cart->subtotal < $this->min_order_amount) {
            return false;
        }

        // Check sale items restriction
        if (!$this->apply_to_sale_items) {
            foreach ($cart->items as $item) {
                if ($item->product?->is_on_sale) {
                    return false;
                }
            }
        }

        // Check product restrictions
        if ($this->restriction_type === 'specific_products') {
            $allowedProductIds = $this->products()->pluck('product_id');
            foreach ($cart->items as $item) {
                if (!$allowedProductIds->contains($item->product_id)) {
                    return false;
                }
            }
        }

        if ($this->restriction_type === 'exclude_products') {
            $excludedProductIds = $this->products()->pluck('product_id');
            foreach ($cart->items as $item) {
                if ($excludedProductIds->contains($item->product_id)) {
                    return false;
                }
            }
        }

        // Check category restrictions
        if ($this->restriction_type === 'specific_categories') {
            $allowedCategoryIds = $this->categories()->pluck('category_id');
            foreach ($cart->items as $item) {
                if (!$allowedCategoryIds->contains($item->product?->category_id)) {
                    return false;
                }
            }
        }

        // Check per-user limit
        if (auth()->check() && $this->usage_limit_per_user) {
            $userUsage = $this->userUsages()
                ->where('user_id', auth()->id())
                ->first();
            
            if ($userUsage && $userUsage->usage_count >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount(float $subtotal, Cart $cart): float
    {
        $discount = 0;

        switch ($this->type) {
            case 'percent':
                $discount = ($subtotal * $this->value) / 100;
                if ($this->max_discount && $discount > $this->max_discount) {
                    $discount = $this->max_discount;
                }
                break;

            case 'fixed':
                $discount = $this->value;
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
                break;

            case 'free_shipping':
                $discount = $cart->shipping_cost;
                break;
        }

        return min($discount, $subtotal);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(?int $userId = null): void
    {
        $this->increment('usage_count');

        if ($userId) {
            CouponUser::updateOrCreate(
                ['coupon_id' => $this->id, 'user_id' => $userId],
                ['usage_count' => \DB::raw('usage_count + 1')]
            );
        }
    }

    /**
     * Scope for active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for currently valid coupons (within date range).
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('starts_at')
              ->orWhere('starts_at', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now());
        });
    }
}
