<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function show()
    {
        return view('storefront.orders.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email'
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->whereHas('customer', function($q) use ($request) {
                $q->where('email', $request->email);
            })
            ->with(['status', 'items.product.primaryImage', 'shippingAddress'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check your order number and email address.');
        }

        // Get order timeline
        $timeline = collect();
        
        // Order placed
        $timeline->push([
            'status' => 'Order Placed',
            'date' => $order->created_at,
            'completed' => true
        ]);

        // Order confirmed
        if ($order->status && in_array(strtolower($order->status->name), ['confirmed', 'processing', 'shipped', 'delivered'])) {
            $timeline->push([
                'status' => 'Order Confirmed',
                'date' => $order->updated_at,
                'completed' => true
            ]);
        }

        // Processing
        if ($order->status && in_array(strtolower($order->status->name), ['processing', 'shipped', 'delivered'])) {
            $timeline->push([
                'status' => 'Processing',
                'date' => $order->updated_at,
                'completed' => true
            ]);
        }

        // Shipped
        if ($order->status && in_array(strtolower($order->status->name), ['shipped', 'delivered'])) {
            $timeline->push([
                'status' => 'Shipped',
                'date' => $order->updated_at,
                'completed' => true,
                'tracking_number' => $order->tracking_number,
                'shipping_carrier' => $order->shipping_carrier
            ]);
        }

        // Delivered
        if ($order->status && strtolower($order->status->name) === 'delivered') {
            $timeline->push([
                'status' => 'Delivered',
                'date' => $order->updated_at,
                'completed' => true
            ]);
        }

        // Cancelled
        if ($order->status && strtolower($order->status->name) === 'cancelled') {
            $timeline->push([
                'status' => 'Cancelled',
                'date' => $order->updated_at,
                'completed' => true
            ]);
        }

        return view('storefront.orders.tracking-result', compact('order', 'timeline'));
    }

    public function apiTrack(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->with(['status', 'shippingAddress'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'status' => $order->status?->name ?? 'Pending',
                'total' => $order->grand_total,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'tracking_number' => $order->tracking_number,
                'shipping_carrier' => $order->shipping_carrier,
                'estimated_delivery' => $order->estimated_delivery_date
            ]
        ]);
    }
}
