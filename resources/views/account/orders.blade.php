<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Orders - New Century Books</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @php 
        $customer = Auth::guard('customer')->user(); 
    @endphp

    {{-- Header Section (Matches Figma Design) --}}
    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ $customer->first_name }} {{ $customer->last_name }},</p>
            <p class="text-[17px] text-black/50 mr-1">Email:</p>
            <p class="text-[17px] text-black/50">{{ $customer->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">
        
        {{-- Navigation Sidebar --}}
        <div class="w-[342px]">
            @include('partials.account-nav', ['active' => 'orders'])
        </div>

        {{-- Main Content: Active Orders List --}}
        <div class="border border-black/50 rounded-lg ml-[63px] w-[1000px] h-full bg-white shadow-sm">
            <div class="mx-7 mt-7 pb-7">
                <h2 class="text-[25px] font-bold mb-6">My Orders</h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">No active orders yet.</p>
                        <a href="{{ route('catalog.index') }}" class="text-[#ED1B24] font-bold hover:underline">Start Shopping →</a>
                    </div>
                @else
                    @foreach($orders as $order)
                        <div class="border border-gray-300 rounded-md p-5 mb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-[20px]">Order #{{ str_pad($order->order_id, 8, '0', STR_PAD_LEFT) }}</p>
                                    <div class="flex gap-2 items-center text-sm text-gray-600">
                                        <p>{{ $order->cart->items->count() }} Products |</p>
                                        <p>{{ $customer->first_name }} {{ $customer->last_name }} |</p>
                                        <p>{{ $order->order_date->format('H:i') }} |</p>
                                        <p>{{ $order->order_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-[24px] text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->paymentMethod->methodName }}</p>
                                </div>
                            </div>

                            <hr class="my-4 border-gray-200" />

                            <div class="flex items-center text-[15px]">
                                <div class="mr-8 text-gray-500 space-y-1">
                                    <p>Status:</p>
                                    <p>Date of Delivery:</p>
                                    <p>Delivered to:</p>
                                </div>
                                <div class="space-y-1">
                                    <p @class([
                                        'font-bold',
                                        'text-[#EA8C51]' => $order->order_status === 'Processing',
                                        'text-blue-600' => $order->order_status === 'Pending',
                                    ])>
                                        {{ $order->order_status }}
                                    </p>
                                    <p>{{ $order->order_date->addDays(3)->format('M d, Y') }} (Est.)</p>
                                    <p class="truncate w-[600px]">{{ $order->address->city ?? 'No city provided' }}</p>
                                </div>
                            </div>

                            {{-- Product Preview Grid with 80x100 Images --}}
                            <div class="mt-8 grid grid-cols-2 gap-6">
                                @foreach($order->cart->items->take(4) as $item)
                                    <div class="flex items-center">
                                        {{-- Container maintained for spacing, Image set to 80x100 --}}
                                        <div class="bg-[#E1F0F0] w-[103px] h-[120px] flex items-center justify-center rounded-sm border border-gray-100">
                                            <img 
                                                class="w-[80px] h-[100px] object-cover shadow-sm" 
                                                src="/images/SampleBook.png" 
                                                alt="{{ $item->product->Title }}"
                                            />
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-sm leading-tight h-10 overflow-hidden">{{ $item->product->Title }}</p>
                                            <p class="text-xs text-gray-500 mt-1 font-bold">Qty: {{ $item->quantity }}</p>
                                            <p class="text-xs text-gray-700">₱{{ number_format($item->unitPrice, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Mark as Received Button (Active Only) --}}
                            @if($order->order_status === 'Processing')
                                <div class="mt-6 flex justify-end">
                                    <form action="{{ route('account.orders.received', $order) }}" method="POST" onsubmit="return confirm('Confirm receipt of this order?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-[#ED1B24] text-white px-6 py-2 rounded-md font-bold hover:bg-red-700 transition shadow-sm">
                                            I received this order
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>