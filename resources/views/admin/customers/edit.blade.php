<x-admin-layout>
    <x-slot name="title">Edit Customer</x-slot>
    <x-slot name="subtitle">{{ $customer->name }}</x-slot>

    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ old('status', $customer->status) === 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Internal Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('notes') border-red-500 @enderror">{{ old('notes', $customer->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Password (leave blank to keep current)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Marketing Preferences</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="subscribed_to_newsletter" value="1" {{ old('subscribed_to_newsletter', $customer->subscribed_to_newsletter ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Subscribed to Newsletter</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="accepts_marketing" value="1" {{ old('accepts_marketing', $customer->accepts_marketing ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-700">Accepts Marketing Emails</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Stats</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                            <dd class="mt-1 text-xl font-bold text-gray-900">{{ $customer->orders_count }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                            <dd class="mt-1 text-xl font-bold text-green-600">{{ number_format($customer->total_spent ?? 0, 2) }} {{ defaultCurrency()->code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Average Order Value</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                @if($customer->orders_count > 0)
                                    {{ number_format(($customer->total_spent ?? 0) / $customer->orders_count, 2) }} {{ defaultCurrency()->code }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Order Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $customer->last_order_date ? $customer->last_order_date->format('M d, Y') : 'No orders yet' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Info</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registered At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->last_login_at ? $customer->last_login_at->format('M d, Y H:i') : 'Never' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $customer->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $customer->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button type="submit" class="w-full btn-primary justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Customer
                        </button>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="w-full btn-secondary justify-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
