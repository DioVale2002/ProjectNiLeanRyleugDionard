<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>My Orders</title>
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
            <img src="{{ asset('assets/User.png') }}" alt="User profile" />
        </a>
        <a href="{{ route('cart.index') }}" class="ml-4">
            <img src="{{ asset('assets/cart.png') }}" alt="Shopping cart" />
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

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.orders') ? 'bg-[#ED1B24]/30' : '' }}" href="{{ route('account.orders') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="{{ asset('assets/userAcc-img/delivery.png') }}" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">My Orders</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.addresses') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.addresses') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="{{ asset('assets/userAcc-img/adress.png') }}" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Your Addresses</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.security') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.security') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="{{ asset('assets/userAcc-img/login.png') }}" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Login & Security</p>
            </a>

            <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ request()->routeIs('account.archived') ? 'bg-[#ED1B24]/20' : '' }}" href="{{ route('account.archived') }}">
                <div class="w-[70px] bg-white rounded-sm border border-black/30">
                    <img class="w-[50px] mx-2 my-2" src="{{ asset('assets/userAcc-img/archive.png') }}" alt="" />
                </div>
                <p class="text-black font-bold text-[25px] ml-3.5">Archive Orders</p>
            </a>

            <hr class="my-4 border-gray-300" />

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md cursor-pointer">
                    <div class="w-[70px] bg-white rounded-sm border border-black/30">
                        <img class="w-[50px] mx-2 my-2" src="{{ asset('assets/userAcc-img/logout.png') }}" alt="" />
                    </div>
                    <p class="text-black font-bold text-[25px] ml-3.5">Log Out</p>
                </button>
            </form>
        </div>

        <div class="border border-black/50 rounded-lg ml-[63px] w-[1000px] h-full">
            <div class="mx-7 mt-7 pb-7">
                <h1 class="font-bold text-[32px] mb-4">Your Orders</h1>
                <hr class="my-4 border-gray-300" />

                @if($orders->isEmpty())
                <div class="text-center py-10">
                    <h3 class="text-red-500 font-bold text-2xl">No Active Orders.</h3>
                </div>
                @else
                @foreach($orders as $order)
                <div class="mb-12 border-b border-gray-300 pb-8 last:border-b-0">
                    <p class="font-bold text-[20px]">Order #{{ $order->order_id }}</p>
                    <div class="flex gap-2 items-center">
                        <p class="font-[15px]">{{ $order->cart->items->count() }} Products |</p>
                        <p class="font-[15px]">{{ Auth::guard('customer')->user()->first_name }} {{ Auth::guard('customer')->user()->last_name }} |</p>
                        <p class="font-[15px]">{{ $order->order_date->format('H:i') }} |</p>
                        <p class="font-[15px]">{{ $order->order_date->format('M d Y') }}</p>
                    </div>
                    <hr class="my-4 border-gray-300" />
                    <div class="flex items-center">
                        <div class="mr-6 space-y-1">
                            <p class="text-gray-600">Status:</p>
                            <p class="text-gray-600">Date of Order:</p>
                            <p class="text-gray-600">Payment Method:</p>
                            <p class="font-bold mt-2">Total:</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-bold {{ $order->order_status == 'Pending' ? 'text-[#EA8C51]' : 'text-blue-500' }}">
                                {{ $order->order_status }}
                            </p>
                            <p>{{ $order->order_date->format('M d Y') }}</p>
                            <p>{{ $order->paymentMethod->methodName ?? 'N/A' }}</p>
                            <p class="font-bold mt-2">₱{{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-12 grid grid-cols-2 gap-6">
                        @foreach($order->cart->items as $item)
                        <div class="flex items-start">
                            <div class="bg-[#E1F0F0] w-[103px] flex items-center justify-center rounded-sm p-3 shrink-0">
                                <img class="w-[80px] h-[100px] object-cover" src="{{ asset('assets/Example.png') }}" alt="{{ $item->product->Title }}" />
                            </div>
                            <div class="ml-4 flex flex-col justify-between h-full">
                                <p class="font-semibold text-sm line-clamp-2 uppercase">{{ $item->product->Title }}</p>
                                <p class="text-sm text-gray-500 mt-1">by {{ $item->product->Author }}</p>
                                <div class="mt-auto pt-2">
                                    <p class="text-sm text-gray-700">Quantity: {{ $item->quantity }}</p>
                                    <p class="font-bold text-sm">₱{{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @endif

            </div>
        </div>
    </div>
</body>

</html>