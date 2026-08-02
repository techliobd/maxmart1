@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Product</h1>
            <p class="text-gray-500 mt-1">Update product information</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
            ← Back to Products
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from name</p>
                        </div>

                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('short_description') border-red-500 @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Images</h2>
                    
                    <div class="space-y-4">
                        @if($product->images->count() > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                                <div class="grid grid-cols-4 gap-4">
                                    @foreach($product->images as $image)
                                        <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 group">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            <form action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="POST" class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-white hover:text-red-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Add New Images</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Upload additional images</p>
                        </div>
                    </div>
                </div>

                <!-- Variations -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Variations</h2>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="has_variations" id="has_variations" value="1" {{ old('has_variations', $product->has_variations) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">This product has variations (e.g., size, color)</span>
                        </label>
                    </div>

                    @if($product->variations->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Variations ({{ $product->variations->count() }})</label>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attributes</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($product->variations as $variation)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $variation->sku }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    @foreach($variation->attributeValues as $attrValue)
                                                        <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs mr-1 mb-1">
                                                            {{ $attrValue->attribute->name ?? '' }}: {{ $attrValue->value ?? '' }}
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($variation->price, 2) }}</td>
                                                <td class="px-4 py-3 text-sm {{ $variation->stock <= 10 ? 'text-red-600' : 'text-gray-900' }}">{{ $variation->stock }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @livewire('admin.product-variation-generator', ['productId' => $product->id])
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Organization -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Organization</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                            <select name="brand_id" id="brand_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-1">Compare Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="compare_price" id="compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" min="0"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Show original price for sale display</p>
                        </div>

                        <div>
                            <label for="cost_per_item" class="block text-sm font-medium text-gray-700 mb-1">Cost per Item</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="cost_per_item" id="cost_per_item" value="{{ old('cost_per_item', $product->cost_per_item) }}" step="0.01" min="0"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">For profit calculation</p>
                        </div>

                        <div>
                            <label for="tax_class_id" class="block text-sm font-medium text-gray-700 mb-1">Tax Class</label>
                            <select name="tax_class_id" id="tax_class_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Default Tax</option>
                                @foreach($taxClasses as $taxClass)
                                    <option value="{{ $taxClass->id }}" {{ old('tax_class_id', $product->tax_class_id) == $taxClass->id ? 'selected' : '' }}>
                                        {{ $taxClass->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Inventory</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode (ISBN, UPC, GTIN)</label>
                            <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="track_inventory" id="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Track inventory</span>
                            </label>
                        </div>

                        <div id="stock-field" class="{{ old('track_inventory', $product->track_inventory) ? '' : 'hidden' }}">
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 10) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Get notified when stock reaches this level</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Shipping</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 mb-1">Length (cm)</label>
                                <input type="number" name="length" id="length" value="{{ old('length', $product->length) }}" step="0.01" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700 mb-1">Width (cm)</label>
                                <input type="number" name="width" id="width" value="{{ old('width', $product->width) }}" step="0.01" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                <input type="number" name="height" id="height" value="{{ old('height', $product->height) }}" step="0.01" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_digital" id="is_digital" value="1" {{ old('is_digital', $product->is_digital) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Digital product (no shipping required)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Status</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $product->published_at?->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('meta_description', $product->meta_description) }}</textarea>
                        </div>

                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Comma-separated keywords</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4">
            <button type="button" onclick="window.location.href='{{ route('admin.products.index') }}'"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                Cancel
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Update Product
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('has_variations').addEventListener('change', function() {
    document.getElementById('variations-section').classList.toggle('hidden', !this.checked);
});

document.getElementById('track_inventory').addEventListener('change', function() {
    document.getElementById('stock-field').classList.toggle('hidden', !this.checked);
});
</script>
@endpush
@endsection
