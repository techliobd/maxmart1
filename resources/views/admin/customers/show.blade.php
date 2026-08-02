@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h1>
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Customers
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Customer Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600">
                        {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                        <p class="text-gray-500">{{ $customer->email }}</p>
                        @if($customer->phone)
                            <p class="text-gray-500">{{ $customer->phone }}</p>
                        @endif
                        <div class="mt-3 flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ $customer->orders_count ?? 0 }} Orders
                            </span>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                ${{ number_format($customer->total_spent ?? 0, 2) }} Spent
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Addresses -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Addresses</h3>
                @if($customer->addresses && $customer->addresses->count() > 0)
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach($customer->addresses as $address)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $address->label ?? ($address->is_default ? 'Default Address' : 'Address') }}</p>
                                        <p class="mt-1 text-sm text-gray-600">{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)
                                            <p class="text-sm text-gray-600">{{ $address->address_line_2 }}</p>
                                        @endif
                                        <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        <p class="text-sm text-gray-600">{{ $address->country }}</p>
                                        @if($address->phone)
                                            <p class="mt-1 text-sm text-gray-600">{{ $address->phone }}</p>
                                        @endif
                                    </div>
                                    @if($address->is_default)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                            Default
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No addresses added yet.</p>
                @endif
            </div>

            <!-- Order History -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Order History</h3>
                @if($customer->orders && $customer->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order #</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($customer->orders as $order)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">#{{ $order->order_number }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No orders yet.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Stats -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Account Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Member Since</span>
                        <span class="font-medium text-gray-900">{{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Order</span>
                        <span class="font-medium text-gray-900">
                            @if($customer->last_order_date)
                                {{ \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Avg Order Value</span>
                        <span class="font-medium text-gray-900">
                            @if(($customer->orders_count ?? 0) > 0)
                                ${{ number_format(($customer->total_spent ?? 0) / $customer->orders_count, 2) }}
                            @else
                                $0.00
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Wishlist Items</span>
                        <span class="font-medium text-gray-900">{{ $customer->wishlist_count ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Internal Notes</h3>
                <form action="{{ route('admin.customers.update-notes', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea name="notes" rows="4" 
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Add internal notes about this customer...">{{ $customer->notes }}</textarea>
                    <button type="submit" class="mt-3 w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Save Notes
                    </button>
                </form>
            </div>

            <!-- Actions -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.orders.create') }}?customer_id={{ $customer->id }}" 
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Order
                    </a>
                    <a href="mailto:{{ $customer->email }}" 
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Email
                    </a>
                    @if(!$customer->is_active)
                        <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Activate Account
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                onclick="return confirm('Are you sure you want to deactivate this customer?')">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Deactivate Account
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
