<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Http\Requests\Admin\OrderUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'status']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'customer.user',
            'items.product.firstImage',
            'items.variation.attributeValues',
            'status',
            'billingAddress',
            'shippingAddress',
            'statusHistory' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            },
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('status');
        $statuses = OrderStatus::orderBy('sort_order')->get();

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Update order status
            if (isset($data['status'])) {
                $oldStatus = $order->status;
                $order->status = $data['status'];
                
                // Create status history entry
                if ($request->filled('status_comment')) {
                    $order->statusHistory()->create([
                        'from_status_id' => $oldStatus?->id,
                        'to_status_id' => OrderStatus::where('name', $data['status'])->first()?->id,
                        'comment' => $request->status_comment,
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            // Update payment status
            if (isset($data['payment_status'])) {
                $order->payment_status = $data['payment_status'];
            }

            // Update fulfillment status
            if (isset($data['fulfillment_status'])) {
                $order->fulfillment_status = $data['fulfillment_status'];
            }

            // Update tracking info
            if (isset($data['tracking_number'])) {
                $order->tracking_number = $data['tracking_number'];
            }

            if (isset($data['shipping_carrier'])) {
                $order->shipping_carrier = $data['shipping_carrier'];
            }

            $order->save();

            // Send notification to customer if status changed
            if ($request->boolean('notify_customer')) {
                // Implement notification logic here
                // For now, just log it
                \Log::info("Customer notification sent for order {$order->order_number}");
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update order: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|exists:order_statuses,name',
            'comment' => 'nullable|string|max:1000',
            'notify_customer' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;
            $newStatus = OrderStatus::where('name', $request->status)->firstOrFail();

            $order->status = $request->status;
            $order->save();

            // Create status history entry
            $order->statusHistory()->create([
                'from_status_id' => $oldStatus?->id,
                'to_status_id' => $newStatus->id,
                'comment' => $request->comment,
                'user_id' => auth()->id(),
            ]);

            // Send notification if requested
            if ($request->boolean('notify_customer')) {
                // Implement notification logic
            }

            DB::commit();

            return back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
    }

    public function printInvoice(Order $order)
    {
        return view('admin.orders.invoice', compact('order'));
    }

    public function printPackingSlip(Order $order)
    {
        return view('admin.orders.packing-slip', compact('order'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_status,update_payment_status,print_invoice',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'new_status' => 'nullable|exists:order_statuses,name',
            'new_payment_status' => 'nullable|in:pending,paid,failed,refunded,partially_refunded',
        ]);

        $orderIds = $request->order_ids;

        switch ($request->action) {
            case 'update_status':
                if ($request->filled('new_status')) {
                    Order::whereIn('id', $orderIds)->update(['status' => $request->new_status]);
                }
                break;
            case 'update_payment_status':
                if ($request->filled('new_payment_status')) {
                    Order::whereIn('id', $orderIds)->update(['payment_status' => $request->new_payment_status]);
                }
                break;
        }

        return back()->with('success', 'Orders updated successfully.');
    }

    public function refund(Order $order, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->total_amount,
            'reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Create refund record
            $refund = $order->refunds()->create([
                'amount' => $request->amount,
                'reason' => $request->reason,
                'status' => 'pending',
                'requested_by' => auth()->id(),
            ]);

            // Create refund items
            foreach ($order->items as $item) {
                $refund->items()->create([
                    'order_item_id' => $item->id,
                    'quantity' => 0, // Adjust based on actual refund logic
                    'amount' => 0,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Refund request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create refund: ' . $e->getMessage()]);
        }
    }
}
