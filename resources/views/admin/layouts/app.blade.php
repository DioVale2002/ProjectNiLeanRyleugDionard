<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>NCB Admin — @yield('title')</title>
</head>
<body class="min-h-screen bg-[#f8fafc] flex flex-col font-sans">

    {{-- Admin topbar --}}
    <div class="flex gap-5 justify-between items-center px-[70px] py-4 bg-white shadow-sm z-10">
        <div class="flex items-center gap-4">
            <img src="/images/Logo.png" alt="NCB Logo" class="h-10" />
        </div>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#FCAE42]/20 flex items-center justify-center">
                <span class="text-[#FCAE42] font-bold text-sm">A</span>
            </div>
            <h1 class="text-gray-800 font-bold text-[16px]">Admin Panel</h1>
        </div>
    </div>
    
    {{-- Brand Lines --}}
    <div class="w-full flex">
        <div class="bg-[#FCAE42] h-1 w-1/2"></div>
        <div class="bg-[#ED1B24] h-1 w-1/2"></div>
    </div>

    <div class="flex gap-[42px] flex-1 max-w-[1600px] mx-auto w-full">

        {{-- Sidebar --}}
        <div class="w-[280px] pt-8 pl-[70px] flex-shrink-0 flex flex-col sticky top-0 h-[calc(100vh-80px)] overflow-y-auto custom-scrollbar">
            
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 ml-3">Menu</p>
            <a href="{{ route('admin.products.index') }}"
               class="group flex items-center px-3 py-3 rounded-xl mb-2 transition-all duration-200 {{ request()->is('admin/products*') ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'hover:bg-gray-100/80' }}">
                <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg transition-colors {{ request()->is('admin/products*') ? 'bg-[#ED1B24]/10' : 'bg-gray-100 group-hover:bg-white' }}">
                    <img class="w-6 h-6 object-contain opacity-80" src="/images/Admin-img/inventory.png" alt="Inventory" />
                </div>
                <p class="font-semibold text-[16px] ml-4 transition-colors {{ request()->is('admin/products*') ? 'text-[#ED1B24]' : 'text-gray-600 group-hover:text-black' }}">Inventory</p>
            </a>

            <a href="{{ route('admin.stock.index') }}"
               class="group flex items-center px-3 py-3 rounded-xl mb-2 transition-all duration-200 {{ request()->is('admin/stock*') ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'hover:bg-gray-100/80' }}">
                <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg transition-colors {{ request()->is('admin/stock*') ? 'bg-[#ED1B24]/10' : 'bg-gray-100 group-hover:bg-white' }}">
                    <img class="w-6 h-6 object-contain opacity-80" src="/images/Admin-img/package.png" alt="Stock" />
                </div>
                <p class="font-semibold text-[16px] ml-4 transition-colors {{ request()->is('admin/stock*') ? 'text-[#ED1B24]' : 'text-gray-600 group-hover:text-black' }}">Stock</p>
            </a>

            <a href="{{ route('admin.vouchers.index') }}"
               class="group flex items-center px-3 py-3 rounded-xl mb-2 transition-all duration-200 {{ request()->is('admin/vouchers*') ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'hover:bg-gray-100/80' }}">
                <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg transition-colors {{ request()->is('admin/vouchers*') ? 'bg-[#ED1B24]/10' : 'bg-gray-100 group-hover:bg-white' }}">
                    <img class="w-6 h-6 object-contain opacity-80" src="/images/Admin-img/coupon.png" alt="Vouchers" />
                </div>
                <p class="font-semibold text-[16px] ml-4 transition-colors {{ request()->is('admin/vouchers*') ? 'text-[#ED1B24]' : 'text-gray-600 group-hover:text-black' }}">Vouchers</p>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="group flex items-center px-3 py-3 rounded-xl mb-2 transition-all duration-200 {{ request()->is('admin/orders*') ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'hover:bg-gray-100/80' }}">
                <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg transition-colors {{ request()->is('admin/orders*') ? 'bg-[#ED1B24]/10' : 'bg-gray-100 group-hover:bg-white' }}">
                    <img class="w-6 h-6 object-contain opacity-80" src="/images/Admin-img/delivery-man.png" alt="Orders" />
                </div>
                <p class="font-semibold text-[16px] ml-4 transition-colors {{ request()->is('admin/orders*') ? 'text-[#ED1B24]' : 'text-gray-600 group-hover:text-black' }}">Orders</p>
            </a>

            <a href="{{ route('admin.analytics.index') }}"
               class="group flex items-center px-3 py-3 rounded-xl mb-2 transition-all duration-200 {{ request()->is('admin/analytics*') ? 'bg-white shadow-sm ring-1 ring-gray-200' : 'hover:bg-gray-100/80' }}">
                <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg transition-colors {{ request()->is('admin/analytics*') ? 'bg-[#ED1B24]/10' : 'bg-gray-100 group-hover:bg-white' }}">
                    <img class="w-6 h-6 object-contain opacity-80" src="/images/Admin-img/pie-graph.png" alt="Analytics" />
                </div>
                <p class="font-semibold text-[16px] ml-4 transition-colors {{ request()->is('admin/analytics*') ? 'text-[#ED1B24]' : 'text-gray-600 group-hover:text-black' }}">Analytics</p>
            </a>

            <div class="mt-auto mb-8">
                <hr class="my-4 border-gray-200" />
                <a href="{{ route('login') }}"
                   class="group flex items-center px-3 py-3 rounded-xl transition-all duration-200 hover:bg-red-50">
                    <div class="flex items-center justify-center w-[42px] h-[42px] rounded-lg bg-gray-100 group-hover:bg-white transition-colors">
                        <img class="w-5 h-5 object-contain opacity-70 group-hover:opacity-100" src="/images/userAcc-img/logout.png" alt="Logout" />
                    </div>
                    <p class="font-semibold text-[16px] ml-4 text-gray-500 group-hover:text-red-600 transition-colors">Log Out</p>
                </a>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex-1 mr-[70px] mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8 min-h-[80vh]">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r-md mb-6 shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r-md mb-6 shadow-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r-md mb-6 shadow-sm">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</body>
</html>