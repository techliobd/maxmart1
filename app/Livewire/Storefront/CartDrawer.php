<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartDrawer extends Component
{
    public bool $isOpen = false;
    public array $cartItems = [];
    public float $subtotal = 0;
    public float $tax = 0;
    public float $total = 0;

    protected $listeners = [
        'openCartDrawer' => 'open',
        'closeCartDrawer' => 'close',
        'cartUpdated' => 'updateCart',
        'addToCart' => 'handleAddToCart',
    ];

    public function mount(): void
    {
        $this->updateCart();
    }

    public function open(): void
    {
        $this->isOpen = true;
        $this->dispatch('bodyScrollDisable');
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->dispatch('bodyScrollEnable');
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function handleAddToCart(int $productId, ?int $variationId = null, int $quantity = 1): void
    {
        $cartService = app(CartService::class);
        
        try {
            $cartService->addItem($productId, $variationId, $quantity);
            $this->updateCart();
            $this->open();
            $this->dispatch('showSuccess', message: 'Item added to cart successfully!');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: $e->getMessage());
        }
    }

    public function updateCart(): void
    {
        $cartService = app(CartService::class);
        $cart = $cartService->getCart();
        
        $this->cartItems = $cart->items ?? [];
        $this->subtotal = $cart->subtotal ?? 0;
        $this->tax = $cart->tax ?? 0;
        $this->total = $cart->total ?? 0;
    }

    public function updateQuantity(int $cartItemId, int $quantity): void
    {
        $cartService = app(CartService::class);
        
        try {
            if ($quantity <= 0) {
                $this->removeItem($cartItemId);
                return;
            }
            
            $cartService->updateQuantity($cartItemId, $quantity);
            $this->updateCart();
            $this->dispatch('cartUpdated');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: $e->getMessage());
        }
    }

    public function removeItem(int $cartItemId): void
    {
        $cartService = app(CartService::class);
        
        try {
            $cartService->removeItem($cartItemId);
            $this->updateCart();
            $this->dispatch('cartUpdated');
            $this->dispatch('showSuccess', message: 'Item removed from cart');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: $e->getMessage());
        }
    }

    public function clearCart(): void
    {
        $cartService = app(CartService::class);
        
        try {
            $cartService->clear();
            $this->updateCart();
            $this->dispatch('cartUpdated');
            $this->dispatch('showSuccess', message: 'Cart cleared');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: $e->getMessage());
        }
    }

    public function getCartCountAttribute(): int
    {
        return collect($this->cartItems)->sum('quantity') ?? 0;
    }

    public function render()
    {
        return view('livewire.storefront.cart-drawer');
    }
}
