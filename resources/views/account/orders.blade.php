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

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
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
                                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full">
                                                    @if($order->order_status === 'Pending')
                                        <span class="inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8"></circle></svg> Pending</span>
                                                    @elseif($order->order_status === 'Completed')
                                        <span class="inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Completed</span>
                                                    @else
                                        {{ $order->order_status }}
                                                    @endif
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-2">{{ $order->order_date->format('B d, Y') }} • {{ $order->cart->items->count() }} item{{ $order->cart->items->count() !== 1 ? 's' : '' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</p>
                                            <p class="text-sm text-gray-600">{{ $order->paymentMethod->methodName }}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                            @foreach($order->cart->items->take(4) as $item)
                                                <div class="flex flex-col">
                                                    <div class="bg-gray-100 rounded-lg w-full aspect-square flex items-center justify-center mb-2 overflow-hidden">
                                                        <img src="/images/SampleBook.png" alt="{{ $item->product->Title }}" class="h-full w-full object-cover" />
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $item->product->Title }}</p>
                                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            @endforeach
                                            @if($order->cart->items->count() > 4)
                                                <div class="flex items-center justify-center bg-gray-100 rounded-lg">
                                                    <p class="text-sm font-medium text-gray-600">+{{ $order->cart->items->count() - 4 }} more</p>
                                                </div>
                                            @endif
                                        </div>
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