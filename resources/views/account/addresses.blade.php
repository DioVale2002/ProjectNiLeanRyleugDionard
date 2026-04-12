<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Address</title>
</head>

<body class="">
    @include('partials.header')

    @php
    $customer = Auth::guard('customer')->user();
    @endphp

    <div class="mx-[219px] mt-[100px] h-full flex gap-[63px]">
        <div>
            <p class="text-[36px] font-bold mb-5">Your Cart</p>
            <div class="flex">
                <a href="#">
                    <div class="h-[52px] flex items-center justify-center border border-gray-400">
                        <p class="py-[15px] px-[80px]">1 Summary</p>
                    </div>
                </a>

                <a href="#">
                    <div class="h-[52px] flex items-center justify-center border border-black-400 bg-gray-300">
                        <p class="py-[15px] px-[80px]">2 Adress</p>
                    </div>
                </a>

                <a href="#">
                    <div class="h-[52px] flex items-center justify-center border border-gray-400">
                        <p class="py-[15px] px-[80px]">3 Payment</p>
                    </div>
                </a>

                <a href="#">
                    <div class="h-[52px] flex items-center justify-center border border-gray-400">
                        <p class="py-[15px] px-[80px]">4 Receipt</p>
                    </div>
                </a>
            </div>

            <p class="mt-[50px] mb-[30px] text-gray-700">
                Make Sure to use your Correct Adress.
            </p>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                @foreach($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="">
                <div class="m-7">
                    <p class="font-bold text-[32px]">My Address</p>
                    <hr class="my-4 border-gray-300" />

                    <form action="{{ route('account.address.update') }}" method="POST" class="">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center">
                            <div class="mt-6 mr-4 flex flex-col justify-center gap-18" style="gap: 4.5rem;">
                                <label class="text-[20px] font-bold" for="country">Country</label>
                                <label class="text-[20px] font-bold" for="province">Province</label>
                                <label class="text-[20px] font-bold" for="city">City</label>
                                <label class="text-[20px] font-bold" for="barangay">Barangay</label>
                                <label class="text-[20px] font-bold" for="zip_postal_code">Zip/Postal Code</label>
                            </div>

                            <div class="mt-6 ml-7 flex flex-col items-center justify-center gap-12">
                                <input
                                    class="border border-gray-400 rounded-sm h-[54px] w-[702px] px-4"
                                    type="text"
                                    name="country"
                                    id="country"
                                    value="{{ old('country', $address?->country) }}" />
                                <input
                                    class="border border-gray-400 rounded-sm h-[54px] w-[702px] px-4"
                                    type="text"
                                    name="province"
                                    id="province"
                                    value="{{ old('province', $address?->province) }}" />
                                <input
                                    class="border border-gray-400 rounded-sm h-[54px] w-[702px] px-4"
                                    type="text"
                                    name="city"
                                    id="city"
                                    value="{{ old('city', $address?->city) }}" />
                                <input
                                    class="border border-gray-400 rounded-sm h-[54px] w-[702px] px-4"
                                    type="text"
                                    name="barangay"
                                    id="barangay"
                                    value="{{ old('barangay', $address?->barangay) }}" />
                                <input
                                    class="border border-gray-400 rounded-sm h-[54px] w-[702px] px-4"
                                    type="text"
                                    name="zip_postal_code"
                                    id="zip_postal_code"
                                    value="{{ old('zip_postal_code', $address?->zip_postal_code) }}" />
                            </div>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button
                                type="submit"
                                class="bg-[#ED1B24] text-white font-bold py-3 px-24 rounded-md hover:bg-[#c1101a]">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-16">
                <p class="font-bold text-[17px] mb-4">Apply Voucher Code</p>
                <div class="flex items-center">
                    <form action="">
                        <input
                            class="h-[36px] w-[397px] border p-2.5 border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2.5"
                            type="text"
                            placeholder="Voucher Code" />
                        <button
                            type="button"
                            class="bg-[#FCAE42] text-black h-[36px] px-4 hover:bg-[#F54E4E] hover:text-white">
                            Apply
                        </button>
                    </form>
                </div>
            </div>

            <a href="{{ url('/catalog') }}" class="flex items-center mt-8 gap-5">
                <img src="{{ asset('images/checkout-img/backbtn.png') }}" alt="Back" />
                <p class="font-bold">Continue Shopping</p>
            </a>
        </div>

        <div>
            <p class="text-[30px] mb-5">Order Summary</p>

            <div class="flex justify-between mt-7 gap-40 px-11">
                <p class="text-[20px]">3 Items</p>
                <p class="text-[20px]">₱4,126</p>
            </div>

            <div class="flex justify-between gap-40 px-11">
                <p class="text-[20px]">Shipping</p>
                <p class="text-[20px]">-</p>
            </div>

            <hr class="my-5" />

            <div class="flex justify-between gap-40 px-11">
                <p class="text-[20px]">Total</p>
                <p class="text-[20px]">₱4,126</p>
            </div>

            <div class="mt-10">
                <a
                    href="#"
                    class="bg-[#FCAE42] text-black text-center text-[16px] h-[40px] px-[102px] font-semibold py-4 rounded-sm">
                    Proceed to Payment
                </a>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>