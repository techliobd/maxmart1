@extends('layouts.storefront')

@section('title', 'Compare Products - MaxMart')

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">Compare</li>
            </ol>
        </div>
    </nav>

    <!-- Compare Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Compare Products</h1>

            @if(session('compare_items', []) && count(session('compare_items', [])) > 0)
                <!-- Comparison Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 bg-gray-50 text-left text-sm font-semibold text-gray-700 border-b border-gray-200 min-w-[200px]">Product</th>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <th class="p-4 border-b border-gray-200 min-w-[250px] relative">
                                            <button onclick="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <img src="{{ $product->primaryImage?->url ?? asset('images/placeholder.png') }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-full h-48 object-contain mb-4">
                                            <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                            <p class="text-primary-600 font-bold mt-2">${{ number_format($product->price, 2) }}</p>
                                            <div class="mt-4 space-y-2">
                                                <a href="{{ route('product.show', $product->slug) }}" class="block text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                                    View Details
                                                </a>
                                                <livewire:add-to-cart :product="$product" :key="'compare-cart-' . $product->id" />
                                            </div>
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rating -->
                            <tr>
                                <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">Rating</td>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <td class="p-4 border-b border-gray-200">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($product->averageRating >= $i)
                                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                                <span class="ml-2 text-sm text-gray-600">({{ $product->reviews->count() }})</span>
                                            </div>
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <!-- Brand -->
                            <tr>
                                <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">Brand</td>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <td class="p-4 border-b border-gray-200">
                                            @if($product->brand)
                                                <span class="text-gray-900">{{ $product->brand->name }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <!-- Category -->
                            <tr>
                                <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">Category</td>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <td class="p-4 border-b border-gray-200">
                                            @if($product->category)
                                                <span class="text-gray-900">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <!-- Stock Status -->
                            <tr>
                                <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">Availability</td>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <td class="p-4 border-b border-gray-200">
                                            @if($product->inStock)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    In Stock
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">Description</td>
                                @foreach(session('compare_items', []) as $productId)
                                    @php
                                        $product = \App\Models\Product::find($productId);
                                    @endphp
                                    @if($product)
                                        <td class="p-4 border-b border-gray-200">
                                            <p class="text-sm text-gray-600 line-clamp-4">{{ $product->description }}</p>
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <!-- Attributes (Dynamic) -->
                            @php
                                $allAttributeIds = [];
                                foreach(session('compare_items', []) as $productId) {
                                    $product = \App\Models\Product::find($productId);
                                    if($product) {
                                        $allAttributeIds = array_merge($allAttributeIds, $product->attributes->pluck('id')->toArray());
                                    }
                                }
                                $allAttributeIds = array_unique($allAttributeIds);
                                $attributes = \App\Models\Attribute::whereIn('id', $allAttributeIds)->get();
                            @endphp

                            @foreach($attributes as $attribute)
                                <tr>
                                    <td class="p-4 bg-gray-50 text-sm font-semibold text-gray-700 border-b border-gray-200">{{ $attribute->name }}</td>
                                    @foreach(session('compare_items', []) as $productId)
                                        @php
                                            $product = \App\Models\Product::find($productId);
                                        @endphp
                                        @if($product)
                                            <td class="p-4 border-b border-gray-200">
                                                @php
                                                    $productAttribute = $product->attributes->firstWhere('id', $attribute->id);
                                                    $value = $productAttribute?->pivot?->value ?? '-';
                                                @endphp
                                                <span class="text-gray-900">{{ $value }}</span>
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('shop') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        ← Add More Products to Compare
                    </a>
                    <button onclick="clearCompare()" class="text-red-600 hover:text-red-700 font-medium">
                        Clear All
                    </button>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">No products to compare</h3>
                    <p class="mt-2 text-gray-600">Add products to compare their features and specifications.</p>
                    <a href="{{ route('shop') }}" class="mt-6 inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                        Browse Products
                    </a>
                </div>
            @endif
        </div>
    </section>

    <script>
        function removeFromCompare(productId) {
            fetch('{{ route("api.compare.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }

        function clearCompare() {
            fetch('{{ route("api.compare.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
    </script>
@endsection
