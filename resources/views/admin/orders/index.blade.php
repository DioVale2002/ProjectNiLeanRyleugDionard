@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="font-sans">
    
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor, process, and track customer orders.</p>
        </div>
    </div>

    {{-- Metrics Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- All Orders (Red Theme) --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#F54E4E] to-[#d63a3a] p-6 shadow-md flex flex-col justify-between h-[140px] relative overflow-hidden">
            <div class="bg-white/20 w-max p-2.5 rounded-xl backdrop-blur-sm shadow-sm mb-auto z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
            </div>
            <svg class="absolute right-[-10px] bottom-[-20px] w-28 h-28 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
            <div class="z-10">
                <p class="text-white/90 font-bold text-sm uppercase tracking-wide">All Orders</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $totalOrders }}</p>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-yellow-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-yellow-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Pending</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingOrders }}</p>
            </div>
        </div>

        {{-- Processing Orders --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-blue-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Processing</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $processingOrders }}</p>
            </div>
        </div>

        {{-- Completed Orders --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[140px]">
            <div class="bg-green-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Completed</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $completedOrders }}</p>
            </div>
        </div>
    </div>

    {{-- Problem Orders Alert --}}
    @if($problemOrders > 0)
        <div class="mb-8 rounded-xl bg-red-50 border-l-4 border-red-500 p-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800">Attention Required</p>
                    <p class="text-sm text-red-600">There are <span class="font-bold">{{ $problemOrders }}</span> problem orders (Cancelled or Failed) in the system.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Data Table Container --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Filter Toolbar --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-5 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700">Order Logs</h3>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search ID or customer..." class="h-10 pl-9 pr-4 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-all w-[240px]" />
                </div>
                <div class="relative">
                    <select name="status" class="h-10 w-[160px] appearance-none rounded-lg border border-gray-200 pl-4 pr-10 text-sm outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors bg-white">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                        @foreach($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
                <button type="submit" class="h-10 rounded-lg bg-gray-800 hover:bg-black transition-colors px-5 text-sm text-white font-medium shadow-sm">Search</button>
                @if($search !== '' || $status !== 'all')
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-red-500 hover:text-red-700 ml-2">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full whitespace-nowrap border-collapse text-left">
                <thead class="bg-white">
                    <tr class="border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="py-4 pl-6 pr-4">Order #</th>
                        <th class="py-4 px-4">Date</th>
                        <th class="py-4 px-4">Customer</th>
                        <th class="py-4 px-4">Payment</th>
                        <th class="py-4 px-4">Total</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($orders as $order)
                        @php
                            $isTimedOut = $order->order_status === 'Processing' && $order->order_date->lte(now()->subDays($timeoutDays));
                            
                            // Define Badge Colors
                            $badgeColor = match($order->order_status) {
                                'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'Processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Completed' => 'bg-green-100 text-green-800 border-green-200',
                                'Cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                                'Failed' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                        @endphp
                        <tr class="transition-colors hover:bg-gray-50/50 group">
                            <td class="py-4 pl-6 pr-4 font-semibold text-gray-800">#{{ $order->order_id }}</td>
                            <td class="py-4 px-4 font-medium text-gray-500">{{ optional($order->order_date)->format('M j, Y') ?? '—' }}</td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-gray-900">{{ trim(($order->customer->first_name ?? '') . ' ' . ($order->customer->last_name ?? '')) ?: '—' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $order->customer->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium">{{ $order->paymentMethod->methodName ?? '—' }}</span>
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-900">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $badgeColor }}">
                                    {{ $order->order_status }}
                                </span>
                                @if($isTimedOut)
                                    <div class="mt-1 flex items-center gap-1 text-xs font-semibold text-red-500">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Timed out ({{ $timeoutDays }}+ days)
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 transition-opacity">
                                    
                                    {{-- Start Processing Button --}}
                                    @if($order->order_status === 'Pending')
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="start_processing" />
                                            <button type="submit" class="flex items-center gap-1.5 h-[34px] rounded-lg bg-[#FCAE42] hover:bg-yellow-500 transition-colors px-3 text-xs font-bold text-black shadow-sm" title="Process Order">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                                                Process
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Timeout Fail Button --}}
                                    @if($isTimedOut)
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="timeout_fail" />
                                            <button type="submit" class="flex items-center gap-1 h-[34px] rounded-lg bg-red-500 hover:bg-red-600 transition-colors px-3 text-xs font-bold text-white shadow-sm">
                                                Fail Timeout
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Cancel Button --}}
                                    @if(in_array($order->order_status, ['Pending', 'Processing'], true))
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="cancel" />
                                            <button type="submit" class="flex items-center gap-1 h-[34px] rounded-lg border border-gray-300 bg-white hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors px-3 text-xs font-bold text-gray-600 shadow-sm" title="Cancel Order">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Cancel
                                            </button>
                                        </form>
                                    @endif

                                    {{-- No Actions state --}}
                                    @if(!in_array($order->order_status, ['Pending', 'Processing'], true) && !$isTimedOut)
                                        <span class="text-xs text-gray-400 font-medium px-2">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <p class="text-gray-500 font-medium">No orders match your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500 bg-gray-50/50">
            <span class="font-medium">Showing {{ $orders->count() }} of {{ $orders->total() }} items</span>
            <div>{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection