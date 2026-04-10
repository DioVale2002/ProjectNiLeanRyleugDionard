<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Archived Orders - NCB</title>
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
            @include('partials.account-nav', ['active' => 'archived'])

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Archived Orders</h2>

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">No Orders yet</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-gray-50">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <p class="font-bold text-lg text-gray-900">Order #{{ str_pad((string) $order->order_id, 8, '0', STR_PAD_LEFT) }}</p>
                                                <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Completed
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
                                                    <div class="bg-white rounded-lg w-full aspect-square flex items-center justify-center mb-2 overflow-hidden border border-gray-200">
                                                        <img src="/images/SampleBook.png" alt="{{ $item->product->Title }}" class="h-full w-full object-cover" />
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $item->product->Title }}</p>
                                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            @endforeach
                                            @if($order->cart->items->count() > 4)
                                                <div class="flex items-center justify-center bg-gray-200 rounded-lg border border-gray-300">
                                                    <p class="text-sm font-medium text-gray-700">+{{ $order->cart->items->count() - 4 }} more</p>
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