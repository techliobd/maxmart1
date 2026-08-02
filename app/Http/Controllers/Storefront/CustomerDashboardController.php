<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\CustomerAddress;
use App\Models\Review;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        // All methods require authentication
        // This is enforced via route middleware in routes/web.php
    }

    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_orders' => Order::where('customer_id', $user->customer->id ?? 0)->count(),
            'pending_orders' => Order::where('customer_id', $user->customer->id ?? 0)->whereIn('status', ['pending', 'processing'])->count(),
            'wishlist_items' => Wishlist::where('user_id', $user->id)->count(),
            'reviews_count' => Review::where('user_id', $user->id)->count(),
        ];

        $recentOrders = Order::where('customer_id', $user->customer->id ?? 0)
            ->with(['items.product', 'status'])
            ->latest()
            ->take(5)
            ->get();

        return view('storefront.customer.dashboard', compact('stats', 'recentOrders'));
    }

    public function orders()
    {
        $user = Auth::user();
        
        $orders = Order::where('customer_id', $user->customer->id ?? 0)
            ->with(['items.product.firstImage', 'status'])
            ->latest()
            ->paginate(15);

        return view('storefront.customer.orders', compact('orders'));
    }

    public function showOrder($orderNumber)
    {
        $user = Auth::user();
        
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', $user->customer->id ?? 0)
            ->with(['items.product', 'items.variation.attributeValues', 'status', 'customer', 'billingAddress', 'shippingAddress'])
            ->firstOrFail();

        $timeline = $this->getOrderTimeline($order);

        return view('storefront.customer.order-detail', compact('order', 'timeline'));
    }

    private function getOrderTimeline($order)
    {
        $timeline = [];
        
        $statusHistory = $order->statusHistory ?? collect();
        
        foreach ($statusHistory as $history) {
            $timeline[] = [
                'status' => $history->status->name ?? $history->status_name,
                'date' => $history->created_at,
                'comment' => $history->comment ?? null,
            ];
        }

        return $timeline;
    }

    public function wishlist()
    {
        $user = Auth::user();
        
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with(['product.firstImage', 'product.variations'])
            ->latest()
            ->paginate(20);

        return view('storefront.customer.wishlist', compact('wishlistItems'));
    }

    public function removeFromWishlist($id)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $wishlist->delete();

        return back()->with('success', 'Item removed from wishlist.');
    }

    public function addresses()
    {
        $user = Auth::user();
        
        $addresses = CustomerAddress::where('customer_id', $user->customer->id ?? 0)
            ->orderBy('is_default', 'desc')
            ->get();

        return view('storefront.customer.addresses', compact('addresses'));
    }

    public function addAddress(Request $request)
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

        $user = Auth::user();
        
        // If this is set as default, unset other defaults of same type
        if ($request->boolean('is_default')) {
            CustomerAddress::where('customer_id', $user->customer->id ?? 0)
                ->where('type', $request->type)
                ->update(['is_default' => false]);
        }

        CustomerAddress::create([
            'customer_id' => $user->customer->id ?? 0,
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'is_default' => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, $id)
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

        $user = Auth::user();
        
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $user->customer->id ?? 0)
            ->firstOrFail();

        // If this is set as default, unset other defaults of same type
        if ($request->boolean('is_default') && !$address->is_default) {
            CustomerAddress::where('customer_id', $user->customer->id ?? 0)
                ->where('type', $request->type)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return back()->with('success', 'Address updated successfully.');
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $user->customer->id ?? 0)
            ->firstOrFail();
        
        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }

    public function reviews()
    {
        $user = Auth::user();
        
        $reviews = Review::where('user_id', $user->id)
            ->with(['product.firstImage'])
            ->latest()
            ->paginate(15);

        return view('storefront.customer.reviews', compact('reviews'));
    }

    public function questions()
    {
        $user = Auth::user();
        
        $questions = ProductQuestion::where('user_id', $user->id)
            ->with(['product.firstImage', 'answers.user'])
            ->latest()
            ->paginate(15);

        return view('storefront.customer.questions', compact('questions'));
    }

    public function profile()
    {
        $user = Auth::user();
        
        return view('storefront.customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
