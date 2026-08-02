<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Services\CartService;

class MiniCart extends Component
{
    public array $cartItems = [];
    public float $subtotal = 0;
    public int $itemCount = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCart',
        'addToCart' => 'handleAddToCart',
    ];

    public function mount(): void
    {
        $this->updateCart();
    }

    public function handleAddToCart(int $productId, ?int $variationId = null, int $quantity = 1): void
    {
        $cartService = app(CartService::class);
        
        try {
            $cartService->addItem($productId, $variationId, $quantity);
            $this->updateCart();
            $this->dispatch('showSuccess', message: 'Item added to cart!');
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
        $this->itemCount = collect($this->cartItems)->sum('quantity') ?? 0;
    }

    public function openDrawer(): void
    {
        $this->dispatch('openCartDrawer');
    }

    public function render()
    {
        return view('livewire.storefront.mini-cart');
    }
}
