@extends('layouts.admin')

@section('title', 'Edit Flash Sale')

@section('content')
<div class="px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Flash Sale: {{ $flashSale->name }}</h1>
        <a href="{{ route('admin.flash-sales.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Flash Sales
        </a>
    </div>

    <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Basic Information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Sale Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $flashSale->name) }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="e.g., Summer Mega Sale">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Optional description">{{ old('description', $flashSale->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount Percentage *</label>
                        <div class="relative mt-1">
                            <input type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', $flashSale->discount_percentage) }}" min="1" max="99" required
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 pr-12 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="20">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">%</span>
                        </div>
                        @error('discount_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">This discount will be applied to all products in this flash sale</p>
                    </div>
                </div>
            </div>

            <!-- Schedule & Stats -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Schedule</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700">Start Date & Time *</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', \Carbon\Carbon::parse($flashSale->starts_at)->format('Y-m-d\TH:i')) }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('starts_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-gray-700">End Date & Time *</label>
                        <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', \Carbon\Carbon::parse($flashSale->ends_at)->format('Y-m-d\TH:i')) }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('ends_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Flash sales typically last 24-72 hours</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $flashSale->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Performance Statistics</h2>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div class="rounded-lg bg-blue-50 p-4">
                        <p class="text-sm text-gray-600">Products</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $flashSale->products_count ?? 0 }}</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4">
                        <p class="text-sm text-gray-600">Orders Generated</p>
                        <p class="text-2xl font-bold text-green-600">{{ $flashSale->orders_count ?? 0 }}</p>
                    </div>
                    <div class="rounded-lg bg-purple-50 p-4">
                        <p class="text-sm text-gray-600">Revenue</p>
                        <p class="text-2xl font-bold text-purple-600">${{ number_format($flashSale->revenue ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-lg bg-orange-50 p-4">
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-lg font-bold {{ $flashSale->is_active ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $flashSale->is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Select Products -->
            <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Select Products</h2>
                
                <div class="mb-4">
                    <label for="product_search" class="block text-sm font-medium text-gray-700">Search Products</label>
                    <input type="text" id="product_search" 
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Type to search products...">
                </div>

                <div class="max-h-96 overflow-y-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="w-12 px-4 py-3">
                                    <input type="checkbox" id="select_all" 
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                            class="product-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            {{ in_array($product->id, old('product_ids', $flashSale->products->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($product->primary_image)
                                                <img src="{{ asset('storage/' . $product->primary_image) }}" alt="{{ $product->name }}" 
                                                    class="h-10 w-10 rounded object-cover">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded bg-gray-200">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $product->sku }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                            {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800' : ($product->stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('product_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Selected: <span id="selected_count">0</span> products</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.flash-sales.index') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Flash Sale
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select_all');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const selectedCountSpan = document.getElementById('selected_count');
    const productSearch = document.getElementById('product_search');
    const tableRows = document.querySelectorAll('tbody tr');

    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        selectAll.checked = checkedCount === productCheckboxes.length;
    }

    selectAll.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        tableRows.forEach(row => {
            const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const sku = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            if (productName.includes(searchTerm) || sku.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Initialize
    updateSelectedCount();
});
</script>
@endpush
@endsection
