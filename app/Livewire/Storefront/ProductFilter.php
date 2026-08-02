<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Support\Facades\Request;

class ProductFilter extends Component
{
    public array $categories = [];
    public array $brands = [];
    public array $attributes = [];
    public array $selectedCategories = [];
    public array $selectedBrands = [];
    public array $selectedAttributes = [];
    public array $priceRange = ['min' => 0, 'max' => 1000];
    public float $minPrice = 0;
    public float $maxPrice = 1000;
    public string $sortBy = 'featured';
    public int $perPage = 12;

    protected $listeners = ['filterUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->categories = Category::where('is_active', true)
            ->with('children')
            ->orderBy('name')
            ->get()
            ->toArray();

        $this->brands = Brand::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->toArray();

        $this->attributes = Attribute::with('values')
            ->whereHas('values')
            ->orderBy('name')
            ->get()
            ->toArray();

        // Get price range from products
        $priceStats = Product::query()
            ->where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        if ($priceStats) {
            $this->minPrice = floor($priceStats->min_price ?? 0);
            $this->maxPrice = ceil($priceStats->max_price ?? 1000);
            $this->priceRange = ['min' => $this->minPrice, 'max' => $this->maxPrice];
        }
    }

    public function toggleCategory(int $categoryId): void
    {
        $index = array_search($categoryId, $this->selectedCategories);
        if ($index !== false) {
            unset($this->selectedCategories[$index]);
        } else {
            $this->selectedCategories[] = $categoryId;
        }
        $this->dispatch('filtersChanged');
    }

    public function toggleBrand(int $brandId): void
    {
        $index = array_search($brandId, $this->selectedBrands);
        if ($index !== false) {
            unset($this->selectedBrands[$index]);
        } else {
            $this->selectedBrands[] = $brandId;
        }
        $this->dispatch('filtersChanged');
    }

    public function toggleAttribute(int $attributeId, int $valueId): void
    {
        if (!isset($this->selectedAttributes[$attributeId])) {
            $this->selectedAttributes[$attributeId] = [];
        }

        $index = array_search($valueId, $this->selectedAttributes[$attributeId]);
        if ($index !== false) {
            unset($this->selectedAttributes[$attributeId][$index]);
        } else {
            $this->selectedAttributes[$attributeId][] = $valueId;
        }
        $this->dispatch('filtersChanged');
    }

    public function updatePriceRange(): void
    {
        $this->dispatch('filtersChanged', 
            priceMin: $this->priceRange['min'],
            priceMax: $this->priceRange['max']
        );
    }

    public function updateSort(string $sort): void
    {
        $this->sortBy = $sort;
        $this->dispatch('filtersChanged', sort: $sort);
    }

    public function updatePerPage(int $count): void
    {
        $this->perPage = $count;
        $this->dispatch('filtersChanged', perPage: $count);
    }

    public function clearAll(): void
    {
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->selectedAttributes = [];
        $this->priceRange = ['min' => $this->minPrice, 'max' => $this->maxPrice];
        $this->sortBy = 'featured';
        $this->dispatch('filtersChanged', clearAll: true);
    }

    public function getActiveFiltersCountAttribute(): int
    {
        $count = count($this->selectedCategories) + count($this->selectedBrands);
        foreach ($this->selectedAttributes as $values) {
            $count += count($values);
        }
        return $count;
    }

    public function render()
    {
        return view('livewire.storefront.product-filter');
    }
}
