<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Payment Method - NCB</title>
</head>
<body class="bg-gray-50">

    @include('partials.header')

    @if($errors->any())
        <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg space-y-1">
            @foreach($errors->all() as $error)
                <p class="text-sm font-bold">{{ $error }}</p>
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
            {{-- Payment Methods Form --}}
            <div class="lg:col-span-2">
                <form action="{{ route('checkout.payment.confirm') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Payment Methods (Updated to match your custom design) --}}
                    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Select Payment Method</h2>

                        <div>
                            @foreach($paymentMethods as $method)
                                <div class="flex items-center gap-5 {{ $loop->first ? '' : 'mt-7' }}">
                                    <input
                                        type="radio"
                                        name="paymentMethod_id"
                                        id="method-{{ $method->paymentMethod_id }}"
                                        value="{{ $method->paymentMethod_id }}"
                                        class="appearance-none w-4 h-4 rounded-full border border-black checked:bg-black checked:border-black cursor-pointer"
                                        {{ (string) old('paymentMethod_id', $selectedPaymentMethodId ?? '') === (string) $method->paymentMethod_id ? 'checked' : '' }}
                                        required
                                    />
                                    <label for="method-{{ $method->paymentMethod_id }}" class="flex items-center cursor-pointer text-lg">
                                        
                                        {{-- Dynamically display the correct image based on the method name --}}
                                        @if(str_contains(strtolower($method->methodName), 'gcash'))
                                            <img src="{{ asset('images/checkout-img/gcash.png') }}" class="mr-5" alt="GCash" />

                                        {{-- Then check for regular Cash/COD --}}
                                        @elseif(str_contains(strtolower($method->methodName), 'cash') || str_contains(strtolower($method->methodName), 'cod'))
                                            <img src="{{ asset('images/checkout-img/cod.png') }}" class="mr-5" alt="COD" />

                                        {{-- Finally check for Banking --}}
                                        @elseif(str_contains(strtolower($method->methodName), 'bank') || str_contains(strtolower($method->methodName), 'online'))
                                            <img src="{{ asset('images/checkout-img/olbank.png') }}" class="mr-5" alt="Online Banking" />
                                        @endif
                                        
                                        {{ $method->methodName }}
                                    </label>
                                </div>
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
                            Back
                        </a>
                        <button type="submit" class="bg-[#ED1B24] text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition ml-auto">
                            Place Order
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