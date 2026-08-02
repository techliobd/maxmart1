<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CheckoutRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\CouponService;
use App\Services\ShippingService;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected CouponService $couponService,
        protected ShippingService $shippingService,
        protected TaxService $taxService
    ) {}

    public function index(Request $request)
    {
        $cart = $this->cartService->getCurrentCart();
        
        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $customer = null;
        $addresses = collect();
        
        if (Auth::check()) {
            $customer = Auth::user()->customer;
            if ($customer) {
                $addresses = $customer->addresses;
            }
        }

        $cartItems = $cart->items()->with(['product.primaryImage', 'variation'])->get();
        
        $subtotal = $cartItems->sum(fn($item) => $item->total);
        
        // Calculate shipping (default zone, will be updated based on address)
        $shipping = $this->shippingService->calculateFlatRate($subtotal);
        
        // Calculate tax
        $tax = $this->taxService->calculateForOrder($cartItems, $shipping);
        
        // Apply coupon if exists
        $couponCode = session('coupon_code');
        $coupon = null;
        $discount = 0;
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $this->couponService->isApplicable($coupon, $cartItems)) {
                $discount = $this->couponService->calculateDiscount($coupon, $cartItems, $subtotal);
            }
        }

        $total = $subtotal + $shipping + $tax - $discount;

        $paymentMethods = [
            ['id' => 'stripe', 'name' => 'Credit Card (Stripe)', 'icon' => 'credit-card'],
            ['id' => 'paypal', 'name' => 'PayPal', 'icon' => 'paypal'],
            ['id' => 'sslcommerz', 'name' => 'SSLCommerz', 'icon' => 'globe'],
            ['id' => 'bkash', 'name' => 'bKash', 'icon' => 'mobile'],
            ['id' => 'nagad', 'name' => 'Nagad', 'icon' => 'mobile'],
            ['id' => 'cod', 'name' => 'Cash on Delivery', 'icon' => 'money'],
        ];

        return view('storefront.checkout.index', compact(
            'cart',
            'cartItems',
            'customer',
            'addresses',
            'subtotal',
            'shipping',
            'tax',
            'coupon',
            'discount',
            'total',
            'paymentMethods'
        ));
    }

    public function store(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCurrentCart();
        
        if (!$cart || $cart->items()->count() === 0) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create or get customer
            $customer = $this->checkoutService->handleCustomer($request);

            // Get addresses
            $shippingAddress = CustomerAddress::findOrFail($request->shipping_address_id);
            $billingAddress = $request->filled('billing_address_id')
                ? CustomerAddress::findOrFail($request->billing_address_id)
                : $shippingAddress;

            // Validate stock
            $stockValidation = $this->checkoutService->validateStock($cart);
            if (!$stockValidation['valid']) {
                DB::rollBack();
                return back()->with('error', $stockValidation['message']);
            }

            // Calculate totals
            $cartItems = $cart->items()->with(['product', 'variation'])->get();
            $totals = $this->checkoutService->calculateTotals($cartItems, $shippingAddress, $request->coupon_code);

            // Create order
            $order = $this->checkoutService->createOrder(
                $customer,
                $cart,
                $shippingAddress,
                $billingAddress,
                $request->payment_method,
                $request->coupon_code,
                $request->notes,
                $totals
            );

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Handle payment
            if ($request->payment_method !== 'cod') {
                return redirect()->route('checkout.payment', $order)
                    ->with('success', 'Order created successfully. Please complete payment.');
            }

            return redirect()->route('checkout.confirmation', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during checkout. Please try again.');
        }
    }

    public function confirmation(Order $order)
    {
        if (!Auth::check() || (optional(Auth::user()->customer)->id !== $order->customer_id)) {
            abort(403);
        }

        $order->load(['items.product.primaryImage', 'shippingAddress', 'billingAddress']);

        return view('storefront.checkout.confirmation', compact('order'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $cart = $this->cartService->getCurrentCart();
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $coupon = Coupon::where('code', $request->code)->first();
        
        if (!$coupon) {
            return response()->json(['error' => 'Invalid coupon code'], 422);
        }

        $cartItems = $cart->items()->with(['product', 'variation'])->get();
        
        if (!$this->couponService->isApplicable($coupon, $cartItems)) {
            return response()->json(['error' => 'This coupon is not applicable to your cart'], 422);
        }

        $validation = $this->couponService->validateCoupon($coupon);
        if (!$validation['valid']) {
            return response()->json(['error' => $validation['message']], 422);
        }

        session(['coupon_code' => $request->code]);

        $discount = $this->couponService->calculateDiscount($coupon, $cartItems, $cartItems->sum(fn($i) => $i->total));

        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount
            ]
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon_code');
        
        return response()->json(['success' => true]);
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id'
        ]);

        $address = CustomerAddress::findOrFail($request->address_id);
        $cart = $this->cartService->getCurrentCart();
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $cartItems = $cart->items()->with(['product', 'variation'])->get();
        $subtotal = $cartItems->sum(fn($i) => $i->total);
        $weight = $cartItems->sum(fn($i) => $i->product->weight * $i->quantity);

        $shipping = $this->shippingService->calculateForAddress($address, $subtotal, $weight);

        return response()->json([
            'success' => true,
            'shipping' => $shipping
        ]);
    }
}
