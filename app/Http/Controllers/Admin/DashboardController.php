<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('track_inventory', true)
                ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->count(),
            'out_of_stock_products' => Product::where('track_inventory', true)
                ->where('stock_quantity', 0)
                ->count(),
            'total_customers' => Customer::count(),
            'total_users' => User::where('is_admin', false)->where('is_staff', false)->count(),
        ];

        // Revenue stats (last 7 days)
        $revenueStats = Order::where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        // Recent orders
        $recentOrders = Order::with(['customer', 'status'])
            ->latest()
            ->take(10)
            ->get();

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'revenueStats', 'recentOrders', 'topProducts'));
    }
}
