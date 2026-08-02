<x-admin-layout>
    <x-slot name="title">Create Order</x-slot>
    <x-slot name="subtitle">Manual Order Entry</x-slot>

    <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6" x-data="orderForm()">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Selection -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Select Customer *</label>
                            <select name="customer_id" id="customer_id" x-model="customerId" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('customer_id') border-red-500 @enderror"
                                @change="fetchCustomerAddress()">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" x-model="customerName" readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                            </div>
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="customer_email" id="customer_email" x-model="customerEmail" readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipping Address</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Street Address *</label>
                            <input type="text" name="shipping_address" id="shipping_address" x-model="shippingAddress" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_address') border-red-500 @enderror">
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="shipping_city" id="shipping_city" x-model="shippingCity" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="shipping_state" id="shipping_state" x-model="shippingState"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_state') border-red-500 @enderror">
                            @error('shipping_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" x-model="shippingPostalCode" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_postal_code') border-red-500 @enderror">
                            @error('shipping_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-gray-700">Country *</label>
                            <select name="shipping_country" id="shipping_country" x-model="shippingCountry" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_country') border-red-500 @enderror">
                                <option value="">-- Select Country --</option>
                                @foreach(config('countries', ['BD' => 'Bangladesh', 'US' => 'United States', 'GB' => 'United Kingdom', 'IN' => 'India']) as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('shipping_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                            <input type="text" name="shipping_phone" id="shipping_phone" x-model="shippingPhone" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="same_as_billing" id="same_as_billing" x-model="sameAsBilling"
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                                @change="copyToBilling()">
                            <span class="ml-2 text-sm text-gray-700">Billing address same as shipping</span>
                        </label>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Billing Address</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Street Address *</label>
                            <input type="text" name="billing_address" id="billing_address" x-model="billingAddress" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_address') border-red-500 @enderror">
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="billing_city" id="billing_city" x-model="billingCity" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_city') border-red-500 @enderror">
                            @error('billing_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_state" class="block text-sm font-medium text-gray-700">State/Province</label>
                            <input type="text" name="billing_state" id="billing_state" x-model="billingState"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_state') border-red-500 @enderror">
                            @error('billing_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                            <input type="text" name="billing_postal_code" id="billing_postal_code" x-model="billingPostalCode" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_postal_code') border-red-500 @enderror">
                            @error('billing_postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="billing_country" class="block text-sm font-medium text-gray-700">Country *</label>
                            <select name="billing_country" id="billing_country" x-model="billingCountry" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('billing_country') border-red-500 @enderror">
                                <option value="">-- Select Country --</option>
                                @foreach(config('countries', ['BD' => 'Bangladesh', 'US' => 'United States', 'GB' => 'United Kingdom', 'IN' => 'India']) as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('billing_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                    <div class="space-y-4">
                        <template x-for="(item, index) in orderItems" :key="index">
                            <div class="flex flex-wrap gap-3 items-end p-4 bg-gray-50 rounded-lg">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-medium text-gray-700">Product</label>
                                    <select :name="'items[' + index + '][product_id]'" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                        @change="updateItemPrice(index, $event)">
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" :name="'items[' + index + '][quantity]'" min="1" value="1" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                        @input="calculateTotals()">
                                </div>
                                <div class="w-32">
                                    <label class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" :name="'items[' + index + '][price]'" step="0.01" min="0" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary item-price"
                                        @input="calculateTotals()">
                                </div>
                                <button type="button" x-show="orderItems.length > 1" @click="removeItem(index)"
                                    class="btn-danger py-2 px-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addItem()" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                            <dd class="text-gray-900" x-text="formatCurrency(subtotal)">{{ number_format(0, 2) }} {{ defaultCurrency()->code }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Shipping</dt>
                            <dd>
                                <input type="number" name="shipping_cost" id="shipping_cost" step="0.01" min="0" value="0"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    @input="calculateTotals()">
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tax</dt>
                            <dd>
                                <input type="number" name="tax_amount" id="tax_amount" step="0.01" min="0" value="0"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    @input="calculateTotals()">
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Discount</dt>
                            <dd>
                                <input type="number" name="discount_amount" id="discount_amount" step="0.01" min="0" value="0"
                                    class="w-24 text-right rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                    @input="calculateTotals()">
                            </dd>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between">
                                <dt class="text-base font-semibold text-gray-800">Total</dt>
                                <dd class="text-xl font-bold text-primary" x-text="formatCurrency(total)">{{ number_format(0, 2) }} {{ defaultCurrency()->code }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <!-- Payment & Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment & Status</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                            <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('payment_method') border-red-500 @enderror">
                                <option value="">-- Select Method --</option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="stripe">Stripe</option>
                                <option value="paypal">PayPal</option>
                                <option value="sslcommerz">SSLCommerz</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status *</label>
                            <select name="payment_status" id="payment_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('payment_status') border-red-500 @enderror">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="partial">Partial</option>
                                <option value="refunded">Refunded</option>
                                <option value="failed">Failed</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="order_status" class="block text-sm font-medium text-gray-700">Order Status *</label>
                            <select name="order_status" id="order_status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('order_status') border-red-500 @enderror">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                            @error('order_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="customer_notes" class="block text-sm font-medium text-gray-700">Customer Notes</label>
                            <textarea name="customer_notes" id="customer_notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('customer_notes') border-red-500 @enderror">{{ old('customer_notes') }}</textarea>
                            @error('customer_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="internal_notes" class="block text-sm font-medium text-gray-700">Internal Notes</label>
                            <textarea name="internal_notes" id="internal_notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('internal_notes') border-red-500 @enderror">{{ old('internal_notes') }}</textarea>
                            @error('internal_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full btn-primary justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Order
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="w-full btn-secondary justify-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
    function orderForm() {
        return {
            customerId: '',
            customerName: '',
            customerEmail: '',
            shippingAddress: '',
            shippingCity: '',
            shippingState: '',
            shippingPostalCode: '',
            shippingCountry: '',
            shippingPhone: '',
            billingAddress: '',
            billingCity: '',
            billingState: '',
            billingPostalCode: '',
            billingCountry: '',
            sameAsBilling: true,
            orderItems: [{}],
            subtotal: 0,
            total: 0,

            addItem() {
                this.orderItems.push({});
            },

            removeItem(index) {
                this.orderItems.splice(index, 1);
                this.calculateTotals();
            },

            updateItemPrice(index, event) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const itemInputs = document.querySelectorAll('.item-price');
                if (itemInputs[index]) {
                    itemInputs[index].value = price;
                }
                this.calculateTotals();
            },

            fetchCustomerAddress() {
                if (!this.customerId) {
                    this.customerName = '';
                    this.customerEmail = '';
                    return;
                }
                // In a real implementation, you would fetch customer data via AJAX
                // For now, we'll just clear the fields
            },

            copyToBilling() {
                if (this.sameAsBilling) {
                    this.billingAddress = this.shippingAddress;
                    this.billingCity = this.shippingCity;
                    this.billingState = this.shippingState;
                    this.billingPostalCode = this.shippingPostalCode;
                    this.billingCountry = this.shippingCountry;
                }
            },

            calculateTotals() {
                let subtotal = 0;
                const itemRows = document.querySelectorAll('[x-for]');
                
                // Calculate from DOM inputs
                document.querySelectorAll('input[name*="[price]"]').forEach((input, index) => {
                    const price = parseFloat(input.value) || 0;
                    const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
                    const quantity = parseInt(quantityInput?.value) || 1;
                    subtotal += price * quantity;
                });

                this.subtotal = subtotal;

                const shipping = parseFloat(document.getElementById('shipping_cost')?.value) || 0;
                const tax = parseFloat(document.getElementById('tax_amount')?.value) || 0;
                const discount = parseFloat(document.getElementById('discount_amount')?.value) || 0;

                this.total = subtotal + shipping + tax - discount;
            },

            formatCurrency(amount) {
                return '{{ defaultCurrency()->code }} ' + parseFloat(amount).toFixed(2);
            }
        }
    }
    </script>
</x-admin-layout>
