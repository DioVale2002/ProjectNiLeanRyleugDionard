<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Shipping Address - NCB</title>
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
            <p class="text-3xl font-bold mb-5">Shipping Address</p>

            {{-- Steps --}}
            <div class="grid grid-cols-2 md:grid-cols-7 gap-2 md:gap-3 items-center">
                <a href="{{ route('cart.index') }}" class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold hover:opacity-95">
                    1 Summary
                </a>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <div class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold">
                    2 Address
                </div>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    3 Payment
                </div>
                <div class="hidden md:block h-[2px] bg-gray-300"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    4 Receipt
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Enter Delivery Address</h2>
                    <p class="text-gray-600 text-sm mb-6">Please provide accurate information for timely delivery</p>

                    <form action="{{ route('checkout.address.save') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="voucher_id" value="{{ $selectedVoucherId }}">

                        {{-- Country --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <input type="text" name="country" value="{{ old('country', $address?->country) }}"
                                placeholder="Philippines"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                        </div>

                        {{-- Province & City --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                                <input type="text" name="province" value="{{ old('province', $address?->province) }}"
                                    placeholder="e.g., Metro Manila"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                                <input type="text" name="city" value="{{ old('city', $address?->city) }}"
                                    placeholder="e.g., Manila"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                        </div>

                        {{-- Barangay & Postal Code --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Barangay</label>
                                <input type="text" name="barangay" value="{{ old('barangay', $address?->barangay) }}"
                                    placeholder="Barangay name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="zip_postal_code" value="{{ old('zip_postal_code', $address?->zip_postal_code) }}"
                                    placeholder="e.g., 1000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-3 mt-8">
                            <a href="{{ route('cart.index') }}" class="bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-lg hover:bg-gray-200 transition text-center">
                                Back to Cart
                            </a>
                            <button type="submit" class="bg-[#ED1B24] text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition ml-auto">
                                Continue to Payment →
                            </button>
                        </div>
                    </form>
                </div>
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