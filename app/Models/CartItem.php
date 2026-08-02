<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'price',
        'discount',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
            'options' => 'array',
        ];
    }

    /**
     * Get the cart this item belongs to.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product for this item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation for this item.
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * Get the total for this item.
     */
    public function getTotalAttribute(): float
    {
        return ($this->price - $this->discount) * $this->quantity;
    }

    /**
     * Get the display price (considering variation price).
     */
    public function getDisplayPriceAttribute(): float
    {
        if ($this->variation) {
            return $this->variation->price;
        }
        return $this->product?->price ?? $this->price;
    }

    /**
     * Get the product name with variation details.
     */
    public function getFullProductNameAttribute(): string
    {
        $name = $this->product?->name ?? 'Unknown Product';

        if ($this->variation && $this->options) {
            $options = [];
            foreach ($this->options as $option) {
                $options[] = $option['value'] ?? $option;
            }
            if (!empty($options)) {
                $name .= ' (' . implode(', ', $options) . ')';
            }
        }

        return $name;
    }

    /**
     * Check if the item is available in stock.
     */
    public function isAvailable(): bool
    {
        if ($this->variation) {
            return $this->variation->isInStock() && 
                   ($this->variation->stock_quantity >= $this->quantity || 
                    $this->product?->allow_backorder);
        }

        return $this->product?->isAvailable($this->quantity) ?? false;
    }

    /**
     * Update quantity.
     */
    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            $this->delete();
        } else {
            $this->update(['quantity' => $quantity]);
            $this->cart?->recalculate();
        }
    }

    /**
     * Increment quantity.
     */
    public function incrementQuantity(int $amount = 1): void
    {
        $this->updateQuantity($this->quantity + $amount);
    }

    /**
     * Decrement quantity.
     */
    public function decrementQuantity(int $amount = 1): void
    {
        $this->updateQuantity($this->quantity - $amount);
    }
}
