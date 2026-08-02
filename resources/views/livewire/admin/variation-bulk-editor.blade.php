<div class="bg-white border border-gray-200 rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Product Variations</h3>
        <div class="flex gap-2">
            <button 
                type="button"
                wire:click="toggleEdit"
                class="{{ $isEditing ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }} font-medium py-2 px-4 rounded-lg transition-colors"
            >
                {{ $isEditing ? 'Done Editing' : 'Edit Variations' }}
            </button>
            @if($isEditing)
                <button 
                    type="button"
                    wire:click="saveAll"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    Save Changes
                </button>
            @endif
        </div>
    </div>

    @if(count($variations) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attributes</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compare Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        @if($isEditing)
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($variations as $index => $variation)
                        <tr class="{{ $isEditing ? 'hover:bg-gray-50' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($variation['attributes'] as $attr)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $attr['attribute_name'] }}: {{ $attr['value_name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($isEditing)
                                    <input 
                                        type="text"
                                        wire:model="variations.{{ $index }}.sku"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                @else
                                    <span class="text-sm text-gray-900">{{ $variation['sku'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($isEditing)
                                    <input 
                                        type="number"
                                        step="0.01"
                                        wire:model="variations.{{ $index }}.price"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                @else
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($variation['price'], 2) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($isEditing)
                                    <input 
                                        type="number"
                                        step="0.01"
                                        wire:model="variations.{{ $index }}.compare_price"
                                        class="w-24 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="0.00"
                                    >
                                @else
                                    @if($variation['compare_price'] > 0)
                                        <span class="text-sm text-gray-500 line-through">${{ number_format($variation['compare_price'], 2) }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($isEditing)
                                    <input 
                                        type="number"
                                        wire:model="variations.{{ $index }}.stock"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                                    >
                                @else
                                    <span class="text-sm text-gray-900">{{ $variation['stock'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($isEditing)
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox"
                                            wire:model="variations.{{ $index }}.is_active"
                                            class="sr-only peer"
                                        >
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $variation['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $variation['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            @if($isEditing)
                                <td class="px-4 py-3">
                                    <button 
                                        type="button"
                                        wire:click="deleteVariation({{ $variation['id'] }})"
                                        class="text-red-600 hover:text-red-900"
                                        title="Delete variation"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No variations</h3>
            <p class="mt-1 text-sm text-gray-500">Use the variation generator to create product variations.</p>
        </div>
    @endif
</div>
