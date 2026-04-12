<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Shopping Cart - NCB</title>
</head>

<body class="bg-gray-50">

    @include('partials.header')

    {{-- Messages --}}
    @if(session('success'))
    <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-red-50 border border-red-200text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mx-4 md:mx-10 xl:mx-[261px] mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg space-y-1">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8 mb-16">

        {{-- Checkout Progress --}}
        <div class="mb-8">
            <p class="text-3xl font-bold mb-5">Your Cart</p>

            {{-- Steps --}}
            <div class="grid grid-cols-2 md:grid-cols-7 gap-2 md:gap-3 items-center">
                <div class="h-11 flex items-center justify-center border border-[#ED1B24] bg-[#ED1B24] text-white rounded-sm text-sm font-semibold">
                    1 Summary
                </div>
                <div class="hidden md:block h-[2px] bg-[#ED1B24]"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    2 Address
                </div>
                <div class="hidden md:block h-[2px] bg-gray-300"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    3 Payment
                </div>
                <div class="hidden md:block h-[2px] bg-gray-300"></div>
                <div class="h-11 flex items-center justify-center border border-gray-300 bg-white rounded-sm text-sm font-semibold text-gray-600">
                    4 Receipt
                </div>
            </div>
        </div>

        {{-- Main content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart items --}}
            <div class="lg:col-span-2">
                @if(!$cart || $cart->items->isEmpty())
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-600 mb-6">Add items to get started with your order</p>
                    <a href="{{ route('catalog.index') }}" class="inline-block bg-[#ED1B24] text-white font-semibold px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Continue Shopping
                    </a>
                </div>
                @else
                <div class="space-y-4">
                    {{-- Item count --}}
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $cart->items->count() }} item{{ $cart->items->count() !== 1 ? 's' : '' }} in cart
                        </h2>
                        <a href="{{ route('catalog.index') }}" class="text-[#ED1B24] hover:underline text-sm font-medium">
                            Continue Shopping
                        </a>
                    </div>

                    {{-- Cart items --}}
                    @foreach($cart->items as $item)
                    <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition">
                        <div class="flex gap-4">
                            {{-- Image --}}
                            <div class="flex-shrink-0">
                                <img src="/images/SampleBook.png" alt="{{ $item->product->Title }}" class="w-24 h-32 object-cover rounded" />
                            </div>

                            {{-- Details --}}
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $item->product->Title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">by {{ $item->product->Author }}</p>
                                <p class="text-lg font-bold text-[#ED1B24]">₱{{ number_format($item->unitPrice, 2) }} each</p>
                            </div>

                            {{-- Quantity & Actions --}}
                            <div class="flex flex-col items-end justify-between">
                                <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 text-sm font-medium">
                                        Remove
                                    </button>
                                </form>

                                {{-- Quantity controls --}}
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center border border-gray-300 rounded-lg">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" onclick="this.closest('form').querySelector('input[name=quantity]').stepDown(); this.closest('form').submit();" class="px-3 py-1 text-gray-600 hover:bg-gray-100">−</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->Stock }}" class="w-12 text-center border-0 focus:outline-none" readonly />
                                    <button type="button" onclick="this.closest('form').querySelector('input[name=quantity]').stepUp(); this.closest('form').submit();" class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                                </form>

                                <p class="font-bold text-gray-900 text-right">
                                    ₱{{ number_format($item->subtotal, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Voucher section --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 mt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Apply Voucher Code</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select id="voucher-select" name="voucher_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent">
                            <option value="">-- Select a voucher --</option>
                            @foreach(\App\Models\Voucher::all() as $voucher)
                            <option value="{{ $voucher->voucher_id }}" {{ old('voucher_id') == $voucher->voucher_id ? 'selected' : '' }}>
                                {{ $voucher->voucherName }} ({{ $voucher->voucherType === 'percentage'
                                            ? $voucher->voucherAmount . '% off'
                                            : '₱' . number_format($voucher->voucherAmount, 2) . ' off' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>

            {{-- Order Summary Sidebar --}}
            @if($cart && !$cart->items->isEmpty())
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-24 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>

                    {{-- Breakdown --}}
                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">₱{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-gray-900">TBD</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-gray-900">TBD</span>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between mb-6 text-lg">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-[#ED1B24]">₱{{ number_format($total, 2) }}</span>
                    </div>

                    {{-- Checkout button --}}
                    <form action="{{ route('checkout.address') }}" method="GET">
                        <input id="voucher-id-hidden" type="hidden" name="voucher_id" value="">
                        <button type="submit" class="w-full bg-[#ED1B24] text-white font-bold py-3 rounded-lg hover:bg-red-700 transition duration-200 text-center">
                            Proceed to Checkout
                        </button>
                    </form>

                    {{-- Continue shopping --}}
                    <a href="{{ route('catalog.index') }}" class="block text-center text-[#ED1B24] font-medium py-3 hover:underline text-sm">
                        Continue Shopping
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    @include('partials.footer')

</body>
<script>
    const voucherSelect = document.getElementById('voucher-select');
    const voucherHidden = document.getElementById('voucher-id-hidden');
    if (voucherSelect && voucherHidden) {
        voucherHidden.value = voucherSelect.value;
        voucherSelect.addEventListener('change', () => {
            voucherHidden.value = voucherSelect.value;
        });
    }
</script>

</html>