<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    protected ?Cart $cart = null;

    public function __construct()
    {
        $this->cart = $this->getOrCreateCart();
    }

    /**
     * Get the current cart or create a new one
     */
    public function getOrCreateCart(): Cart
    {
        if ($this->cart) {
            return $this->cart;
        }

        $sessionId = Session::getId();
        
        if (Auth::check()) {
            $this->cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => $sessionId]
            );
        } else {
            $this->cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        return $this->cart;
    }

    /**
     * Add item to cart
     */
    public function addItem(int $productId, int $variationId, int $quantity = 1): CartItem
    {
        $product = Product::findOrFail($productId);
        
        if (!$product->isAvailable()) {
            throw new \Exception('Product is not available for purchase');
        }

        $variation = null;
        if ($variationId) {
            $variation = ProductVariation::findOrFail($variationId);
            
            if (!$variation->isAvailable()) {
                throw new \Exception('Selected variation is not available');
            }
        }

        $unitPrice = $variation ? $variation->price : $product->price;
        $salePrice = $this->calculateSalePrice($unitPrice, $product, $variation);

        $cartItem = $this->cart->items()->where([
            'product_id' => $productId,
            'variation_id' => $variationId ?: null,
        ])->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $this->cart->id,
                'product_id' => $productId,
                'variation_id' => $variationId ?: null,
                'quantity' => $quantity,
                'unit_price' => $salePrice,
                'original_price' => $unitPrice,
            ]);
        }

        return $cartItem->fresh();
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $cartItemId, int $quantity): CartItem
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($quantity <= 0) {
            $cartItem->delete();
            throw new \Exception('Item removed from cart');
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return $cartItem->fresh();
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $cartItemId): bool
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        if ($cartItem->cart_id !== $this->cart->id) {
            throw new \Exception('Item does not belong to this cart');
        }

        return $cartItem->delete();
    }

    /**
     * Clear entire cart
     */
    public function clearCart(): bool
    {
        return $this->cart->items()->delete();
    }

    /**
     * Get cart items with product details
     */
    public function getCartItems(): Collection
    {
        return $this->cart->items()->with(['product', 'variation', 'product.images'])->get();
    }

    /**
     * Calculate cart subtotal
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        
        foreach ($this->cart->items as $item) {
            $subtotal += $item->unit_price * $item->quantity;
        }

        return round($subtotal, 2);
    }

    /**
     * Get total item count in cart
     */
    public function getItemCount(): int
    {
        return $this->cart->items()->sum('quantity');
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(string $code): array
    {
        $couponService = app(CouponService::class);
        return $couponService->applyToCart($this->cart, $code);
    }

    /**
     * Calculate sale price based on product/variation discounts
     */
    protected function calculateSalePrice(float $basePrice, Product $product, ?ProductVariation $variation): float
    {
        $price = $basePrice;

        // Check for flash sale
        if ($product->flashSale && $product->flashSale->isActive()) {
            $price = $product->flashSale->discount_type === 'percentage'
                ? $basePrice * (1 - $product->flashSale->discount_value / 100)
                : $basePrice - $product->flashSale->discount_value;
        }

        // Check for product-specific discount
        if ($product->discount_type === 'percentage' && $product->discount_value > 0) {
            $price = $basePrice * (1 - $product->discount_value / 100);
        } elseif ($product->discount_type === 'fixed' && $product->discount_value > 0) {
            $price = $basePrice - $product->discount_value;
        }

        return max(0, round($price, 2));
    }

    /**
     * Merge guest cart with user cart on login
     */
    public function mergeCarts(int $userId): void
    {
        $guestCart = Cart::where('session_id', Session::getId())
            ->whereNull('user_id')
            ->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::where('user_id', $userId)->first();

        if (!$userCart) {
            $guestCart->update(['user_id' => $userId]);
            return;
        }

        // Merge items
        foreach ($guestCart->items as $guestItem) {
            $existingItem = $userCart->items()->where([
                'product_id' => $guestItem->product_id,
                'variation_id' => $guestItem->variation_id,
            ])->first();

            if ($existingItem) {
                $existingItem->quantity += $guestItem->quantity;
                $existingItem->save();
                $guestItem->delete();
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    /**
     * Get the cart model
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * Check if cart has enough stock for all items
     */
    public function validateStock(): array
    {
        $issues = [];

        foreach ($this->cart->items as $item) {
            $product = $item->product;
            $variation = $item->variation;

            if ($variation) {
                if ($variation->stock_quantity < $item->quantity) {
                    $issues[] = [
                        'item_id' => $item->id,
                        'product_name' => $product->name,
                        'variation_name' => $variation->name,
                        'available' => $variation->stock_quantity,
                        'requested' => $item->quantity,
                    ];
                }
            } else {
                if ($product->stock_quantity < $item->quantity) {
                    $issues[] = [
                        'item_id' => $item->id,
                        'product_name' => $product->name,
                        'available' => $product->stock_quantity,
                        'requested' => $item->quantity,
                    ];
                }
            }
        }

        return $issues;
    }
}
