@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Create New Coupon</h1>
        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Coupons
        </a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Basic Information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Coupon Code *</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="e.g., SUMMER20">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Optional description for internal use">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Discount Type *</label>
                        <select name="type" id="type" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="discount_value_field">
                        <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value *</label>
                        <div class="relative mt-1">
                            <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 pr-12 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="0.00">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-500" id="discount_suffix">%</span>
                        </div>
                        @error('discount_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Usage Limits</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="usage_limit" class="block text-sm font-medium text-gray-700">Total Usage Limit</label>
                        <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Leave empty for unlimited">
                        @error('usage_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Total times this coupon can be used (all customers)</p>
                    </div>

                    <div>
                        <label for="usage_limit_per_user" class="block text-sm font-medium text-gray-700">Usage Limit Per User</label>
                        <input type="number" name="usage_limit_per_user" id="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Leave empty for unlimited">
                        @error('usage_limit_per_user')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Times each customer can use this coupon</p>
                    </div>

                    <div>
                        <label for="min_purchase_amount" class="block text-sm font-medium text-gray-700">Minimum Purchase Amount</label>
                        <div class="relative mt-1">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">$</span>
                            <input type="number" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount') }}" step="0.01" min="0"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 pl-8 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="0.00">
                        </div>
                        @error('min_purchase_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum cart total required to use this coupon</p>
                    </div>

                    <div>
                        <label for="max_discount_amount" class="block text-sm font-medium text-gray-700">Maximum Discount Amount</label>
                        <div class="relative mt-1">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">$</span>
                            <input type="number" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}" step="0.01" min="0"
                                class="block w-full rounded-lg border border-gray-300 px-4 py-2 pl-8 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                placeholder="0.00">
                        </div>
                        @error('max_discount_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maximum discount cap (for percentage coupons)</p>
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Validity Period</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('starts_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave empty to start immediately</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiry Date & Time</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave empty for no expiry</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                    </div>
                </div>
            </div>

            <!-- Applicable Products/Categories -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Applicability</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="applicable_to" class="block text-sm font-medium text-gray-700">Apply To</label>
                        <select name="applicable_to" id="applicable_to"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="all" {{ old('applicable_to') == 'all' ? 'selected' : '' }}>All Products</option>
                            <option value="specific_products" {{ old('applicable_to') == 'specific_products' ? 'selected' : '' }}>Specific Products</option>
                            <option value="specific_categories" {{ old('applicable_to') == 'specific_categories' ? 'selected' : '' }}>Specific Categories</option>
                        </select>
                        @error('applicable_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="products_field" style="display: none;">
                        <label for="product_ids" class="block text-sm font-medium text-gray-700">Select Products</label>
                        <select name="product_ids[]" id="product_ids" multiple
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple</p>
                    </div>

                    <div id="categories_field" style="display: none;">
                        <label for="category_ids" class="block text-sm font-medium text-gray-700">Select Categories</label>
                        <select name="category_ids[]" id="category_ids" multiple
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="exclude_sale_items" id="exclude_sale_items" value="1" {{ old('exclude_sale_items') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="exclude_sale_items" class="text-sm font-medium text-gray-700">Exclude Sale Items</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.coupons.index') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Create Coupon
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const discountValueField = document.getElementById('discount_value_field');
    const discountSuffix = document.getElementById('discount_suffix');
    const applicableToSelect = document.getElementById('applicable_to');
    const productsField = document.getElementById('products_field');
    const categoriesField = document.getElementById('categories_field');

    function updateDiscountField() {
        if (typeSelect.value === 'free_shipping') {
            discountValueField.style.display = 'none';
        } else {
            discountValueField.style.display = 'block';
            discountSuffix.textContent = typeSelect.value === 'percentage' ? '%' : '$';
        }
    }

    function updateApplicabilityFields() {
        productsField.style.display = applicableToSelect.value === 'specific_products' ? 'block' : 'none';
        categoriesField.style.display = applicableToSelect.value === 'specific_categories' ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', updateDiscountField);
    applicableToSelect.addEventListener('change', updateApplicabilityFields);

    // Initialize
    updateDiscountField();
    updateApplicabilityFields();
});
</script>
@endpush
@endsection
