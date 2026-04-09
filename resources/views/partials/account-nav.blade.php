<div class="w-[342px] mr-8">
    <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ $active === 'orders' ? 'bg-[#ED1B24]/20' : '' }}"
       href="{{ route('account.orders') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/delivery.png" alt="" />
        </div>
        <p class="font-bold text-[20px] ml-3.5">My Orders</p>
    </a>

    <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ $active === 'addresses' ? 'bg-[#ED1B24]/20' : '' }}"
       href="{{ route('account.addresses') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/adress.png" alt="" />
        </div>
        <p class="font-bold text-[20px] ml-3.5">Addresses</p>
    </a>

    <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ $active === 'security' ? 'bg-[#ED1B24]/20' : '' }}"
       href="{{ route('account.security') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/login.png" alt="" />
        </div>
        <p class="font-bold text-[20px] ml-3.5">Login & Security</p>
    </a>

    <a class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md mb-[17px] {{ $active === 'archived' ? 'bg-[#ED1B24]/20' : '' }}"
       href="{{ route('account.archived') }}">
        <div class="w-[70px] bg-white rounded-sm border border-black/30">
            <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/archive.png" alt="" />
        </div>
        <p class="font-bold text-[20px] ml-3.5">Archived Orders</p>
    </a>

    <hr class="my-4 border-gray-300" />

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="flex items-center hover:bg-[#ED1B24]/30 p-1.5 rounded-md w-full text-left">
            <div class="w-[70px] bg-white rounded-sm border border-black/30">
                <img class="w-[50px] mx-2 my-2" src="/images/userAcc-img/logout.png" alt="" />
            </div>
            <p class="font-bold text-[20px] ml-3.5">Log Out</p>
        </button>
    </form>
</div>