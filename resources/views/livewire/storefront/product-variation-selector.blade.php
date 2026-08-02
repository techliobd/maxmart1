<div class="product-variation-selector">
    @if($product->variations()->count() > 0)
        <div class="space-y-4">
            @foreach($product->attributes as $attribute)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $attribute->name }}
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($attribute->values as $value)
                            <button
                                type="button"
                                wire:click="selectAttribute({{ $attribute->id }}, {{ $value->id }})"
                                class="px-4 py-2 border rounded-lg transition-all duration-200
                                    {{ isset($selectedAttributes[$attribute->id]) && $selectedAttributes[$attribute->id] == $value->id
                                        ? 'border-blue-600 bg-blue-50 text-blue-700'
                                        : 'border-gray-300 hover:border-gray-400' }}"
                            >
                                {{ $value->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($selectedVariation)
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">
                        Selected: 
                        @foreach($selectedAttributes as $attributeId => $valueId)
                            @php
                                $attr = $product->attributes->firstWhere('id', $attributeId);
                                $val = $attr?->values->firstWhere('id', $valueId);
                            @endphp
                            @if($val){{ $val->value }}@endif
                            @if(!$loop->last), @endif
                        @endforeach
                    </p>
                    <p class="text-green-700 mt-1">
                        Price: ${{ number_format($selectedVariation->price, 2) }} | 
                        Stock: {{ $selectedVariation->stock }}
                    </p>
                </div>
            @endif
        </div>
    @endif

    <div class="mt-6 flex items-center gap-4">
        <div class="flex items-center border border-gray-300 rounded-lg">
            <button
                type="button"
                wire:click="decrementQuantity"
                class="px-4 py-2 hover:bg-gray-100 transition-colors"
            >
                -
            </button>
            <span class="px-6 py-2 border-x border-gray-300">{{ $quantity }}</span>
            <button
                type="button"
                wire:click="incrementQuantity"
                class="px-4 py-2 hover:bg-gray-100 transition-colors"
            >
                +
            </button>
        </div>

        <button
            type="button"
            wire:click="addToCart"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
        >
            Add to Cart - ${{ number_format($availablePrice, 2) }}
        </button>
    </div>
</div>
