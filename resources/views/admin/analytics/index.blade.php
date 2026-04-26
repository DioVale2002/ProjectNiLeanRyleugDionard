@extends('admin.layouts.app')
@section('title', 'Analytics')

@section('content')
{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="font-sans">
    
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Reports & Analytics</h2>
            <p class="text-sm text-gray-500 mt-1">Track your sales performance, orders, and inventory health.</p>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm border border-gray-100 mb-8">
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-gray-500 uppercase tracking-wider">Time Period</label>
                <div class="relative">
                    <select name="period" class="h-11 w-[180px] appearance-none rounded-lg border border-gray-300 pl-4 pr-10 text-sm outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors bg-white">
                        <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-gray-500 uppercase tracking-wider">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="h-11 w-[160px] rounded-lg border border-gray-300 px-4 text-sm outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-gray-500 uppercase tracking-wider">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="h-11 w-[160px] rounded-lg border border-gray-300 px-4 text-sm outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="h-11 rounded-lg bg-[#FCAE42] hover:bg-yellow-500 transition-colors px-6 text-sm text-black font-bold shadow-sm">Apply Filters</button>
                <a href="{{ route('admin.analytics.index') }}" class="h-11 flex items-center justify-center rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors px-6 text-sm text-gray-600 font-semibold shadow-sm">Reset</a>
            </div>
        </form>
    </div>

    {{-- Financial & Order Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Revenue (Red Theme) --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#F54E4E] to-[#d63a3a] p-6 shadow-md flex flex-col h-[140px] relative overflow-hidden">
            <div class="bg-white/20 w-max p-2.5 rounded-xl backdrop-blur-sm shadow-sm mb-auto z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <svg class="absolute right-[-10px] bottom-[-20px] w-28 h-28 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
            <div class="mt-2 z-10">
                <p class="text-white/80 font-medium text-sm uppercase tracking-wide">Total Revenue</p>
                <p class="text-3xl font-bold text-white">₱{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-blue-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalOrders }}</p>
            </div>
        </div>

        {{-- Completed Orders --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-green-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Completed</p>
                <p class="text-3xl font-bold text-gray-800">{{ $completedOrders }}</p>
            </div>
        </div>

        {{-- Avg Order Value --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-purple-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-purple-500"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Avg Order Value</p>
                <p class="text-3xl font-bold text-gray-800">₱{{ number_format($averageOrderValue, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- MAIN CHARTS SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        {{-- Sales Performance Line Chart (Spans 2 columns) --}}
        <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm border border-gray-100 p-6 flex flex-col">
            <h3 class="font-semibold text-gray-800 mb-4">Revenue & Orders Overview</h3>
            <div class="flex-1 w-full relative min-h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Inventory Health Doughnut Chart (Spans 1 column) --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6 flex flex-col">
            <h3 class="font-semibold text-gray-800 mb-4">Inventory Health</h3>
            <div class="flex-1 w-full relative min-h-[250px] flex items-center justify-center">
                <canvas id="inventoryChart"></canvas>
            </div>
            
            {{-- Quick Legend underneath the chart --}}
            <div class="mt-6 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-gray-600">Active Stock</span>
                    </div>
                    <span class="text-sm font-bold">{{ $activeProducts }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FCAE42]"></div>
                        <span class="text-sm text-gray-600">Low Stock</span>
                    </div>
                    <span class="text-sm font-bold">{{ $lowStockProducts }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#F54E4E]"></div>
                        <span class="text-sm text-gray-600">Out of Stock</span>
                    </div>
                    <span class="text-sm font-bold">{{ $outOfStockProducts }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Best / Worst Sellers Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        {{-- Top Selling --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                <div class="bg-green-100 p-1.5 rounded-lg"><svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg></div>
                <h3 class="font-semibold text-gray-800">Top Selling Books</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-white">
                        <tr class="border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                            <th class="py-3 pl-6 pr-4">Book Title</th>
                            <th class="py-3 px-4 text-center">Units Sold</th>
                            <th class="py-3 px-4 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topSelling as $book)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 pl-6 pr-4 font-medium text-gray-800">{{ $book->title }}</td>
                                <td class="py-3 px-4 text-center text-gray-600">
                                    <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-xs font-bold">{{ $book->units_sold }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-medium text-gray-900">₱{{ number_format((float) $book->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-400">No top-selling data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Selling --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                <div class="bg-red-100 p-1.5 rounded-lg"><svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg></div>
                <h3 class="font-semibold text-gray-800">Low Selling Books</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-white">
                        <tr class="border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                            <th class="py-3 pl-6 pr-4">Book Title</th>
                            <th class="py-3 px-4 text-center">Units Sold</th>
                            <th class="py-3 px-4 text-right">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($lowSelling as $book)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 pl-6 pr-4 font-medium text-gray-800">{{ $book->title }}</td>
                                <td class="py-3 px-4 text-center text-gray-600">
                                    <span class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs font-bold">{{ $book->units_sold }}</span>
                                </td>
                                <td class="py-3 px-4 text-right text-gray-600">{{ $book->Stock }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-400">No low-selling data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Miscellaneous Operational Data --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-wide">Pending Orders</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $pendingOrders }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full"><svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-wide">Processing</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $processingOrders }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full"><svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg></div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-wide">Stock In Logs</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stockInCount }}</p>
            </div>
            <div class="bg-teal-50 p-3 rounded-full"><svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg></div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-wide">Stock Out Logs</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stockOutCount }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full"><svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg></div>
        </div>
    </div>
</div>

{{-- Pass Laravel data securely to JavaScript --}}
@php
    // Formatting the raw dates into nice readable strings (e.g., "Jan 12")
    $chartLabels = collect($performanceRows)->map(function($row) {
        return \Carbon\Carbon::parse($row->period_date)->format('M d');
    });
    
    // Grabbing the data arrays
    $revenueData = collect($performanceRows)->pluck('revenue');
    $orderData = collect($performanceRows)->pluck('order_count');
@endphp

{{-- Chart.js Initialization --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. Line Chart: Sales & Revenue ---
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Revenue (₱)',
                        data: @json($revenueData),
                        borderColor: '#F54E4E',
                        backgroundColor: 'rgba(245, 78, 78, 0.1)',
                        borderWidth: 2,
                        tension: 0.4, // Makes the line curved and smooth
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Orders',
                        data: @json($orderData),
                        borderColor: '#FCAE42',
                        backgroundColor: 'rgba(252, 174, 66, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        borderDash: [5, 5], // Dashed line for orders
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.datasetIndex === 0) {
                                    label += '₱' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                                } else {
                                    label += context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Revenue (₱)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Number of Orders' },
                        grid: { drawOnChartArea: false } // Prevent double grid lines
                    }
                }
            }
        });

        // --- 2. Doughnut Chart: Inventory Health ---
        const ctxInventory = document.getElementById('inventoryChart').getContext('2d');
        
        new Chart(ctxInventory, {
            type: 'doughnut',
            data: {
                labels: ['Active Stock', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [
                        {{ $activeProducts }}, 
                        {{ $lowStockProducts }}, 
                        {{ $outOfStockProducts }}
                    ],
                    backgroundColor: [
                        '#22c55e', // Green
                        '#FCAE42', // Yellow
                        '#F54E4E'  // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Makes the doughnut ring thinner
                plugins: {
                    legend: {
                        display: false // Hidden because we built a custom HTML legend below it
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + ' items';
                            }
                        }
                    }
                }
            }
        });

    });
</script>
@endsection