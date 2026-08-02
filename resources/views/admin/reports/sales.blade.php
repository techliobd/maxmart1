<x-admin-layout>
    <x-slot name="title">Sales Report</x-slot>
    <x-slot name="subtitle">Revenue Analytics</x-slot>

    <div class="space-y-6">
        <!-- Date Range Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                        class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                        class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label for="period" class="block text-sm font-medium text-gray-700">Group By</label>
                    <select name="period" id="period" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        <option value="daily" {{ request('period', 'daily') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ request('period', 'weekly') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('period', 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                <dd class="mt-2 text-3xl font-bold text-primary">{{ number_format($stats['total_revenue'] ?? 0, 2) }} {{ defaultCurrency()->code }}</dd>
                <dd class="mt-1 text-sm text-green-600">↑ 12.5% from last period</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                <dd class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</dd>
                <dd class="mt-1 text-sm text-green-600">↑ 8.2% from last period</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Average Order Value</dt>
                <dd class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['avg_order_value'] ?? 0, 2) }} {{ defaultCurrency()->code }}</dd>
                <dd class="mt-1 text-sm text-red-600">↓ 3.1% from last period</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Refunds</dt>
                <dd class="mt-2 text-3xl font-bold text-red-600">{{ number_format($stats['total_refunds'] ?? 0, 2) }} {{ defaultCurrency()->code }}</dd>
                <dd class="mt-1 text-sm text-gray-500">{{ $stats['refund_count'] ?? 0 }} orders refunded</dd>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trend</h3>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Chart placeholder - integrate with Chart.js or similar</p>
            </div>
        </div>

        <!-- Sales by Category -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Sales by Category</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">% of Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categoryStats ?? [] as $stat)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stat['category_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $stat['orders_count'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">{{ number_format($stat['revenue'], 2) }} {{ defaultCurrency()->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $stat['percentage'] }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
