<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    protected CartService $cartService;
    protected CouponService $couponService;
    protected ShippingService $shippingService;
    protected TaxService $taxService;

    public function __construct(
        CartService $cartService,
        CouponService $couponService,
        ShippingService $shippingService,
        TaxService $taxService
    ) {
        $this->cartService = $cartService;
        $this->couponService = $couponService;
        $this->shippingService = $shippingService;
        $this->taxService = $taxService;
    }

    /**
     * Validate cart before checkout
     */
    public function validateCheckout(): array
    {
        $errors = [];
        $cart = $this->cartService->getCart();

        if ($cart->items()->count() === 0) {
            $errors[] = 'Your cart is empty';
            return ['valid' => false, 'errors' => $errors];
        }

        // Validate stock
        $stockIssues = $this->cartService->validateStock();
        if (!empty($stockIssues)) {
            foreach ($stockIssues as $issue) {
                $errors[] = sprintf(
                    'Only %d %s of "%s" available',
                    $issue['available'],
                    $issue['variation_name'] ?? 'unit',
                    $issue['product_name']
                );
            }
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    /**
     * Get checkout summary with all calculations
     */
    public function getCheckoutSummary(array $data): array
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();

        // Shipping calculation
        $shippingAddress = $this->resolveShippingAddress($data);
        $shippingCost = $this->shippingService->calculateShipping(
            $shippingAddress,
            $cart->items
        );

        // Tax calculation
        $taxAmount = $this->taxService->calculateTax(
            $subtotal,
            $shippingCost,
            $shippingAddress
        );

        // Coupon discount
        $discount = 0;
        $appliedCoupon = null;
        if (!empty($data['coupon_code'])) {
            $couponResult = $this->couponService->applyToCart($cart, $data['coupon_code']);
            if ($couponResult['success']) {
                $discount = $couponResult['discount_amount'];
                $appliedCoupon = $couponResult['coupon'];
            }
        }

        $total = $subtotal + $shippingCost + $taxAmount - $discount;

        return [
            'subtotal' => round($subtotal, 2),
            'shipping' => round($shippingCost, 2),
            'tax' => round($taxAmount, 2),
            'discount' => round($discount, 2),
            'total' => max(0, round($total, 2)),
            'coupon' => $appliedCoupon,
            'items_count' => $this->cartService->getItemCount(),
        ];
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Validate checkout
            $validation = $this->validateCheckout();
            if (!$validation['valid']) {
                throw new \Exception(implode(', ', $validation['errors']));
            }

            $cart = $this->cartService->getCart();
            $customer = $this->resolveCustomer($data);
            $shippingAddress = $this->resolveShippingAddress($data);
            $billingAddress = $this->resolveBillingAddress($data, $shippingAddress);

            // Get summary calculations
            $summary = $this->getCheckoutSummary($data);

            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'status_id' => OrderStatus::where('slug', 'pending')->first()?->id ?? 1,
                'subtotal' => $summary['subtotal'],
                'shipping_cost' => $summary['shipping'],
                'tax_amount' => $summary['tax'],
                'discount_amount' => $summary['discount'],
                'total' => $summary['total'],
                'currency_code' => config('app.currency', 'USD'),
                'payment_method' => $data['payment_method'] ?? 'cod',
                'payment_status' => 'pending',
                'shipping_method' => $data['shipping_method'] ?? 'standard',
                'notes' => $data['notes'] ?? null,
                'coupon_code' => $data['coupon_code'] ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variation_id' => $cartItem->variation_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'original_price' => $cartItem->original_price,
                    'subtotal' => $cartItem->unit_price * $cartItem->quantity,
                ]);
            }

            // Save addresses
            $order->shippingAddress()->create([
                'customer_id' => $customer->id,
                'first_name' => $shippingAddress['first_name'],
                'last_name' => $shippingAddress['last_name'],
                'company' => $shippingAddress['company'] ?? null,
                'address_line_1' => $shippingAddress['address_line_1'],
                'address_line_2' => $shippingAddress['address_line_2'] ?? null,
                'city' => $shippingAddress['city'],
                'state' => $shippingAddress['state'] ?? null,
                'postal_code' => $shippingAddress['postal_code'],
                'country' => $shippingAddress['country'],
                'phone' => $shippingAddress['phone'],
                'email' => $shippingAddress['email'],
            ]);

            if ($data['different_billing_address'] ?? false) {
                $order->billingAddress()->create([
                    'customer_id' => $customer->id,
                    'first_name' => $billingAddress['first_name'],
                    'last_name' => $billingAddress['last_name'],
                    'company' => $billingAddress['company'] ?? null,
                    'address_line_1' => $billingAddress['address_line_1'],
                    'address_line_2' => $billingAddress['address_line_2'] ?? null,
                    'city' => $billingAddress['city'],
                    'state' => $billingAddress['state'] ?? null,
                    'postal_code' => $billingAddress['postal_code'],
                    'country' => $billingAddress['country'],
                    'phone' => $billingAddress['phone'],
                    'email' => $billingAddress['email'],
                ]);
            }

            // Reduce stock
            $this->reduceStock($order);

            // Apply coupon usage
            if ($summary['coupon']) {
                $this->couponService->incrementUsage($summary['coupon']);
            }

            // Clear cart
            $this->cartService->clearCart();

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'type' => 'order_created',
                'description' => "Order #{$order->order_number} created",
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);

            return $order->fresh();
        });
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber(): string
    {
        $prefix = date('Ymd');
        $random = strtoupper(Str::random(6));
        
        do {
            $orderNumber = $prefix . '-' . $random;
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Resolve customer from checkout data
     */
    protected function resolveCustomer(array $data): Customer
    {
        if (Auth::check()) {
            return Customer::firstOrCreate(
                ['email' => Auth::user()->email],
                [
                    'user_id' => Auth::id(),
                    'first_name' => Auth::user()->first_name ?? $data['first_name'],
                    'last_name' => Auth::user()->last_name ?? $data['last_name'],
                    'phone' => $data['phone'] ?? null,
                ]
            );
        }

        return Customer::firstOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
            ]
        );
    }

    /**
     * Resolve shipping address from checkout data
     */
    protected function resolveShippingAddress(array $data): array
    {
        if (!empty($data['shipping_address_id'])) {
            $address = CustomerAddress::findOrFail($data['shipping_address_id']);
            return [
                'first_name' => $address->first_name,
                'last_name' => $address->last_name,
                'company' => $address->company,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
                'phone' => $address->phone,
                'email' => $address->email,
            ];
        }

        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'company' => $data['company'] ?? null,
            'address_line_1' => $data['address_line_1'],
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ];
    }

    /**
     * Resolve billing address from checkout data
     */
    protected function resolveBillingAddress(array $data, array $shippingAddress): array
    {
        if ($data['different_billing_address'] ?? false) {
            return [
                'first_name' => $data['billing_first_name'] ?? $shippingAddress['first_name'],
                'last_name' => $data['billing_last_name'] ?? $shippingAddress['last_name'],
                'company' => $data['billing_company'] ?? null,
                'address_line_1' => $data['billing_address_line_1'] ?? $shippingAddress['address_line_1'],
                'address_line_2' => $data['billing_address_line_2'] ?? null,
                'city' => $data['billing_city'] ?? $shippingAddress['city'],
                'state' => $data['billing_state'] ?? null,
                'postal_code' => $data['billing_postal_code'] ?? $shippingAddress['postal_code'],
                'country' => $data['billing_country'] ?? $shippingAddress['country'],
                'phone' => $data['billing_phone'] ?? $shippingAddress['phone'],
                'email' => $data['billing_email'] ?? $shippingAddress['email'],
            ];
        }

        return $shippingAddress;
    }

    /**
     * Reduce stock for ordered items
     */
    protected function reduceStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->variation) {
                $item->variation->decrement('stock_quantity', $item->quantity);
            } else {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
        }
    }
}
