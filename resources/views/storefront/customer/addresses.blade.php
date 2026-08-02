<x-layout-storefront>
    <x-slot name="title">My Addresses</x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your shipping and billing addresses</p>
            </div>
            <button type="button" 
                    onclick="openAddressModal()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Address
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Addresses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($addresses as $address)
                <div class="border border-gray-200 rounded-lg p-6 relative hover:shadow-md transition-shadow {{ $address->is_default ? 'ring-2 ring-blue-500' : '' }}">
                    @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Default</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $address->label ?? ($address->type === 'billing' ? 'Billing' : 'Shipping') }}</h3>
                        <p class="text-sm text-gray-500 mb-3">{{ ucfirst($address->type) }} Address</p>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p>{{ $address->full_name }}</p>
                            <p>{{ $address->address_line_1 }}</p>
                            @if($address->address_line_2)
                                <p>{{ $address->address_line_2 }}</p>
                            @endif
                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p>{{ $address->country }}</p>
                            @if($address->phone)
                                <p class="pt-2">
                                    <span class="font-medium">Phone:</span> {{ $address->phone }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="editAddress({{ $address->id }})"
                                class="flex-1 px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('customer.addresses.set-default', $address) }}" class="flex-1">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    {{ $address->is_default ? 'disabled' : '' }}
                                    class="w-full px-3 py-2 text-sm font-medium {{ $address->is_default ? 'text-gray-400 bg-gray-100 cursor-not-allowed' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }} rounded-lg transition-colors">
                                {{ $address->is_default ? 'Default' : 'Set Default' }}
                            </button>
                        </form>
                        <button type="button" 
                                onclick="confirmDelete({{ $address->id }})"
                                {{ $address->is_default ? 'disabled' : '' }}
                                class="px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors {{ $address->is_default ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No addresses yet</h3>
                    <p class="text-gray-500 mb-4">Add your first address to get started.</p>
                    <button type="button" 
                            onclick="openAddressModal()"
                            class="inline-block px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Add Your First Address
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add/Edit Address Modal -->
    <div id="addressModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeAddressModal()"></div>

            <!-- Modal panel -->
            <div class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Add New Address</h3>
                    <button type="button" onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addressForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="addressId" name="_method" value="POST">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Label (e.g., Home, Work)</label>
                            <input type="text" id="label" name="label" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div>
                            <label for="is_default" class="block text-sm font-medium text-gray-700 mb-1">
                                <input type="checkbox" id="is_default" name="is_default" value="1" class="mr-2">
                                Set as default
                            </label>
                        </div>

                        <div class="col-span-2">
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="col-span-2">
                            <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                            <input type="text" id="address_line_1" name="address_line_1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="col-span-2">
                            <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                            <input type="text" id="address_line_2" name="address_line_2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" id="city" name="city" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province *</label>
                            <input type="text" id="state" name="state" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                            <input type="text" id="postal_code" name="postal_code" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                            <select id="country" name="country" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Country</option>
                                <option value="BD">Bangladesh</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="AU">Australia</option>
                                <option value="IN">India</option>
                                <option value="PK">Pakistan</option>
                                <option value="AE">United Arab Emirates</option>
                                <option value="SA">Saudi Arabia</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeAddressModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openAddressModal() {
            document.getElementById('modalTitle').textContent = 'Add New Address';
            document.getElementById('addressForm').reset();
            document.getElementById('addressId').value = '';
            document.getElementById('addressForm').action = "{{ route('customer.addresses.store') }}";
            document.getElementById('addressId').name = '_method';
            document.getElementById('addressId').value = 'POST';
            document.getElementById('addressModal').classList.remove('hidden');
        }

        function closeAddressModal() {
            document.getElementById('addressModal').classList.add('hidden');
        }

        function editAddress(id) {
            // In a real implementation, you would fetch the address data via AJAX
            // For now, redirect to an edit page or handle with Livewire
            window.location.href = `/customer/addresses/${id}/edit`;
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this address?')) {
                const form = document.getElementById('deleteForm');
                form.action = `/customer/addresses/${id}`;
                form.submit();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddressModal();
            }
        });
    </script>
</x-layout-storefront>
