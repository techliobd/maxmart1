<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductVariationSelector extends Component
{
    public Product $product;
    public array $selectedAttributes = [];
    public ?ProductVariation $selectedVariation = null;
    public int $quantity = 1;

    protected $listeners = ['variationSelected' => 'updateVariation'];

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->initializeSelectedAttributes();
        $this->updateVariation();
    }

    public function initializeSelectedAttributes(): void
    {
        foreach ($this->product->attributes as $attribute) {
            $firstValue = $attribute->values->first();
            if ($firstValue) {
                $this->selectedAttributes[$attribute->id] = $firstValue->id;
            }
        }
    }

    public function selectAttribute(int $attributeId, int $valueId): void
    {
        $this->selectedAttributes[$attributeId] = $valueId;
        $this->updateVariation();
        $this->dispatch('variationSelected', variationId: $this->selectedVariation?->id);
    }

    public function updateVariation(): void
    {
        if (empty($this->selectedAttributes)) {
            $this->selectedVariation = null;
            return;
        }

        $variation = $this->product->variations()
            ->whereHas('variationAttributeValues', function ($query) {
                foreach ($this->selectedAttributes as $attributeId => $valueId) {
                    $query->where(function ($q) use ($attributeId, $valueId) {
                        $q->where('attribute_id', $attributeId)
                          ->where('attribute_value_id', $valueId);
                    });
                }
            })
            ->first();

        $this->selectedVariation = $variation;
    }

    public function incrementQuantity(): void
    {
        $maxStock = $this->selectedVariation?->stock ?? $this->product->stock;
        if ($this->quantity < $maxStock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        if (!$this->selectedVariation && $this->product->variations()->count() > 0) {
            $this->dispatch('showError', message: 'Please select all variations');
            return;
        }

        $this->dispatch('addToCart', 
            productId: $this->product->id,
            variationId: $this->selectedVariation?->id,
            quantity: $this->quantity
        );
    }

    public function getAvailablePriceAttribute(): float
    {
        if ($this->selectedVariation) {
            return $this->selectedVariation->price;
        }
        
        return $this->product->variations()->min('price') ?? $this->product->price;
    }

    public function getAvailableStockAttribute(): int
    {
        if ($this->selectedVariation) {
            return $this->selectedVariation->stock;
        }
        
        return $this->product->stock;
    }

    public function render()
    {
        return view('livewire.storefront.product-variation-selector');
    }
}
