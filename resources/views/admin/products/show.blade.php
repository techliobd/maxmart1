@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
            <p class="text-gray-500 mt-1">Product details and information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                ← Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Images -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Images</h2>
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @if($loop->first)
                                    <span class="absolute top-2 left-2 px-2 py-1 bg-blue-600 text-white text-xs rounded">Main</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500">No images uploaded</p>
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Description</h2>
                @if($product->description)
                    <div class="prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @else
                    <p class="text-gray-500">No description available</p>
                @endif
            </div>

            <!-- Variations -->
            @if($product->variations->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Variations ({{ $product->variations->count() }})</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attributes</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Compare Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
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
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            @if($variation->compare_price > $variation->price)
                                                ${{ number_format($variation->compare_price, 2) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm {{ $variation->stock <= 10 ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                            {{ $variation->stock }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($variation->status === 'active') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($variation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Reviews -->
            @if($product->reviews->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Reviews ({{ $product->reviews->count() }})</h2>
                    <div class="space-y-4">
                        @foreach($product->reviews->take(5) as $review)
                            <div class="border-b border-gray-100 pb-4 last:border-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">{{ substr($review->customer->name ?? 'Anonymous', 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $review->customer->name ?? 'Anonymous' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                                <p class="text-gray-400 text-xs mt-2">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                    @if($product->reviews->count() > 5)
                        <a href="#" class="mt-4 inline-block text-blue-600 hover:text-blue-700 text-sm font-medium">View all reviews →</a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($product->status === 'active') bg-green-100 text-green-800
                            @elseif($product->status === 'inactive') bg-gray-100 text-gray-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Published</span>
                        <span class="text-gray-900">{{ $product->published_at?->format('M d, Y') ?? 'Not scheduled' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Created</span>
                        <span class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Last Updated</span>
                        <span class="text-gray-900">{{ $product->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Base Price</span>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    </div>
                    @if($product->compare_price > $product->price)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Compare Price</span>
                            <span class="text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="text-green-600 font-medium">{{ round((1 - $product->price / $product->compare_price) * 100) }}% OFF</span>
                        </div>
                    @endif
                    @if($product->cost_per_item)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Cost per Item</span>
                            <span class="text-gray-900">${{ number_format($product->cost_per_item, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Profit Margin</span>
                            <span class="text-green-600 font-medium">${{ number_format($product->price - $product->cost_per_item, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Inventory Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Inventory</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">SKU</span>
                        <span class="text-gray-900 font-mono text-sm">{{ $product->sku ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Barcode</span>
                        <span class="text-gray-900 font-mono text-sm">{{ $product->barcode ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Track Inventory</span>
                        <span class="text-gray-900">{{ $product->track_inventory ? 'Yes' : 'No' }}</span>
                    </div>
                    @if($product->track_inventory)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Stock Level</span>
                            <span class="{{ $product->stock <= 10 ? 'text-red-600 font-bold' : 'text-gray-900' }}">{{ $product->stock }} units</span>
                        </div>
                        @if($product->stock <= 10)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-red-800 text-sm font-medium">⚠️ Low stock alert!</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Organization Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Organization</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Category</span>
                        <a href="{{ route('admin.categories.show', $product->category) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            {{ $product->category->name ?? '-' }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Brand</span>
                        @if($product->brand)
                            <a href="{{ route('admin.brands.show', $product->brand) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                {{ $product->brand->name }}
                            </a>
                        @else
                            <span class="text-gray-900">-</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tax Class</span>
                        <span class="text-gray-900">{{ $product->taxClass->name ?? 'Default' }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Shipping</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Type</span>
                        <span class="text-gray-900">{{ $product->is_digital ? 'Digital' : 'Physical' }}</span>
                    </div>
                    @if(!$product->is_digital)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Weight</span>
                            <span class="text-gray-900">{{ $product->weight ? $product->weight . ' kg' : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Dimensions</span>
                            <span class="text-gray-900">
                                @if($product->length && $product->width && $product->height)
                                    {{ $product->length }} × {{ $product->width }} × {{ $product->height }} cm
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium rounded-lg transition-colors">
                        Edit Product
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Delete Product
                        </button>
                    </form>
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="block w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-medium rounded-lg transition-colors">
                        View on Storefront ↗
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
