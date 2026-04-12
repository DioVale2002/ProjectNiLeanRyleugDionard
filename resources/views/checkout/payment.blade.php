<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Payment Method - NCB</title>
</head>

<body class="bg-gray-50">

    @include('partials.header')

    @if($errors->any())
    <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg space-y-1">
        @foreach($errors->all() as $error)
        <p class="text-sm">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8 mb-16">
        {{-- Checkout Progress --}}
        <div class="mb-8">
            <p class="text-3xl font-bold mb-5">Payment</p>

            {{-- Steps --}}
            <div class="grid grid-cols-2 md:grid-cols-7 gap-2 md:gap-3 items-center">
                <a href="{{ route('cart.index') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                    1 Summary
                </a>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <a href="{{ route('checkout.address') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                    2 Address
                </a>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <div class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold">
                    3 Payment
                </div>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    4 Receipt
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Payment Methods --}}
            <div class="lg:col-span-2">
                <form action="{{ route('checkout.payment.confirm') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Payment Methods --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

                        <div class="space-y-3">
                            @foreach($paymentMethods as $method)
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#ED1B24] hover:bg-red-50 transition" id="method-{{ $method->paymentMethod_id }}">
                                <input
                                    type="radio"
                                    name="paymentMethod_id"
                                    value="{{ $method->paymentMethod_id }}"
                                    {{ (string) old('paymentMethod_id', $selectedPaymentMethodId) === (string) $method->paymentMethod_id ? 'checked' : '' }}
                                    onchange="document.querySelectorAll('[id^=method-]').forEach(el => el.classList.remove('border-[#ED1B24]', 'bg-red-50')); this.closest('label').classList.add('border-[#ED1B24]', 'bg-red-50')"
                                    class="appearance-none w-5 h-5 rounded-full border-2 border-gray-400 checked:bg-[#ED1B24] checked:border-[#ED1B24] mr-4"
                                    required />
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        @if(str_contains(strtolower($method->methodName), 'cash'))
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                        @elseif(str_contains(strtolower($method->methodName), 'gcash'))
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                                            <path fill-opacity=".2" d="M3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"></path>
                                            <path d="M14 9a1 1 0 00-1 1v6a1 1 0 001 1h1a1 1 0 001-1v-6a1 1 0 00-1-1h-1z"></path>
                                        </svg>
                                        @elseif(str_contains(strtolower($method->methodName), 'bank'))
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        @endif
                                        <div>
                                            <p class="text-lg font-semibold text-gray-900">{{ $method->methodName }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Address Summary --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Delivery Address</h3>
                        <div class="text-gray-600 text-sm space-y-1">
                            <p class="font-medium">{{ $address->barangay }}, {{ $address->city }}</p>
                            <p>{{ $address->province }}, {{ $address->country }}</p>
                            <p>{{ $address->zip_postal_code }}</p>
                        </div>
                        <a href="{{ route('checkout.address') }}" class="text-[#ED1B24] text-sm font-medium mt-4 inline-block hover:underline">Change address</a>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('checkout.address') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition text-center">
                            ← Back
                        </a>
                        <button type="submit" class="bg-[#ED1B24] text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition ml-auto">
                            Place Order →
                        </button>
                    </div>
                </form>
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>

                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $cart->items->count() }} item{{ $cart->items->count() !== 1 ? 's' : '' }}</span>
                            <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600">- ₱{{ number_format($discount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">TBD</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-[#ED1B24]">₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>