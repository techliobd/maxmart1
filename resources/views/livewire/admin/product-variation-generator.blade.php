<div class="space-y-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Generate Product Variations</h3>
        
        @if(count($attributeValues) > 0)
            <p class="text-sm text-gray-600 mb-4">
                Select attribute values to generate all possible combinations. Existing variations will be preserved.
            </p>

            <!-- Attribute Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($attributeValues as $attributeId => $values)
                    @php
                        $attribute = \App\Models\Attribute::find($attributeId);
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-3">{{ $attribute->name }}</h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($values as $valueName => $valueId)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input 
                                        type="checkbox"
                                        wire:click="toggleAttributeValue({{ $attributeId }}, {{ $valueId }})"
                                        {{ isset($selectedAttributes[$attributeId]) && in_array($valueId, $selectedAttributes[$attributeId]) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    >
                                    <span class="text-sm text-gray-700">{{ $valueName }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Generate Button -->
            <div class="flex gap-3">
                <button 
                    type="button"
                    wire:click="generatePreview"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                >
                    Preview Variations ({{ count($this->generateCombinations($selectedAttributes)) ?? 0 }} combinations)
                </button>
                <button 
                    type="button"
                    wire:click="$set('selectedAttributes', {})"
                    class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                >
                    Clear Selection
                </button>
            </div>

            <!-- Preview Table -->
            @if($showPreview && count($generatedVariations) > 0)
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Preview Generated Variations</h4>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Combination</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($generatedVariations as $index => $variation)
                                    <tr class="{{ $variation['exists'] ? 'bg-gray-50' : '' }}">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @foreach($variation['attribute_combination'] as $attrId => $valId)
                                                @php
                                                    $val = \App\Models\AttributeValue::find($valId);
                                                @endphp
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                                    {{ $val?->value }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3">
                                            <input 
                                                type="text"
                                                wire:model="generatedVariations.{{ $index }}.sku"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Auto-generated"
                                            >
                                        </td>
                                        <td class="px-4 py-3">
                                            <input 
                                                type="number"
                                                step="0.01"
                                                wire:model="generatedVariations.{{ $index }}.price"
                                                class="w-24 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                            >
                                        </td>
                                        <td class="px-4 py-3">
                                            <input 
                                                type="number"
                                                wire:model="generatedVariations.{{ $index }}.stock"
                                                class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                            >
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($variation['exists'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Exists
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    New
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-4 flex gap-3">
                        <button 
                            type="button"
                            wire:click="saveVariations"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors"
                        >
                            Save All Variations
                        </button>
                        <button 
                            type="button"
                            wire:click="$set('showPreview', false)"
                            class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No attributes available</h3>
                <p class="mt-1 text-sm text-gray-500">Create product attributes first before generating variations.</p>
            </div>
        @endif
    </div>
</div>
