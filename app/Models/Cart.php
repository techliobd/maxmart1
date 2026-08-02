<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'session_id',
        'coupon_id',
        'subtotal',
        'discount',
        'shipping_cost',
        'tax',
        'total',
        'coupon_code',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Get the customer this cart belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items in this cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class)->with('product.variations');
    }

    /**
     * Get the applied coupon.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the total item count.
     */
    public function getItemCountAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }

    /**
     * Clear all items from cart.
     */
    public function clear(): void
    {
        $this->items()->delete();
        $this->update([
            'subtotal' => 0,
            'discount' => 0,
            'shipping_cost' => 0,
            'tax' => 0,
            'total' => 0,
            'coupon_id' => null,
            'coupon_code' => null,
        ]);
    }

    /**
     * Recalculate cart totals.
     */
    public function recalculate(): void
    {
        $subtotal = 0;
        
        foreach ($this->items as $item) {
            $subtotal += $item->total;
        }

        $discount = 0;
        if ($this->coupon) {
            $discount = $this->coupon->calculateDiscount($subtotal, $this);
        }

        $taxRate = Setting::get('tax_rate', 0);
        $tax = ($subtotal - $discount) * ($taxRate / 100);

        $total = $subtotal - $discount + $tax + $this->shipping_cost;

        $this->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    /**
     * Apply a coupon to the cart.
     */
    public function applyCoupon(string $code): bool
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->isValid($this)) {
            return false;
        }

        $this->update([
            'coupon_id' => $coupon->id,
            'coupon_code' => $code,
        ]);

        $this->recalculate();
        return true;
    }

    /**
     * Remove coupon from cart.
     */
    public function removeCoupon(): void
    {
        $this->update([
            'coupon_id' => null,
            'coupon_code' => null,
        ]);
        $this->recalculate();
    }
}
