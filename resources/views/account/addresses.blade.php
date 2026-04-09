<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/resources/css/app.css" />
    <title>User Account - Address</title>
</head>

<body class="">
    <div class="flex items-center justify-between px-[261px]">
        <a href="/"><img src="{{ asset('assets/Logo.png') }}" alt="Logo" class="" /></a>
        <div class="relative">
            <input
                type="text"
                placeholder="Search"
                class="w-[908px] h-[36.74px] px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" />
            <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.352 4.35a1 1 0 01-1.414 1.414l-4.352-4.35A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <p class="ml-4 text-gray-700 font-bold">{{ Auth::guard('customer')->user()->first_name }}</p>
        <a href="{{ route('account.security') }}" class="ml-4">
            <img src="/public/assets/User.png" alt="User profile" />
        </a>
        <a href="#" class="ml-4">
            <img src="/public/assets/cart.png" alt="Shopping cart" />
        </a>
    </div>

    <div class="h-12 w-screen bg-[#FCAE42] px-[261px] flex items-center justify-between">
        <a href="#" class="ml-7 text-black text-[20px] font-bold">Books</a>
        <a href="#" class="text-black text-[20px] font-bold">E-Books</a>
        <a href="#" class="text-black text-[20px] font-bold">Best Sellers</a>
        <a href="#" class="text-black text-[20px] font-bold">New</a>
        <a href="#" class="text-black text-[20px] font-bold">Collections</a>
        <a href="#" class="text-black text-[20px] font-bold">Sale</a>
    </div>

    <div class="ml-[282px] mt-[50px] mb-[50px]">
        <p class="text-[36px] text-black font-bold">Your Account</p>
        <div class="flex">
            <p class="text-[17px] text-black/50 mr-1">{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }},</p>
            <p class="text-[17px] text-black/50 mr-1">Email:</p>
            <p class="text-[17px] text-black/50">{{ Auth::guard('customer')->user()->email }}</p>
        </div>
    </div>

    <div class="flex mx-[282px] mb-[80px]">
        <div class="w-[342px]">
            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.orders') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.orders') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/public/assets/userAcc-img/delivery.png" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">My Orders</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.addresses') ? 'bg-[#ED1B24]/30' : '' }}" href="{{ route('account.addresses') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/public/assets/userAcc-img/adress.png" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Your Addresses</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.security') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.security') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/public/assets/userAcc-img/login.png" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Login & Security</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.archived') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.archived') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/public/assets/userAcc-img/archive.png" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Archive Orders</p>
            </a>

            <hr class="my-4 border-gray-300" />

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md cursor-pointer">
                    <div class="w-[70px] bg-white rounded-sm border border-black/30">
                        <img class="w-[50px] mx-2 my-2" src="/public/assets/userAcc-img/logout.png" alt="" />
                    </div>
                    <p class="text-black font-bold text-[25px] ml-3.5">Log Out</p>
                </button>
            </form>
        </div>

        <div class="border border-black/50 rounded-lg ml-[63px] w-[900px] h-full">
            <div class="m-7">

                @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
                @endif
                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="flex justify-between items-center">
                    <p class="font-bold text-[32px]">My Address</p>
                    <button type="button" id="editBtn" onclick="toggleEdit()" class="bg-gray-200 text-gray-800 font-bold py-2 px-6 rounded-md hover:bg-gray-300">
                        Edit Address
                    </button>
                </div>
                <hr class="my-4 border-gray-300" />

                <form method="POST" action="{{ route('account.address.update') }}" id="updateAddressForm">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center">
                        <div class="mt-6 mr-4 flex flex-col justify-center gap-12" style="gap: 3.5rem;">
                            <label class="text-[20px] font-bold" for="country">Country</label>
                            <label class="text-[20px] font-bold" for="province">Province</label>
                            <label class="text-[20px] font-bold" for="city">City</label>
                            <label class="text-[20px] font-bold" for="barangay">Barangay</label>
                            <label class="text-[20px] font-bold" for="zip_postal_code">Zip/Postal Code</label>
                        </div>

                        <div class="mt-6 ml-7 flex flex-col items-center justify-center gap-8" style="gap: 1.5rem;">
                            <input class="address-input border border-gray-400 bg-gray-100 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:ring-2 focus:ring-red-400"
                                type="text" name="country" id="country" value="{{ old('country', $address->country ?? '') }}" placeholder="Not set" readonly />

                            <input class="address-input border border-gray-400 bg-gray-100 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:ring-2 focus:ring-red-400"
                                type="text" name="province" id="province" value="{{ old('province', $address->province ?? '') }}" placeholder="Not set" readonly />

                            <input class="address-input border border-gray-400 bg-gray-100 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:ring-2 focus:ring-red-400"
                                type="text" name="city" id="city" value="{{ old('city', $address->city ?? '') }}" placeholder="Not set" readonly />

                            <input class="address-input border border-gray-400 bg-gray-100 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:ring-2 focus:ring-red-400"
                                type="text" name="barangay" id="barangay" value="{{ old('barangay', $address->barangay ?? '') }}" placeholder="Not set" readonly />

                            <input class="address-input border border-gray-400 bg-gray-100 rounded-sm h-[54px] w-[602px] px-4 focus:outline-none focus:ring-2 focus:ring-red-400"
                                type="text" name="zip_postal_code" id="zip_postal_code" value="{{ old('zip_postal_code', $address->zip_postal_code ?? '') }}" placeholder="Numbers only" readonly />
                        </div>
                    </div>

                    <div id="actionButtons" class="flex justify-end gap-4 mt-6 mr-7 hidden">
                        <button type="button" onclick="cancelEdit()" class="border border-gray-400 text-gray-600 font-bold py-3 px-10 rounded-md hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit" class="bg-[#ED1B24] text-white font-bold py-3 px-16 rounded-md hover:bg-[#c1101a]">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const inputs = document.querySelectorAll('.address-input');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                input.classList.remove('bg-gray-100'); // Remove gray background
                input.classList.add('bg-white'); // Make background white
            });

            document.getElementById('editBtn').classList.add('hidden');
            document.getElementById('actionButtons').classList.remove('hidden');
        }

        function cancelEdit() {
            if (confirm('Discard unsaved changes?')) {
                location.reload();
            }
        }
    </script>
</body>

</html>