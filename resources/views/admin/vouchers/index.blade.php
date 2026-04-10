@extends('admin.layouts.app')
@section('title', 'Vouchers')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-3.5">
        <p class="text-xl text-black/60">Voucher Summary</p>
        <a href="{{ route('admin.vouchers.create') }}" class="flex items-center justify-center gap-3.5 rounded-md bg-[#FCAE42] px-[22px] py-[6px] text-white">
            <img src="/images/Admin-img/plus.png" alt="" width="18" height="18" />
            Add a New Voucher
        </a>
    </div>

    <div class="rounded-2xl bg-[#F54E4E] px-4 py-3 h-[145px]">
        <img class="mb-[32px]" src="/images/Admin-img/productBanner.png" alt="" width="36" height="36" />
        <div class="grid grid-cols-2">
            <div>
                <p class="text-white">All Vouchers</p>
                <p class="text-[20px] font-medium text-white">{{ $totalVouchers }}</p>
            </div>
            <div>
                <p class="text-white">Active Vouchers</p>
                <div class="flex items-center gap-2.5">
                    <p class="text-[20px] font-medium text-white">{{ $activeVouchers }}</p>
                    <p class="text-[12px] text-white">{{ $totalVouchers > 0 ? number_format(($activeVouchers / max($totalVouchers, 1)) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-center text-black/60">Voucher Items</h1>
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="flex items-center gap-3">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search voucher name" class="h-[42px] w-[320px] rounded-md border border-gray-300 px-4 text-sm outline-none" />
            <button type="submit" class="h-[42px] rounded-md bg-[#FCAE42] px-5 text-sm text-white">Search</button>
            @if($search !== '')
                <a href="{{ route('admin.vouchers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto w-full rounded-xl bg-white shadow-sm border border-gray-100">
        <table class="w-full whitespace-nowrap border-collapse text-left">
            <thead>
                <tr class="border-b border-gray-200 text-sm text-black">
                    <th class="py-4 pl-6 pr-4 font-normal">#</th>
                    <th class="py-4 px-4 font-medium">Voucher Name</th>
                    <th class="py-4 px-4 font-medium">Type</th>
                    <th class="py-4 px-4 font-medium">Discount</th>
                    <th class="py-4 px-4 font-medium">Uses</th>
                    <th class="py-4 px-4 font-medium">Edit</th>
                    <th class="py-4 px-4 font-medium">Delete</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-500">
                @forelse($vouchers as $voucher)
                    <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                        <td class="py-4 pl-6 pr-4">{{ $voucher->voucher_id }}</td>
                        <td class="py-4 px-4 font-medium text-gray-900">{{ $voucher->voucherName }}</td>
                        <td class="py-4 px-4">{{ ucfirst($voucher->voucherType) }}</td>
                        <td class="py-4 px-4">{{ $voucher->voucherType === 'percentage' ? $voucher->voucherAmount . '%' : '₱' . number_format($voucher->voucherAmount, 2) }}</td>
                        <td class="py-4 px-4">{{ $voucher->voucherUsed }}</td>
                        <td class="py-4 px-4">
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="text-gray-400 transition-colors hover:text-blue-600">Edit</a>
                        </td>
                        <td class="py-4 px-4">
                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Delete this voucher?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 transition-colors hover:text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-500">No vouchers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm text-gray-500">
        <span>{{ $vouchers->total() }} items</span>
        <div>{{ $vouchers->links() }}</div>
    </div>
</div>
@endsection