@extends('layouts.admin')

@section('title', 'Flash Sale Details')

@section('content')
<div class="px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $flashSale->name }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.flash-sales.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Flash Sales
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    @php
        $now = now();
        $startsAt = \Carbon\Carbon::parse($flashSale->starts_at);
        $endsAt = \Carbon\Carbon::parse($flashSale->ends_at);
        
        if ($now < $startsAt) {
            $status = 'Scheduled';
            $statusClass = 'bg-blue-50 border-blue-200';
            $statusTextClass = 'text-blue-800';
        } elseif ($now <= $endsAt) {
            $status = 'Active';
            $statusClass = 'bg-green-50 border-green-200';
            $statusTextClass = 'text-green-800';
        } else {
            $status = 'Expired';
            $statusClass = 'bg-gray-50 border-gray-200';
            $statusTextClass = 'text-gray-800';
        }
    @endphp
    <div class="mb-6 rounded-lg border {{ $statusClass }} p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $status === 'Active' ? 'bg-green-100 text-green-800' : ($status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ $status }}
                </span>
                <span class="{{ $statusTextClass }}">{{ $flashSale->discount_percentage }}% OFF on all products</span>
            </div>
            <div class="text-sm text-gray-600">
                @if($status === 'Active')
                    Ends in: <span class="font-medium" id="countdown">{{ $endsAt->diffForHumans() }}</span>
                @elseif($status === 'Scheduled')
                    Starts in: <span class="font-medium">{{ $startsAt->diffForHumans() }}</span>
                @else
                    Ended: <span class="font-medium">{{ $endsAt->diffForHumans() }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h2>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sale Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flashSale->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Discount</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flashSale->discount_percentage }}%</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flashSale->description ?? 'No description provided.' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Schedule -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Schedule</h2>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $startsAt->format('F d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $endsAt->format('F d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duration</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $startsAt->diffForHumans($endsAt, true) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $flashSale->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $flashSale->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Products in Sale -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Products ({{ $products->count() }})</h2>
                @if($products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Original Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Sale Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stock</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Orders</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
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
                                                    <p class="text-xs text-gray-500">{{ $product->sku }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">${{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium text-red-600">
                                                ${{ number_format($product->price * (1 - $flashSale->discount_percentage / 100), 2) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                                {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800' : ($product->stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $product->orders_count ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No products in this flash sale.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Performance</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Orders</span>
                        <span class="text-lg font-bold text-gray-900">{{ $flashSale->orders_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Revenue Generated</span>
                        <span class="text-lg font-bold text-green-600">${{ number_format($flashSale->revenue ?? 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Items Sold</span>
                        <span class="text-lg font-bold text-gray-900">{{ $flashSale->items_sold ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Avg Order Value</span>
                        <span class="text-lg font-bold text-gray-900">
                            @if(($flashSale->orders_count ?? 0) > 0)
                                ${{ number_format(($flashSale->revenue ?? 0) / $flashSale->orders_count, 2) }}
                            @else
                                $0.00
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" 
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Flash Sale
                    </a>
                    @if($flashSale->is_active && $status === 'Scheduled')
                        <form action="{{ route('admin.flash-sales.start', $flashSale) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Start Now
                            </button>
                        </form>
                    @endif
                    @if($flashSale->is_active && $status === 'Active')
                        <form action="{{ route('admin.flash-sales.end', $flashSale) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                onclick="return confirm('Are you sure you want to end this flash sale early?')">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                </svg>
                                End Early
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                            onclick="return confirm('Are you sure you want to delete this flash sale? This cannot be undone.')">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Flash Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
