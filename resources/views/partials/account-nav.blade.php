<div class="w-full xl:w-[280px] flex flex-col gap-2">
    <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'orders' ? 'bg-[#ED1B24] text-white' : 'text-gray-700 hover:bg-gray-50' }}"
       href="{{ route('account.orders') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M3 8a1 1 0 018 0v6a1 1 0 01-8 0V8zm5 9a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
        <span class="font-semibold">My Orders</span>
    </a>

    <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'addresses' ? 'bg-[#ED1B24] text-white' : 'text-gray-700 hover:bg-gray-50' }}"
       href="{{ route('account.addresses') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L8.59 16.59A7 7 0 015.05 4.05zM8 9a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
        <span class="font-semibold">Addresses</span>
    </a>

    <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'security' ? 'bg-[#ED1B24] text-white' : 'text-gray-700 hover:bg-gray-50' }}"
       href="{{ route('account.security') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
        <span class="font-semibold">Security</span>
    </a>

    <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ $active === 'archived' ? 'bg-[#ED1B24] text-white' : 'text-gray-700 hover:bg-gray-50' }}"
       href="{{ route('account.archived') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1H6a1 1 0 110-2h1v-1a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        <span class="font-semibold">Archived</span>
    </a>

    <hr class="my-2 border-gray-200" />

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition w-full text-gray-700 hover:bg-red-50 hover:text-[#ED1B24]">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.293 4.293a1 1 0 01-1.414-1.414L12.586 7H9a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V7.414z" clip-rule="evenodd"></path></svg>
            <span class="font-semibold">Log Out</span>
        </button>
    </form>
</div>