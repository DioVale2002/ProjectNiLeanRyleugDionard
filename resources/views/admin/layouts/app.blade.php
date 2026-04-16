<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>NCB Admin — @yield('title')</title>
</head>
<body class="min-h-screen bg-[#f8fafc] flex flex-col">

    {{-- Admin topbar --}}
    <div class="flex gap-5 justify-between items-center mx-[70px] py-2">
        <img src="/images/Logo.png" alt="NCB Logo" class="h-12" />
        <h1 class="text-black font-bold text-[18px]">Admin</h1>
    </div>
    <hr class="bg-[#FCAE42] h-1 border-0" />
    <hr class="bg-[#F54E4E] h-1 border-0 mb-9" />

    <div class="flex gap-[42px] flex-1">

        {{-- Sidebar --}}
        <div class="w-[343px] pt-10 ml-[70px]">
            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->is('admin/products*') ? 'bg-[#ED1B24]/20' : '' }}"
               href="{{ route('admin.products.index') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/Admin-img/inventory.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Inventory</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->is('admin/stock*') ? 'bg-[#ED1B24]/20' : '' }}"
               href="{{ route('admin.stock.index') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/Admin-img/package.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Stock</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->is('admin/vouchers*') ? 'bg-[#ED1B24]/20' : '' }}"
               href="{{ route('admin.vouchers.index') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/Admin-img/coupon.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Vouchers</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->is('admin/orders*') ? 'bg-[#ED1B24]/20' : '' }}"
               href="{{ route('admin.orders.index') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/Admin-img/delivery-man.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Orders</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->is('admin/analytics*') ? 'bg-[#ED1B24]/20' : '' }}"
               href="{{ route('admin.analytics.index') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/Admin-img/pie-graph.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Analytics</p>
            </a>

            <hr class="my-4 border-gray-300" />

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md" href="{{ route('login') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/logout.png" alt="" />
                </div>
                <p class="font-bold text-[25px] ml-3.5">Log Out</p>
            </a>
        </div>

        {{-- Main content --}}
        <div class="flex-1 mr-[70px] mt-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>