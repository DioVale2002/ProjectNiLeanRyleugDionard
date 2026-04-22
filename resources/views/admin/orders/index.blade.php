@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-3.5">
        <p class="text-xl text-black/60">Order Monitoring</p>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl bg-[#F54E4E] px-4 py-3 h-[108px] text-white shadow-sm">
            <p class="text-sm">All Orders</p>
            <p class="mt-8 text-2xl font-semibold">{{ $totalOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $pendingOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Processing</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $processingOrders }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $completedOrders }}</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 mb-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Monitor Orders</h3>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search order ID / customer" class="h-[42px] w-[260px] rounded-md border border-gray-300 px-4 text-sm outline-none" />
                <select name="status" class="h-[42px] w-[180px] rounded-md border border-gray-300 px-4 text-sm outline-none">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All statuses</option>
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" {{ $status === $statusOption ? 'selected' : '' }}>{{ $statusOption }}</option>
                    @endforeach
                </select>
                <button type="submit" class="h-[42px] rounded-md bg-[#FCAE42] px-5 text-sm text-white">Search</button>
                @if($search !== '' || $status !== 'all')
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        <div class="mb-4 rounded-lg bg-[#FFF3E6] px-4 py-3 text-sm text-[#9A5A00]">
            Problem Orders (Cancelled/Failed): <span class="font-semibold">{{ $problemOrders }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-black">
                        <th class="py-3">Order #</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Payment</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Current Status</th>
                        <th class="py-3">Events</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isTimedOut = $order->order_status === 'Processing' && $order->order_date->lte(now()->subDays($timeoutDays));
                        @endphp
                        <tr class="border-b border-gray-100">
                            <td class="py-3">{{ $order->order_id }}</td>
                            <td class="py-3">{{ optional($order->order_date)->format('M j, Y') ?? '—' }}</td>
                            <td class="py-3">
                                {{ trim(($order->customer->first_name ?? '') . ' ' . ($order->customer->last_name ?? '')) ?: '—' }}
                                <p class="text-xs text-gray-500">{{ $order->customer->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-3">{{ $order->paymentMethod->methodName ?? '—' }}</td>
                            <td class="py-3">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="py-3">
                                {{ $order->order_status }}
                                @if($isTimedOut)
                                    <p class="text-xs text-amber-600">Timed out ({{ $timeoutDays }}+ days)</p>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($order->order_status === 'Pending')
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="start_processing" />
                                            <button type="submit" class="h-[32px] rounded-md bg-[#FCAE42] px-3 text-xs text-white">Start Processing</button>
                                        </form>
                                    @endif

                                    @if($order->order_status === 'Processing')
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="ship" />
                                            <button type="submit" class="h-[32px] rounded-md bg-blue-500 px-3 text-xs text-white">Ship</button>
                                        </form>
                                    @endif

                                    @if($order->order_status === 'Shipped')
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="deliver" />
                                            <button type="submit" class="h-[32px] rounded-md bg-green-600 px-3 text-xs text-white">Deliver</button>
                                        </form>
                                    @endif

                                    @if($isTimedOut)
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="timeout_fail" />
                                            <button type="submit" class="h-[32px] rounded-md bg-amber-500 px-3 text-xs text-white">Mark Timeout Failed</button>
                                        </form>
                                    @endif

                                    @if(in_array($order->order_status, ['Pending', 'Processing', 'Shipped'], true))
                                        <form action="{{ route('admin.orders.event', $order) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="event" value="cancel" />
                                            <button type="submit" class="h-[32px] rounded-md bg-[#F54E4E] px-3 text-xs text-white">Cancel</button>
                                        </form>
                                    @endif

                                    @if(!in_array($order->order_status, ['Pending', 'Processing', 'Shipped'], true) && !$isTimedOut)
                                        <span class="text-xs text-gray-400">No actions</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <span>{{ $orders->total() }} items</span>
            <div>{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
