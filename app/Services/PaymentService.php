<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment based on selected method
     */
    public function processPayment(Order $order, string $method, array $data): array
    {
        switch ($method) {
            case 'stripe':
                return $this->processStripe($order, $data);
            case 'paypal':
                return $this->processPaypal($order, $data);
            case 'sslcommerz':
                return $this->processSslcommerz($order, $data);
            case 'bkash':
                return $this->processBkash($order, $data);
            case 'nagad':
                return $this->processNagad($order, $data);
            case 'cod':
                return $this->processCod($order, $data);
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    /**
     * Process Stripe payment
     */
    protected function processStripe(Order $order, array $data): array
    {
        try {
            $gateway = PaymentGateway::where('provider', 'stripe')->where('is_active', true)->first();
            
            if (!$gateway) {
                return ['success' => false, 'message' => 'Stripe is not available'];
            }

            // In production, use Stripe SDK
            // For now, simulate successful payment
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => 'stripe_' . uniqid(),
                'payment_data' => json_encode([
                    'method' => 'stripe',
                    'amount' => $order->total,
                    'currency' => $order->currency_code,
                ]),
            ]);

            ActivityLog::create([
                'user_id' => $order->user_id,
                'type' => 'payment_received',
                'description' => "Stripe payment received for order #{$order->order_number}",
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);

            return [
                'success' => true,
                'message' => 'Payment successful',
                'transaction_id' => $order->transaction_id,
                'redirect_url' => route('order.confirmation', $order->order_number),
            ];
        } catch (\Exception $e) {
            Log::error('Stripe payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process PayPal payment
     */
    protected function processPaypal(Order $order, array $data): array
    {
        try {
            $gateway = PaymentGateway::where('provider', 'paypal')->where('is_active', true)->first();
            
            if (!$gateway) {
                return ['success' => false, 'message' => 'PayPal is not available'];
            }

            // In production, use PayPal SDK
            // For now, simulate successful payment
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => 'paypal_' . uniqid(),
                'payment_data' => json_encode([
                    'method' => 'paypal',
                    'amount' => $order->total,
                    'currency' => $order->currency_code,
                ]),
            ]);

            ActivityLog::create([
                'user_id' => $order->user_id,
                'type' => 'payment_received',
                'description' => "PayPal payment received for order #{$order->order_number}",
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);

            return [
                'success' => true,
                'message' => 'Payment successful',
                'transaction_id' => $order->transaction_id,
                'redirect_url' => route('order.confirmation', $order->order_number),
            ];
        } catch (\Exception $e) {
            Log::error('PayPal payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process SSLCommerz payment (Bangladesh)
     */
    protected function processSslcommerz(Order $order, array $data): array
    {
        try {
            $gateway = PaymentGateway::where('provider', 'sslcommerz')->where('is_active', true)->first();
            
            if (!$gateway) {
                return ['success' => false, 'message' => 'SSLCommerz is not available'];
            }

            $config = $gateway->config ?? [];
            $storeId = $config['store_id'] ?? '';
            $storePassword = $config['store_password'] ?? '';

            // Initiate SSLCommerz payment
            $postData = [
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'total_amount' => $order->total,
                'currency' => $order->currency_code,
                'tran_id' => 'SSLCZ_' . uniqid(),
                'success_url' => route('payment.sslcommerz.success', $order->order_number),
                'fail_url' => route('payment.sslcommerz.fail', $order->order_number),
                'cancel_url' => route('payment.sslcommerz.cancel', $order->order_number),
                'cus_name' => $order->shippingAddress->first_name . ' ' . $order->shippingAddress->last_name,
                'cus_email' => $order->customer->email,
                'cus_phone' => $order->shippingAddress->phone,
                'cus_add1' => $order->shippingAddress->address_line_1,
                'cus_city' => $order->shippingAddress->city,
                'cus_country' => $order->shippingAddress->country,
                'shipping_method' => $order->shipping_method,
                'product_name' => 'Order #' . $order->order_number,
                'product_category' => 'E-commerce',
            ];

            // In production, make API call to SSLCommerz
            // $response = Http::post('https://sandbox.sslcommerz.com/gwprocess/v3/api.php', $postData);
            
            $order->update([
                'transaction_id' => $postData['tran_id'],
                'payment_status' => 'pending',
                'payment_data' => json_encode(['method' => 'sslcommerz']),
            ]);

            // Return redirect URL for SSLCommerz gateway page
            return [
                'success' => true,
                'message' => 'Redirecting to payment gateway',
                'redirect_url' => 'https://sandbox.sslcommerz.com/example/new_easycheckout_with_post.php',
                'gw_url' => 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php',
                'post_data' => $postData,
            ];
        } catch (\Exception $e) {
            Log::error('SSLCommerz payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process bKash payment (Bangladesh)
     */
    protected function processBkash(Order $order, array $data): array
    {
        try {
            $gateway = PaymentGateway::where('provider', 'bkash')->where('is_active', true)->first();
            
            if (!$gateway) {
                return ['success' => false, 'message' => 'bKash is not available'];
            }

            $config = $gateway->config ?? [];
            
            // bKash payment flow requires multiple steps
            // Step 1: Create payment
            $createPaymentData = [
                'mode' => '0011',
                'payerReference' => $order->order_number,
                'callbackURL' => route('payment.bkash.callback', $order->order_number),
                'amount' => $order->total,
                'currency' => $order->currency_code,
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
            ];

            $order->update([
                'transaction_id' => 'bkash_' . uniqid(),
                'payment_status' => 'pending',
                'payment_data' => json_encode(['method' => 'bkash']),
            ]);

            // In production, make API calls to bKash
            // Return checkout URL for bKash payment page
            return [
                'success' => true,
                'message' => 'Redirecting to bKash payment',
                'bkash_url' => 'https://sandbox.pay.bka.sh/checkout/complete',
                'payment_id' => $order->transaction_id,
            ];
        } catch (\Exception $e) {
            Log::error('bKash payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process Nagad payment (Bangladesh)
     */
    protected function processNagad(Order $order, array $data): array
    {
        try {
            $gateway = PaymentGateway::where('provider', 'nagad')->where('is_active', true)->first();
            
            if (!$gateway) {
                return ['success' => false, 'message' => 'Nagad is not available'];
            }

            $config = $gateway->config ?? [];

            // Nagad payment initialization
            $order->update([
                'transaction_id' => 'nagad_' . uniqid(),
                'payment_status' => 'pending',
                'payment_data' => json_encode(['method' => 'nagad']),
            ]);

            // In production, make API calls to Nagad
            return [
                'success' => true,
                'message' => 'Redirecting to Nagad payment',
                'nagad_url' => 'https://sandbox.nagad.com.bd/checkout',
                'payment_id' => $order->transaction_id,
            ];
        } catch (\Exception $e) {
            Log::error('Nagad payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process Cash on Delivery (COD)
     */
    protected function processCod(Order $order, array $data): array
    {
        try {
            $order->update([
                'payment_status' => 'pending',
                'payment_method' => 'cod',
                'transaction_id' => 'cod_' . $order->order_number,
                'payment_data' => json_encode(['method' => 'cod']),
            ]);

            ActivityLog::create([
                'user_id' => $order->user_id,
                'type' => 'cod_order_placed',
                'description' => "COD order placed: #{$order->order_number}",
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);

            return [
                'success' => true,
                'message' => 'Order placed successfully. Pay on delivery.',
                'redirect_url' => route('order.confirmation', $order->order_number),
            ];
        } catch (\Exception $e) {
            Log::error('COD payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Order placement failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment status from gateway callback
     */
    public function verifyPayment(string $method, array $data): array
    {
        switch ($method) {
            case 'stripe':
                return $this->verifyStripe($data);
            case 'paypal':
                return $this->verifyPaypal($data);
            case 'sslcommerz':
                return $this->verifySslcommerz($data);
            case 'bkash':
                return $this->verifyBkash($data);
            case 'nagad':
                return $this->verifyNagad($data);
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    /**
     * Verify SSLCommerz payment
     */
    protected function verifySslcommerz(array $data): array
    {
        $orderId = $data['order_number'] ?? null;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // In production, validate with SSLCommerz API
        $isValid = isset($data['status']) && $data['status'] === 'VALID';

        if ($isValid) {
            $order->update(['payment_status' => 'paid']);
            
            return [
                'success' => true,
                'message' => 'Payment verified',
                'order' => $order,
            ];
        }

        return ['success' => false, 'message' => 'Payment verification failed'];
    }

    /**
     * Verify bKash payment
     */
    protected function verifyBkash(array $data): array
    {
        $orderId = $data['order_number'] ?? null;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // In production, query bKash API for payment status
        $isPaid = $data['paymentStatus'] === 'Completed' ?? false;

        if ($isPaid) {
            $order->update(['payment_status' => 'paid']);
            
            return [
                'success' => true,
                'message' => 'Payment verified',
                'order' => $order,
            ];
        }

        return ['success' => false, 'message' => 'Payment verification failed'];
    }

    /**
     * Verify Nagad payment
     */
    protected function verifyNagad(array $data): array
    {
        $orderId = $data['order_number'] ?? null;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found'];
        }

        // In production, query Nagad API for payment status
        $isPaid = $data['status'] === 'Success' ?? false;

        if ($isPaid) {
            $order->update(['payment_status' => 'paid']);
            
            return [
                'success' => true,
                'message' => 'Payment verified',
                'order' => $order,
            ];
        }

        return ['success' => false, 'message' => 'Payment verification failed'];
    }

    /**
     * Verify Stripe payment
     */
    protected function verifyStripe(array $data): array
    {
        // In production, use Stripe SDK to verify payment intent
        return ['success' => true, 'message' => 'Payment verified'];
    }

    /**
     * Verify PayPal payment
     */
    protected function verifyPaypal(array $data): array
    {
        // In production, use PayPal SDK to verify payment
        return ['success' => true, 'message' => 'Payment verified'];
    }

    /**
     * Get available payment methods
     */
    public function getAvailableMethods(): array
    {
        return PaymentGateway::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($gateway) => [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'provider' => $gateway->provider,
                'logo' => $gateway->logo,
                'description' => $gateway->description,
                'is_test_mode' => $gateway->is_test_mode,
            ])
            ->toArray();
    }
}
