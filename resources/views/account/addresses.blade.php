<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/output.css" />
    <title>Addresses - NCB</title>
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
        @include('partials.account-nav', ['active' => 'addresses'])
        <div class="flex-1">
            <p class="text-[28px] font-bold mb-4">Delivery Address</p>
            <hr class="mb-6 border-gray-300" />

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form action="{{ route('account.address.update') }}" method="POST" class="max-w-[500px]">
                @csrf
                @method('PUT')

                @foreach(['country' => 'Country', 'province' => 'Province', 'city' => 'City', 'barangay' => 'Barangay', 'zip_postal_code' => 'ZIP / Postal Code'] as $field => $label)
                    <div class="mb-4">
                        <label class="block text-[16px] font-medium text-gray-700 mb-1">{{ $label }}</label>
                        <input type="text" name="{{ $field }}"
                            value="{{ old($field, $address?->$field) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-[#FCAE42] text-[16px]" />
                    </div>
                @endforeach

                <button type="submit"
                    class="bg-[#FCAE42] text-black font-bold text-[16px] py-3 px-8 hover:bg-[#F54E4E] hover:text-white transition-colors mt-2">
                    Save Address
                </button>
            </form>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>