<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Refund;
use App\Models\RefundItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    /**
     * Get order by order number
     */
    public function getOrderByNumber(string $orderNumber): ?Order
    {
        return Order::with(['items.product', 'items.variation', 'status', 'customer'])
            ->where('order_number', $orderNumber)
            ->first();
    }

    /**
     * Update order status
     */
    public function updateStatus(Order $order, int $statusId, ?string $notes = null): Order
    {
        $order->update(['status_id' => $statusId]);

        $newStatus = OrderStatus::find($statusId);

        // Log status change
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'order_status_changed',
            'description' => "Order #{$order->order_number} status changed to {$newStatus->name}",
            'model_type' => Order::class,
            'model_id' => $order->id,
            'meta' => [
                'old_status_id' => $order->getOriginal('status_id'),
                'new_status_id' => $statusId,
                'notes' => $notes,
            ],
        ]);

        // Send notification if status is completed or cancelled
        if (in_array($newStatus->slug, ['completed', 'cancelled'])) {
            Notification::create([
                'user_id' => $order->user_id,
                'title' => "Order #{$order->order_number} - {$newStatus->name}",
                'message' => "Your order status has been updated to: {$newStatus->name}" . ($notes ? ". Notes: {$notes}" : ''),
                'type' => 'order_update',
                'model_type' => Order::class,
                'model_id' => $order->id,
            ]);
        }

        return $order->fresh();
    }

    /**
     * Get orders with filters
     */
    public function getOrders(array $filters = []): Collection
    {
        $query = Order::with(['customer', 'status', 'items'])
            ->orderBy('created_at', 'desc');

        if (!empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('order_number', 'like', "%{$filters['search']}%")
                    ->orWhereHas('customer', function ($q2) use ($filters) {
                        $q2->where('first_name', 'like', "%{$filters['search']}%")
                            ->orWhere('last_name', 'like', "%{$filters['search']}%")
                            ->orWhere('email', 'like', "%{$filters['search']}%");
                    });
            });
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get order statistics for dashboard
     */
    public function getStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Order::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total');
        $pendingOrders = (clone $query)->whereHas('status', fn($q) => $q->where('slug', 'pending'))->count();
        $completedOrders = (clone $query)->whereHas('status', fn($q) => $q->where('slug', 'completed'))->count();
        $cancelledOrders = (clone $query)->whereHas('status', fn($q) => $q->where('slug', 'cancelled'))->count();

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'average_order_value' => round($averageOrderValue, 2),
        ];
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Order $order, string $reason): Order
    {
        return DB::transaction(function () use ($order, $reason) {
            $cancelledStatus = OrderStatus::where('slug', 'cancelled')->first();
            
            if (!$cancelledStatus) {
                throw new \Exception('Cancelled status not found');
            }

            // Restore stock
            foreach ($order->items as $item) {
                if ($item->variation) {
                    $item->variation->increment('stock_quantity', $item->quantity);
                } else {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }

            // Update order status
            $this->updateStatus($order, $cancelledStatus->id, "Cancellation reason: {$reason}");

            // Handle refund if payment was made
            if ($order->payment_status === 'paid') {
                $this->initiateRefund($order, $order->total, 'Order cancelled: ' . $reason);
            }

            return $order->fresh();
        });
    }

    /**
     * Initiate refund for order
     */
    public function initiateRefund(Order $order, float $amount, string $reason): Refund
    {
        return DB::transaction(function () use ($order, $amount, $reason) {
            $refund = Refund::create([
                'order_id' => $order->id,
                'amount' => $amount,
                'reason' => $reason,
                'status' => 'pending',
                'requested_by' => auth()->id(),
            ]);

            // Create refund items
            foreach ($order->items as $item) {
                RefundItem::create([
                    'refund_id' => $refund->id,
                    'order_item_id' => $item->id,
                    'quantity' => $item->quantity,
                    'amount' => $item->subtotal,
                ]);
            }

            return $refund;
        });
    }

    /**
     * Process refund approval
     */
    public function approveRefund(Refund $refund): Refund
    {
        $refund->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'refund_approved',
            'description' => "Refund #{$refund->id} approved for order #{$refund->order->order_number}",
            'model_type' => Refund::class,
            'model_id' => $refund->id,
        ]);

        return $refund;
    }

    /**
     * Get customer orders
     */
    public function getCustomerOrders(int $customerId, int $limit = 10): Collection
    {
        return Order::with(['items.product.images', 'status'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Track order by number and email
     */
    public function trackOrder(string $orderNumber, string $email): ?Order
    {
        return Order::with(['items.product.images', 'status', 'shippingAddress'])
            ->where('order_number', $orderNumber)
            ->whereHas('customer', fn($q) => $q->where('email', $email))
            ->first();
    }

    /**
     * Export orders to array for CSV/PDF
     */
    public function exportOrders(Collection $orders): array
    {
        return $orders->map(fn($order) => [
            'order_number' => $order->order_number,
            'customer_name' => $order->customer->full_name,
            'customer_email' => $order->customer->email,
            'status' => $order->status->name,
            'payment_status' => $order->payment_status,
            'total' => $order->total,
            'items_count' => $order->items->sum('quantity'),
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
        ])->toArray();
    }

    /**
     * Bulk update order statuses
     */
    public function bulkUpdateStatus(array $orderIds, int $statusId): int
    {
        $updated = 0;
        
        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $this->updateStatus($order, $statusId);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Add note to order
     */
    public function addNote(Order $order, string $note, bool $isPrivate = true): void
    {
        $order->notes = trim(($order->notes ?? '') . "\n\n" . 
            '[' . now()->format('Y-m-d H:i') . '] ' . 
            ($isPrivate ? '[PRIVATE] ' : '') . $note);
        $order->save();
    }
}
