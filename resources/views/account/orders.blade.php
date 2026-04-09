<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>My Orders - NCB</title>
</head>
<body>

    @include('partials.header')

    @php $customer = Auth::guard('customer')->user(); @endphp

    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ $customer->first_name }} {{ $customer->last_name }},</p>
            <p class="text-[17px] text-black/50">Email: {{ $customer->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">

        {{-- Sidebar nav --}}
        @include('partials.account-nav', ['active' => 'orders'])

        {{-- Main content --}}
        <div class="flex-1">
            <p class="text-[28px] font-bold mb-4">My Orders</p>
            <hr class="mb-6 border-gray-300" />

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            @if($orders->isEmpty())
                <p class="text-[#ED1B24] text-[20px] mt-[50px]">No Orders yet.</p>
            @else
                @foreach($orders as $order)
                    <div class="border border-black/20 p-5 mb-5 rounded shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="font-bold text-[18px]">Order #{{ $order->order_id }}</p>
                                <p class="text-gray-500 text-[14px]">{{ $order->order_date->format('M d, Y') }}</p>
                                <p class="mt-1">Status:
                                    <span class="font-bold {{ $order->order_status === 'Pending' ? 'text-[#FCAE42]' : 'text-blue-500' }}">
                                        {{ $order->order_status }}
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[22px]">₱{{ number_format($order->total_price, 2) }}</p>
                                <p class="text-gray-500 text-[14px]">{{ $order->paymentMethod->methodName }}</p>
                            </div>
                        </div>
                        <hr class="border-gray-200 mb-3" />
                        @foreach($order->cart->items as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <img src="/images/SampleBook.png" alt="" class="w-[40px] h-[50px] object-cover" />
                                    <div>
                                        <p class="font-medium">{{ $item->product->Title }}</p>
                                        <p class="text-gray-500 text-[13px]">{{ $item->product->Author }}</p>
                                    </div>
                                </div>
                                <p class="text-[14px]">
                                    {{ $item->quantity }} x ₱{{ number_format($item->unitPrice, 2) }}
                                    = <span class="font-bold">₱{{ number_format($item->subtotal, 2) }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    @include('partials.footer')
</body>
</html>