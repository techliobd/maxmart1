<div class="space-y-6">
    @if($menu)
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Builder: {{ $menu->name }}</h3>

            <!-- Add New Item Form -->
            @if($editingItemId === null)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3">Add New Menu Item</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <input 
                                type="text"
                                wire:model="newItemLabel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., Home"
                            >
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input 
                                type="text"
                                wire:model="newItemUrl"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., / or /about"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Parent (Optional)</label>
                            <select 
                                wire:model="newItemParentId"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">-- Top Level --</option>
                                @foreach($menuItems as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button 
                            type="button"
                            wire:click="saveItem"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                        >
                            Add Item
                        </button>
                        @if(count($menuItems) > 0)
                            <button 
                                type="button"
                                wire:click="$set('newItemParentId', $rootMenuItemId ?? null)"
                                class="text-sm text-blue-600 hover:text-blue-700"
                            >
                                Add as child of existing item
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <!-- Edit Item Form -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-3">Edit Menu Item</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-blue-900 mb-1">Label</label>
                            <input 
                                type="text"
                                wire:model="newItemLabel"
                                class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-blue-900 mb-1">URL</label>
                            <input 
                                type="text"
                                wire:model="newItemUrl"
                                class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>
                        <div class="flex items-end gap-2">
                            <button 
                                type="button"
                                wire:click="saveItem"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                            >
                                Save
                            </button>
                            <button 
                                type="button"
                                wire:click="cancelEditing"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Menu Items Tree -->
            @if(count($menuItems) > 0)
                <div class="border border-gray-200 rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach($menuItems as $item)
                            <li class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if(isset($item['children']) && count($item['children']) > 0)
                                            <button 
                                                type="button"
                                                wire:click="toggleExpand({{ $item['id'] }})"
                                                class="text-gray-400 hover:text-gray-600"
                                            >
                                                <svg class="h-5 w-5 transform {{ in_array($item['id'], $expandedItems) ? 'rotate-90' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="w-5"></span>
                                        @endif
                                        <div>
                                            <a href="{{ $item['url'] }}" target="_blank" class="font-medium text-blue-600 hover:text-blue-800">{{ $item['label'] }}</a>
                                            <span class="text-sm text-gray-500 ml-2">{{ $item['url'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button 
                                            type="button"
                                            wire:click="moveUp({{ $item['id'] }})"
                                            class="text-gray-400 hover:text-gray-600"
                                            title="Move up"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="moveDown({{ $item['id'] }})"
                                            class="text-gray-400 hover:text-gray-600"
                                            title="Move down"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="startEditing({{ $item['id'] }})"
                                            class="text-blue-600 hover:text-blue-800"
                                            title="Edit"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button 
                                            type="button"
                                            wire:click="deleteItem({{ $item['id'] }})"
                                            class="text-red-600 hover:text-red-800"
                                            title="Delete"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Children -->
                                @if(in_array($item['id'], $expandedItems) && isset($item['children']) && count($item['children']) > 0)
                                    <ul class="mt-4 ml-8 space-y-2 border-l-2 border-gray-200 pl-4">
                                        @foreach($item['children'] as $child)
                                            <li class="py-2">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-5"></span>
                                                        <div>
                                                            <a href="{{ $child['url'] }}" target="_blank" class="font-medium text-blue-600 hover:text-blue-800">{{ $child['label'] }}</a>
                                                            <span class="text-sm text-gray-500 ml-2">{{ $child['url'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button 
                                                            type="button"
                                                            wire:click="startEditing({{ $child['id'] }})"
                                                            class="text-blue-600 hover:text-blue-800"
                                                        >
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                        <button 
                                                            type="button"
                                                            wire:click="deleteItem({{ $child['id'] }})"
                                                            class="text-red-600 hover:text-red-800"
                                                        >
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No menu items</h3>
                    <p class="mt-1 text-sm text-gray-500">Add your first menu item above.</p>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No menu selected</h3>
            <p class="mt-1 text-sm text-gray-500">Select a menu to build.</p>
        </div>
    @endif
</div>
