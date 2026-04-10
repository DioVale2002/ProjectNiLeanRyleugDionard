<div class="w-full xl:w-[342px]">
    <a class="flex items-center p-1.5 rounded-md mb-[17px] transition {{ $active === 'orders' ? 'bg-[#ED1B24]/20' : 'hover:bg-[#ED1B24]/10' }}"
       href="{{ route('account.orders') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/delivery.png" alt="My Orders" />
        </div>
        <p class="text-black font-bold text-[25px] ml-3.5">My Orders</p>
    </a>

    <a class="flex items-center p-1.5 rounded-md mb-[17px] transition {{ $active === 'addresses' ? 'bg-[#ED1B24]/20' : 'hover:bg-[#ED1B24]/10' }}"
       href="{{ route('account.addresses') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/adress.png" alt="Your Addresses" />
        </div>
        <p class="text-black font-bold text-[25px] ml-3.5">Your Addresses</p>
    </a>

    <a class="flex items-center p-1.5 rounded-md mb-[17px] transition {{ $active === 'security' ? 'bg-[#ED1B24]/20' : 'hover:bg-[#ED1B24]/10' }}"
       href="{{ route('account.security') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/login.png" alt="Login and Security" />
        </div>
        <p class="text-black font-bold text-[25px] ml-3.5">Login &amp; Security</p>
    </a>

    <a class="flex items-center p-1.5 rounded-md mb-[17px] transition {{ $active === 'archived' ? 'bg-[#ED1B24]/20' : 'hover:bg-[#ED1B24]/10' }}"
       href="{{ route('account.archived') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/archive.png" alt="Archive Orders" />
        </div>
        <p class="text-black font-bold text-[25px] ml-3.5">Archive Orders</p>
    </a>

    <hr class="my-4 border-gray-300" />

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="flex items-center p-1.5 rounded-md transition w-full hover:bg-[#ED1B24]/10">
            <div class="w-[70px] bg-white rounded-sm border border-black/30">
                <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/logout.png" alt="Logout" />
            </div>
            <p class="text-black font-bold text-[25px] ml-3.5">Log Out</p>
        </button>
    </form>
</div>