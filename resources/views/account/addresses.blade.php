<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>User Account - Address</title>
</head>

<body class="">
    @include('partials.header')

    @php
    $customer = Auth::guard('customer')->user();
    @endphp

    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ $customer->first_name }} {{ $customer->last_name }},</p>
            <p class="text-[17px] text-black/50 mr-1">Email:</p>
            <p class="text-[17px] text-black/50">{{ $customer->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">

        @include('partials.account-nav', ['active' => 'addresses'])

        <div class="border border-black/50 rounded-lg ml-[63px] w-[900px] h-full">
            <div class="m-7">
                <p class="font-bold text-[32px]">My Address</p>
                <hr class="my-4 border-gray-300" />

                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-sm mb-6">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-sm mb-6 space-y-1">
                    @foreach($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('account.address.update') }}" method="POST" class="">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center">
                        <div class="mt-6 mr-4 flex flex-col justify-center" style="gap: 4.5rem;">
                            <label class="text-[20px] font-bold" for="country">Country</label>
                            <label class="text-[20px] font-bold" for="province">Province</label>
                            <label class="text-[20px] font-bold" for="city">City</label>
                            <label class="text-[20px] font-bold" for="barangay">Barangay</label>
                            <label class="text-[20px] font-bold" for="zip_postal_code">Zip/Postal Code</label>
                        </div>

                        <div class="mt-6 ml-7 flex flex-col items-center justify-center gap-12">
                            <input
                                class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4"
                                type="text"
                                name="country"
                                id="country"
                                value="{{ old('country', $address?->country) }}" />
                            <input
                                class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4"
                                type="text"
                                name="province"
                                id="province"
                                value="{{ old('province', $address?->province) }}" />
                            <input
                                class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4"
                                type="text"
                                name="city"
                                id="city"
                                value="{{ old('city', $address?->city) }}" />
                            <input
                                class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4"
                                type="text"
                                name="barangay"
                                id="barangay"
                                value="{{ old('barangay', $address?->barangay) }}" />
                            <input
                                class="border border-gray-400 rounded-sm h-[54px] w-[602px] px-4"
                                type="text"
                                name="zip_postal_code"
                                id="zip_postal_code"
                                value="{{ old('zip_postal_code', $address?->zip_postal_code) }}" />
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 mr-7">
                        <button
                            type="submit"
                            class="bg-[#ED1B24] text-white font-bold py-3 px-24 rounded-md hover:bg-[#c1101a]">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

</html>