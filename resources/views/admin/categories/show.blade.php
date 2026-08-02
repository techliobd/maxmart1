<x-admin-layout>
    <x-slot name="title">{{ $category->name }}</x-slot>
    <x-slot name="subtitle">Category details and management</x-slot>

    <div class="space-y-6">
        <!-- Action Buttons -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                @if($category->parent)
                    <a href="{{ route('admin.categories.show', $category->parent) }}" 
                       class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        {{ $category->parent->name }}
                    </a>
                    <span class="text-gray-400">/</span>
                @endif
                <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will also delete all subcategories and products in this category.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <!-- Image -->
                <div class="p-6 border-b md:border-b-0 md:border-r border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Category Image</h3>
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover rounded-lg">
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="p-6 md:col-span-2">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Name</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Slug</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $category->slug }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Parent Category</h3>
                            <p class="mt-1 text-gray-900">{{ $category->parent ? $category->parent->name : 'None (Top Level)' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Display Order</h3>
                            <p class="mt-1 text-gray-900">{{ $category->display_order }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($category->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created</h3>
                            <p class="mt-1 text-gray-900">{{ $category->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if($category->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                            <p class="text-gray-900">{{ $category->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SEO Info -->
        @if($category->meta_title || $category->meta_description)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
            <div class="space-y-4">
                @if($category->meta_title)
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Meta Title</h4>
                    <p class="mt-1 text-gray-900">{{ $category->meta_title }}</p>
                </div>
                @endif
                @if($category->meta_description)
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Meta Description</h4>
                    <p class="mt-1 text-gray-900">{{ $category->meta_description }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Subcategories</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $category->children_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Products</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $category->products_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Level</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $category->depth ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children && $category->children->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Subcategories ({{ $category->children->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($category->children as $child)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($child->image)
                                        <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" class="w-8 h-8 rounded object-cover mr-3">
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">{{ $child->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $child->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $child->products_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($child->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.categories.show', $child) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="{{ route('admin.categories.edit', $child) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Products Preview -->
        @if($category->products && $category->products->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Products ({{ $category->products->count() }})</h3>
                <a href="{{ route('admin.products.index') }}?category_id={{ $category->id }}" class="text-sm text-blue-600 hover:text-blue-900">View All →</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($category->products->take(8) as $product)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500 mt-1">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>
