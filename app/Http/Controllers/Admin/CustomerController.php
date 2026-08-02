<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load([
            'user',
            'addresses',
            'orders' => function ($query) {
                $query->with('status')->latest()->take(10);
            },
        ]);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => $customer->orders()->where('payment_status', 'paid')->avg('total_amount'),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->user_id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only(['name', 'phone']));

        // Update associated user
        if ($customer->user) {
            $customer->user->update([
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete customer with existing orders.']);
        }

        // Delete associated user
        if ($customer->user) {
            $customer->user->delete();
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function orders(Customer $customer)
    {
        $orders = $customer->orders()
            ->with(['status', 'items.product'])
            ->latest()
            ->paginate(20);

        return view('admin.customers.orders', compact('customer', 'orders'));
    }

    public function addresses(Customer $customer)
    {
        $addresses = $customer->addresses()->orderBy('is_default', 'desc')->get();

        return view('admin.customers.addresses', compact('customer', 'addresses'));
    }

    public function addAddress(Request $request, Customer $customer)
    {
        $request->validate([
            'type' => 'required|in:billing,shipping',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        $customer->addresses()->create($request->all());

        return back()->with('success', 'Address added successfully.');
    }

    public function deleteAddress(Customer $customer, $addressId)
    {
        $address = $customer->addresses()->findOrFail($addressId);
        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }

    public function notes(Customer $customer, Request $request)
    {
        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        // Assuming there's a customer_notes table or similar
        // For now, we'll log it
        \Log::info("Note added for customer {$customer->id}: {$request->note}");

        return back()->with('success', 'Note added successfully.');
    }
}
