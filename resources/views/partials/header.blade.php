{{-- Shared customer-facing header --}}
<div class="flex items-center justify-between px-[261px]">
    <a href="{{ route('catalog.index') }}">
        <img src="/images/Logo.png" alt="NCB Logo" />
    </a>
    <form action="{{ route('catalog.index') }}" method="GET" class="relative">
        @if(request('genre'))
            <input type="hidden" name="genre" value="{{ request('genre') }}">
        @endif
        <input
            type="text"
            name="search"
            placeholder="Search"
            value="{{ request('search') }}"
            class="w-[908px] h-[36.74px] px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
        />
        <button class="absolute right-0 top-0 h-full px-4 text-gray-500 hover:text-gray-700 focus:outline-none" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.352 4.35a1 1 0 01-1.414 1.414l-4.352-4.35A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>

    <div class="flex items-center gap-4">
        @auth('customer')
            <p class="text-gray-700 font-bold">{{ Auth::guard('customer')->user()->first_name }}</p>
            <a href="{{ route('account.orders') }}">
                <img src="/images/User.png" alt="Account" />
            </a>
        @else
            <a href="{{ route('login') }}" class="text-gray-700 font-bold">Login</a>
        @endauth
        <a href="{{ route('cart.index') }}">
            <img src="/images/cart.png" alt="Cart" />
        </a>
    </div>
</div>

{{-- Nav bar --}}
<div class="h-12 w-screen bg-[#FCAE42] px-[261px] flex items-center justify-between">
    <a href="{{ route('catalog.index') }}" class="ml-7 text-black text-[20px] font-bold">Books</a>
    <a href="#" class="text-black text-[20px] font-bold">E-Books</a>
    <a href="#" class="text-black text-[20px] font-bold">Best Sellers</a>
    <a href="#" class="text-black text-[20px] font-bold">New</a>
    <a href="#" class="text-black text-[20px] font-bold">Collections</a>
    <a href="#" class="text-black text-[20px] font-bold">Sale</a>
</div>