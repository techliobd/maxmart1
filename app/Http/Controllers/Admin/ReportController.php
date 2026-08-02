<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $salesData = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count, SUM(total_amount) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalSales = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        $averageOrderValue = $totalOrders > 0 ? ($totalSales / $totalOrders) : 0;

        // Top selling products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.payment_status', 'paid')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Sales by category
        $salesByCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->where('orders.payment_status', 'paid')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total_sales'), DB::raw('COUNT(DISTINCT orders.id) as order_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        return view('admin.reports.sales', compact(
            'salesData',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topProducts',
            'salesByCategory',
            'dateFrom',
            'dateTo'
        ));
    }

    public function products(Request $request)
    {
        $lowStockProducts = Product::where('track_inventory', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->with('category')
            ->orderBy('stock_quantity')
            ->get();

        $outOfStockProducts = Product::where('track_inventory', true)
            ->where('stock_quantity', 0)
            ->with('category')
            ->get();

        $topViewedProducts = Product::orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.products', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'topViewedProducts'
        ));
    }

    public function customers(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $newCustomers = Customer::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        $topCustomers = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->where('orders.payment_status', 'paid')
            ->select('customers.id', 'customers.name', 'customers.email', DB::raw('COUNT(orders.id) as order_count'), DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('customers.id', 'customers.name', 'customers.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        $customerGrowth = Customer::selectRaw('DATE(created_at) as date, COUNT(*) as new_customers')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.customers', compact(
            'newCustomers',
            'topCustomers',
            'customerGrowth',
            'dateFrom',
            'dateTo'
        ));
    }

    public function inventory(Request $request)
    {
        $inventoryValue = Product::where('track_inventory', true)
            ->selectRaw('SUM(stock_quantity * (sale_price - discount)) as total_value')
            ->value('total_value') ?? 0;

        $productsByStock = Product::where('track_inventory', true)
            ->selectRaw('CASE 
                WHEN stock_quantity = 0 THEN "Out of Stock"
                WHEN stock_quantity <= low_stock_threshold THEN "Low Stock"
                ELSE "In Stock"
            END as stock_status, COUNT(*) as count')
            ->groupBy('stock_status')
            ->get();

        return view('admin.reports.inventory', compact('inventoryValue', 'productsByStock'));
    }

    public function exportSales(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $orders = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['customer', 'items'])
            ->get();

        $csvData = "Order Number,Customer,Date,Status,Payment Status,Total Amount\n";

        foreach ($orders as $order) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                $order->order_number,
                $order->customer?->name ?? 'Guest',
                $order->created_at->format('Y-m-d H:i:s'),
                $order->status,
                $order->payment_status,
                $order->total_amount
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="sales_report_' . date('Y-m-d') . '.csv"');
    }
}
