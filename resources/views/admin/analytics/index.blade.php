@extends('admin.layouts.app')
@section('title', 'Analytics')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-3.5">
        <p class="text-xl text-black/60">Report & Analytics</p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 mb-8">
        <div class="flex flex-wrap items-end gap-3">
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Period</label>
                    <select name="period" class="h-[42px] w-[160px] rounded-md border border-gray-300 px-3 text-sm outline-none">
                        <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Date From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="h-[42px] w-[160px] rounded-md border border-gray-300 px-3 text-sm outline-none" />
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-500">Date To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="h-[42px] w-[160px] rounded-md border border-gray-300 px-3 text-sm outline-none" />
                </div>
                <button type="submit" class="h-[42px] rounded-md bg-[#FCAE42] px-5 text-sm text-white">Apply</button>
                <a href="{{ route('admin.analytics.index') }}" class="h-[42px] rounded-md border border-gray-300 px-5 py-2 text-sm text-gray-600">Reset</a>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl bg-[#F54E4E] px-4 py-3 h-[108px] text-white shadow-sm">
            <p class="text-sm">Revenue</p>
            <p class="mt-8 text-2xl font-semibold">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Orders</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $totalOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $completedOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Avg Completed Order</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">₱{{ number_format($averageOrderValue, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $pendingOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Processing</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $processingOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Stock In Logs</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $stockInCount }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Stock Out Logs</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $stockOutCount }}</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 mb-8">
        <h3 class="mb-4 text-lg font-semibold text-gray-700">Sales Performance (Daily)</h3>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-black">
                        <th class="py-3">Date</th>
                        <th class="py-3">Orders</th>
                        <th class="py-3">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($performanceRows as $row)
                        <tr class="border-b border-gray-100">
                            <td class="py-3">{{ \Illuminate\Support\Carbon::parse($row->period_date)->format('M d, Y') }}</td>
                            <td class="py-3">{{ $row->order_count }}</td>
                            <td class="py-3">₱{{ number_format((float) $row->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-500">No sales data in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Top Selling Books</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-black">
                            <th class="py-3">Book</th>
                            <th class="py-3">Units Sold</th>
                            <th class="py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSelling as $book)
                            <tr class="border-b border-gray-100">
                                <td class="py-3">{{ $book->title }}</td>
                                <td class="py-3">{{ $book->units_sold }}</td>
                                <td class="py-3">₱{{ number_format((float) $book->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">No top-selling data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Low Selling Books</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-black">
                            <th class="py-3">Book</th>
                            <th class="py-3">Units Sold</th>
                            <th class="py-3">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowSelling as $book)
                            <tr class="border-b border-gray-100">
                                <td class="py-3">{{ $book->title }}</td>
                                <td class="py-3">{{ $book->units_sold }}</td>
                                <td class="py-3">{{ $book->Stock }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">No low-selling data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Active Stock</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $activeProducts }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Low Stock</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $lowStockProducts }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Out of Stock</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $outOfStockProducts }}</p>
        </div>
    </div>
</div>
@endsection
