<x-admin-layout>
    <x-slot name="title">View Coupon - {{ $coupon->code }}</x-slot>
    <x-slot name="subtitle">Coupon Details</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.coupons.index') }}" class="text-primary hover:text-primary-dark flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Coupons
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Coupon
            </a>
            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Coupon Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Code</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $coupon->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $coupon->type === 'percentage' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($coupon->type) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Value</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            @if($coupon->type === 'percentage')
                                {{ $coupon->value }}%
                            @else
                                {{ number_format($coupon->value, 2) }} {{ defaultCurrency()->code }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Minimum Order Amount</dt>
                        <dd class="mt-1 text-gray-900">
                            @if($coupon->min_order_amount > 0)
                                {{ number_format($coupon->min_order_amount, 2) }} {{ defaultCurrency()->code }}
                            @else
                                <span class="text-gray-400">No minimum</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Maximum Discount</dt>
                        <dd class="mt-1 text-gray-900">
                            @if($coupon->max_discount > 0)
                                {{ number_format($coupon->max_discount, 2) }} {{ defaultCurrency()->code }}
                            @else
                                <span class="text-gray-400">No limit</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Usage Limit Per User</dt>
                        <dd class="mt-1 text-gray-900">
                            @if($coupon->usage_limit_per_user > 0)
                                {{ $coupon->usage_limit_per_user }} time(s)
                            @else
                                <span class="text-gray-400">Unlimited</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Description</h3>
                <p class="text-gray-600">{{ $coupon->description ?? 'No description provided.' }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Applicable Products & Categories</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Products ({{ $coupon->products->count() }})</h4>
                        @if($coupon->products->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($coupon->products as $product)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $product->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">Applies to all products</p>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Categories ({{ $coupon->categories->count() }})</h4>
                        @if($coupon->categories->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($coupon->categories as $category)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 text-sm">Applies to all categories</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Validity Period</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y H:i') : 'Immediately' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y H:i') : 'Never expires' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $isActive = $coupon->isActive();
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Uses</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $coupon->orders_count }}</dd>
                        @if($coupon->usage_limit > 0)
                            <dd class="text-sm text-gray-500">out of {{ $coupon->usage_limit }} total uses</dd>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, ($coupon->orders_count / $coupon->usage_limit) * 100) }}%"></div>
                            </div>
                        @endif
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Discount Given</dt>
                        <dd class="mt-1 text-xl font-bold text-green-600">{{ number_format($coupon->total_discount_amount ?? 0, 2) }} {{ defaultCurrency()->code }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Restrictions</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">First Order Only</dt>
                        <dd class="mt-1 text-gray-900">{{ $coupon->first_order_only ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Exclude Sale Items</dt>
                        <dd class="mt-1 text-gray-900">{{ $coupon->exclude_sale_items ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Free Shipping</dt>
                        <dd class="mt-1 text-gray-900">{{ $coupon->free_shipping ? 'Yes' : 'No' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timestamps</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $coupon->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $coupon->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
