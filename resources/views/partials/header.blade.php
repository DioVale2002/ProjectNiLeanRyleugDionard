<header>
    <div class="flex items-center justify-between px-[261px]">
        <a href="/">
            <img src="{{ asset('images/Logo.png') }}" alt="Character portrait" />
        </a>

        <div class="relative">
            <form action="/catalog" method="GET" class="m-0">
                <input
                    type="text"
                    name="search"
                    placeholder="Search"
                    class="w-[908px] h-[36.74px] px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" />

                <button
                    type="submit"
                    class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.352 4.35a1 1 0 01-1.414 1.414l-4.352-4.35A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="ml-4 text-gray-700 font-bold">
            {{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->first_name : 'Guest' }}
        </p>

        <a href="{{ route('account.security') }}" class="ml-4">
            <img src="{{ asset('images/User.png') }}" alt="User profile" />
        </a>

        <a href="{{ url('/cart') }}" class="ml-4">
            <img src="{{ asset('images/cart.png') }}" alt="Shopping cart" />
        </a>
    </div>

    <div class="h-12 w-screen bg-[#FCAE42] px-[261px] flex items-center justify-between">
        <a href="/catalog" class="ml-7 text-black text-[20px] font-bold hover:opacity-80 transition">Books</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">E-Books</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Best Sellers</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">New</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Collections</a>
        <a href="#" class="text-black text-[20px] font-bold hover:opacity-80 transition">Sale</a>
    </div>
</header>