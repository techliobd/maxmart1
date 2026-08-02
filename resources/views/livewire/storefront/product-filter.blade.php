<div class="space-y-6">
    <!-- Mobile Filter Toggle -->
    <div class="lg:hidden">
        <button 
            type="button"
            @click="$dispatch('toggle-mobile-filters')"
            class="w-full flex items-center justify-between px-4 py-3 bg-gray-100 rounded-lg"
        >
            <span class="font-medium text-gray-900">Filters</span>
            @if($activeFiltersCount > 0)
                <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $activeFiltersCount }}
                </span>
            @endif
        </button>
    </div>

    <!-- Price Range -->
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Price Range</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <input 
                    type="number" 
                    wire:model.live.debounce.500ms="priceRange.min"
                    wire:change="updatePriceRange"
                    min="{{ $minPrice }}"
                    max="{{ $maxPrice }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Min"
                >
                <span class="text-gray-500">-</span>
                <input 
                    type="number" 
                    wire:model.live.debounce.500ms="priceRange.max"
                    wire:change="updatePriceRange"
                    min="{{ $minPrice }}"
                    max="{{ $maxPrice }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Max"
                >
            </div>
            <div class="relative pt-2">
                <input 
                    type="range" 
                    min="{{ $minPrice }}" 
                    max="{{ $maxPrice }}" 
                    wire:model.live.debounce.500ms="priceRange.min"
                    wire:change="updatePriceRange"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                >
                <input 
                    type="range" 
                    min="{{ $minPrice }}" 
                    max="{{ $maxPrice }}" 
                    wire:model.live.debounce.500ms="priceRange.max"
                    wire:change="updatePriceRange"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer -mt-2"
                >
            </div>
            <p class="text-sm text-gray-600">
                ${{ number_format($priceRange['min']) }} - ${{ number_format($priceRange['max']) }}
            </p>
        </div>
    </div>

    <!-- Categories -->
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Categories</h3>
        <div class="space-y-2">
            @foreach($categories as $category)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input 
                        type="checkbox" 
                        wire:click="toggleCategory({{ $category['id'] }})"
                        {{ in_array($category['id'], $selectedCategories) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $category['name'] }}</span>
                    @if(isset($category['children']) && count($category['children']) > 0)
                        <span class="text-xs text-gray-400">({{ count($category['children']) }})</span>
                    @endif
                </label>
            @endforeach
        </div>
    </div>

    <!-- Brands -->
    <div class="border-b border-gray-200 pb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Brands</h3>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($brands as $brand)
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input 
                        type="checkbox" 
                        wire:click="toggleBrand({{ $brand['id'] }})"
                        {{ in_array($brand['id'], $selectedBrands) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $brand['name'] }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Attributes -->
    @foreach($attributes as $attribute)
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ $attribute['name'] }}</h3>
            <div class="space-y-2">
                @foreach($attribute['values'] as $value)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            wire:click="toggleAttribute({{ $attribute['id'] }}, {{ $value['id'] }})"
                            {{ isset($selectedAttributes[$attribute['id']]) && in_array($value['id'], $selectedAttributes[$attribute['id']]) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $value['value'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Clear All -->
    @if($activeFiltersCount > 0)
        <button 
            type="button"
            wire:click="clearAll"
            class="w-full py-2 text-sm text-red-600 hover:text-red-700 font-medium border border-red-200 rounded-lg hover:bg-red-50 transition-colors"
        >
            Clear All Filters ({{ $activeFiltersCount }})
        </button>
    @endif
</div>
