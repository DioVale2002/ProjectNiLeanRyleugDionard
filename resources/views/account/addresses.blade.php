<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Addresses - NCB</title>
</head>

<body class="bg-gray-50">
    @include('partials.header')
    @php $customer = Auth::guard('customer')->user(); @endphp

    <div class="mx-4 md:mx-10 xl:mx-[261px] py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Your Account</h1>
            <p class="text-gray-600">Welcome back, <span class="font-semibold">{{ $customer->first_name }} {{ $customer->last_name }}</span></p>
        </div>

        <div class="flex flex-col xl:flex-row gap-8">
            {{-- Sidebar Navigation --}}
            @include('partials.account-nav', ['active' => 'addresses'])

            {{-- Main Content --}}
            <div class="flex-1 min-w-0">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Delivery Address</h2>

                    @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 space-y-1">
                        @foreach($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <form action="{{ route('account.address.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Country --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <input type="text" name="country"
                                value="{{ old('country', $address?->country) }}"
                                placeholder="e.g., Philippines"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                        </div>

                        {{-- Province & City --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                                <input type="text" name="province"
                                    value="{{ old('province', $address?->province) }}"
                                    placeholder="e.g., Metro Manila"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                                <input type="text" name="city"
                                    value="{{ old('city', $address?->city) }}"
                                    placeholder="e.g., Manila"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                        </div>

                        {{-- Barangay & Postal Code --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Barangay</label>
                                <input type="text" name="barangay"
                                    value="{{ old('barangay', $address?->barangay) }}"
                                    placeholder="Barangay name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="zip_postal_code"
                                    value="{{ old('zip_postal_code', $address?->zip_postal_code) }}"
                                    placeholder="e.g., 1000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ED1B24] focus:border-transparent" />
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="bg-[#ED1B24] text-white font-semibold px-8 py-3 rounded-lg hover:bg-red-700 transition">
                                Save Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</body>

</html>