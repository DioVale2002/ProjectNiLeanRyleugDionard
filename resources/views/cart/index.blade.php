<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Your Cart - NCB</title>
</head>
<body>

    @include('partials.header')

    @if(session('success'))
        <div class="mx-[219px] mt-4 bg-green-100 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mx-[219px] mt-4 bg-red-100 text-red-800 px-4 py-3 rounded">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mx-[219px] mt-[60px] h-full flex gap-[63px] mb-[80px]">

        {{-- Left: Cart Items --}}
        <div class="flex-1">
            <p class="text-[36px] font-bold mb-5">Your Cart</p>

            {{-- Checkout steps --}}
            <div class="flex mb-8">
                <div class="h-[52px] flex items-center justify-center border border-gray-400 bg-gray-300">
                    <p class="py-[15px] px-[80px]">1 Summary</p>
                </div>
                <div class="h-[52px] flex items-center justify-center border border-gray-400">
                    <p class="py-[15px] px-[80px]">2 Address</p>
                </div>
                <div class="h-[52px] flex items-center justify-center border border-gray-400">
                    <p class="py-[15px] px-[80px]">3 Payment</p>
                </div>
                <div class="h-[52px] flex items-center justify-center border border-gray-400">
                    <p class="py-[15px] px-[80px]">4 Receipt</p>
                </div>
            </div>

            @if(!$cart || $cart->items->isEmpty())
                <p class="text-gray-700 mb-4">Your shopping cart contains: 0 products</p>
                <p class="font-bold">Your cart is empty</p>
                <a href="{{ route('catalog.index') }}" class="inline-block mt-6 text-[#ED1B24] hover:underline">
                    ← Continue Shopping
                </a>
            @else
                <p class="mb-[30px] text-gray-700">
                    Your shopping cart contains: {{ $cart->items->count() }} product(s)
                </p>

                @foreach($cart->items as $item)
                    <div class="flex border border-black/50 h-full mb-4">
                        {{-- Cover --}}
                        <div class="flex items-center justify-center border-r py-6 border-black/50 w-[174px]">
                            <img class="w-[80px] h-[100px] object-cover" src="/images/SampleBook.png" alt="{{ $item->product->Title }}" />
                        </div>
                        {{-- Title & Price --}}
                        <div class="flex flex-col justify-center border-r border-black/50 w-[487px] px-4">
                            <p class="mb-2 font-medium">{{ strtoupper($item->product->Title) }}</p>
                            <p class="text-gray-500 text-[14px]">{{ $item->product->Author }}</p>
                            <p class="mt-2">₱{{ number_format($item->unitPrice, 2) }} each</p>
                        </div>
                        {{-- Controls --}}
                        <div class="flex items-center justify-center flex-1">
                            <form class="flex items-center" action="{{ route('cart.update', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="ml-[40px] flex items-center border border-black/50">
                                    <button type="button" onclick="this.nextElementSibling.stepDown()"
                                        class="text-[25px] border-r border-black/50 px-5 cursor-pointer">-</button>
                                    <input class="w-[59px] h-full text-center py-3" type="number"
                                        name="quantity" value="{{ $item->quantity }}"
                                        min="1" max="{{ $item->product->Stock }}" />
                                    <button type="button" onclick="this.previousElementSibling.stepUp()"
                                        class="text-[25px] border-l border-black/50 px-5 cursor-pointer">+</button>
                                </div>
                                <button type="submit"
                                    class="ml-3 text-[13px] bg-gray-100 border border-gray-300 px-3 py-2 hover:bg-gray-200 cursor-pointer">
                                    Update
                                </button>
                            </form>

                            <p class="px-6 font-bold">₱{{ number_format($item->subtotal, 2) }}</p>

                            <form action="{{ route('cart.remove', $item) }}" method="POST"
                                  onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer mr-5 hover:opacity-70">
                                    <img src="/images/checkout-img/Trash.png" alt="Remove" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('catalog.index') }}" class="flex items-center mt-8 gap-5 text-[#ED1B24] hover:underline">
                    <img src="/images/checkout-img/backbtn.png" alt="" />
                    <p class="font-bold">Continue Shopping</p>
                </a>
            @endif
        </div>

        {{-- Right: Order Summary + Checkout --}}
        <div class="w-[380px] flex-shrink-0">
            <p class="text-[30px] mb-5">Order Summary</p>

            <div class="flex justify-between mt-7 gap-10 px-4">
                <p class="text-[20px]">{{ $cart ? $cart->items->count() : 0 }} Items</p>
                <p class="text-[20px]">₱{{ number_format($total, 2) }}</p>
            </div>
            <div class="flex justify-between gap-10 px-4">
                <p class="text-[20px]">Shipping</p>
                <p class="text-[20px]">-</p>
            </div>
            <hr class="my-5" />
            <div class="flex justify-between gap-10 px-4">
                <p class="text-[20px]">Total</p>
                <p class="text-[20px] font-bold">₱{{ number_format($total, 2) }}</p>
            </div>

            @if($cart && !$cart->items->isEmpty())
                <form action="{{ route('orders.store') }}" method="POST" class="mt-8">
                    @csrf

                    {{-- Payment method --}}
                    <div class="mb-4">
                        <p class="font-bold text-[17px] mb-2">Payment Method</p>
                        @foreach(\App\Models\PaymentMethod::all() as $method)
                            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                                <input type="radio" name="paymentMethod_id"
                                    value="{{ $method->paymentMethod_id }}"
                                    {{ old('paymentMethod_id') == $method->paymentMethod_id ? 'checked' : '' }}
                                    required />
                                @if(str_contains(strtolower($method->methodName), 'gcash'))
                                    <img src="/images/checkout-img/gcash.png" alt="GCash" class="h-6" />
                                @elseif(str_contains(strtolower($method->methodName), 'cash'))
                                    <img src="/images/checkout-img/cod.png" alt="COD" class="h-6" />
                                @elseif(str_contains(strtolower($method->methodName), 'bank'))
                                    <img src="/images/checkout-img/olbank.png" alt="Bank" class="h-6" />
                                @else
                                    <span class="text-[16px]">{{ $method->methodName }}</span>
                                @endif
                                <span class="text-[16px]">{{ $method->methodName }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Address --}}
                    @php $address = Auth::guard('customer')->user()->address; @endphp
                    <div class="mb-4">
                        <p class="font-bold text-[17px] mb-2">Delivery Address</p>
                        @if($address)
                            <div class="bg-gray-50 border border-gray-200 p-3 rounded text-[14px]">
                                <p>{{ $address->barangay }}, {{ $address->city }}</p>
                                <p>{{ $address->province }}, {{ $address->country }} {{ $address->zip_postal_code }}</p>
                            </div>
                            <input type="hidden" name="add_id" value="{{ $address->add_id }}">
                            <a href="{{ route('account.addresses') }}"
                                class="text-[#ED1B24] text-[13px] mt-1 inline-block hover:underline">
                                Change address
                            </a>
                        @else
                            <p class="text-gray-500 text-[14px]">No address set.
                                <a href="{{ route('account.addresses') }}" class="text-[#ED1B24] hover:underline">Add one →</a>
                            </p>
                        @endif
                    </div>

                    {{-- Voucher --}}
                    <div class="mb-6">
                        <p class="font-bold text-[17px] mb-2">Voucher Code</p>
                        <select name="voucher_id"
                            class="w-full border border-gray-400 p-2 focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[15px]">
                            <option value="">-- No Voucher --</option>
                            @foreach(\App\Models\Voucher::all() as $voucher)
                                <option value="{{ $voucher->voucher_id }}"
                                    {{ old('voucher_id') == $voucher->voucher_id ? 'selected' : '' }}>
                                    {{ $voucher->voucherName }}
                                    ({{ $voucher->voucherType === 'percentage'
                                        ? $voucher->voucherAmount . '% off'
                                        : '₱' . number_format($voucher->voucherAmount, 2) . ' off' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#FCAE42] text-black text-center text-[16px] h-[48px] font-semibold hover:bg-[#F54E4E] hover:text-white transition-colors">
                        Place Order
                    </button>
                </form>
            @endif
        </div>
    </div>

</body>
</html>