<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'variation_id',
    ];

    /**
     * Get the customer this wishlist belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product for this wishlist item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation for this wishlist item.
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * Check if the product is in stock.
     */
    public function isInStockAttribute(): bool
    {
        if ($this->variation) {
            return $this->variation->isInStock();
        }
        return $this->product?->isAvailable() ?? false;
    }

    /**
     * Move to cart.
     */
    public function moveToCart(int $quantity = 1): void
    {
        $cart = Cart::firstOrCreate(
            ['customer_id' => $this->customer_id],
            ['session_id' => session()->getId()]
        );

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $this->product_id,
                'variation_id' => $this->variation_id,
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $quantity),
                'price' => $this->variation?->price ?? $this->product?->price ?? 0,
                'options' => $this->variation?->attribute_options ?? null,
            ]
        );

        $cart->recalculate();
        $this->delete();
    }
}
