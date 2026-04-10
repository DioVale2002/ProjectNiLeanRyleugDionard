<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>My Orders - NCB</title>
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @php $customer = Auth::guard('customer')->user(); @endphp

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Your Account</h1>
            <p class="text-gray-600">Welcome back, <span class="font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</span></p>
        </div>

        <div class="flex flex-col xl:flex-row gap-8">
            {{-- Sidebar Navigation --}}
            @include('partials.account-nav', ['active' => 'orders'])

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h2>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No Orders yet</p>
                            <a href="{{ route('catalog.index') }}" class="text-[#ED1B24] hover:underline mt-2 inline-block font-medium">Start shopping →</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <p class="font-bold text-lg text-gray-900">Order #{{ str_pad((string) $order->order_id, 8, '0', STR_PAD_LEFT) }}</p>
                                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full inline-flex items-center">
                                                    @if($order->order_status === 'Pending')
                                        Pending
                                                    @elseif($order->order_status === 'Completed')
                                        Completed
                                                    @else
                                        {{ $order->order_status }}
                                                    @endif
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-2">{{ $order->order_date->format('M d, Y') }} • {{ $order->cart->items->count() }} item{{ $order->cart->items->count() !== 1 ? 's' : '' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</p>
                                            <p class="text-sm text-gray-600">{{ $order->paymentMethod->methodName }}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($order->cart->items->take(4) as $item)
                                                <div class="w-[108px]">
                                                    <div class="bg-gray-100 rounded-lg w-[108px] h-[144px] flex items-center justify-center mb-2 overflow-hidden border border-gray-200">
                                                        <img src="/images/SampleBook.png" alt="{{ $item->product->Title }}" class="w-[92px] h-[124px] object-cover" />
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $item->product->Title }}</p>
                                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            @endforeach
                                            @if($order->cart->items->count() > 4)
                                                <div class="w-[108px] h-[144px] flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
                                                    <p class="text-sm font-medium text-gray-600">+{{ $order->cart->items->count() - 4 }} more</p>
                                                </div>
                                            @endif
                                        </div>

                                        @if($order->order_status === 'Processing')
                                            <div class="mt-4 flex justify-end">
                                                <form action="{{ route('account.orders.received', $order) }}" method="POST" onsubmit="return confirm('Confirm that you received this order?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="rounded-md bg-[#ED1B24] px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                        I received this order
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>