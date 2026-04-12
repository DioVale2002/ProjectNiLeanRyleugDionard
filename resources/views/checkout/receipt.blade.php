<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Order Confirmed - NCB</title>
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @php
        $items = $order->cart->items;
        $subtotal = (float) $items->sum('subtotal');
        $discount = $order->voucher
            ? ($order->voucher->voucherType === 'percentage'
                ? $subtotal * ((float) $order->voucher->voucherAmount / 100)
                : min($subtotal, (float) $order->voucher->voucherAmount))
            : 0;
    @endphp

    <div class="mx-[219px] mt-[100px] h-full">

        {{-- Steps --}}
        <div class="grid grid-cols-2 md:grid-cols-7 gap-2 md:gap-3 items-center mb-8">
            <a href="{{ route('cart.index') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                1 Summary
            </a>
            <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
            <a href="{{ route('checkout.address') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                2 Address
            </a>
            <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
            <a href="{{ route('checkout.payment') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                3 Payment
            </a>
            <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
            <div class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold">
                4 Receipt
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Success Message & Order Details --}}
            <div class="lg:col-span-2">
                {{-- Success Card --}}
                <div class="bg-white rounded-lg border border-gray-200 p-8 text-center shadow-sm mb-6">
                    <div class="mb-6">
                        <div class="mx-auto mb-4 rounded-full flex items-center justify-center bg-green-50 text-green-600 font-bold text-[34px] leading-none" style="width:64px;height:64px;">
                            &#10003;
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
                        <p class="text-gray-600">Thank you for your purchase. Your order has been successfully placed.</p>
                    </div>
                    <div class="border-t border-gray-200 pt-6">
                        <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                        <p class="text-4xl font-bold text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Reference Number</p>
                            <p class="text-lg font-semibold text-gray-900">{{ str_pad((string) $order->order_id, 12, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Order Date & Time</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('M d, Y · H:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->paymentMethod->methodName }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Customer</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Pricing Breakdown</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal ({{ $items->count() }} items)</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Discount</span>
                                    <span class="font-medium text-green-600">- ₱{{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-gray-900">TBD</span>
                            </div>
                            <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-semibold text-lg">
                                <span>Total</span>
                                <span class="text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <a href="{{ route('account.orders') }}" class="bg-[#ED1B24] text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition text-center">
                        View My Orders
                    </a>
                    <a href="{{ route('catalog.index') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition text-center">
                        Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>

                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}</span>
                            <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="font-medium text-green-600">- ₱{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">TBD</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-lg font-bold">
                        <span class="text-gray-900">Total</span>
                        <span class="text-[#ED1B24]">₱{{ number_format($order->total_price, 2) }}</span>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-800">📧 A confirmation email has been sent to {{ $order->customer->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
