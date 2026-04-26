@extends('admin.layouts.app')
@section('title', 'Vouchers')

@section('content')
<div class="font-sans">
    
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Voucher Management</h2>
            <p class="text-sm text-gray-500 mt-1">Create and manage discount codes for your customers.</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="flex items-center gap-2 rounded-xl bg-[#FCAE42] hover:bg-yellow-500 transition-colors px-5 py-2.5 text-white font-semibold shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add New Voucher
        </a>
    </div>

    {{-- Metrics Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Primary Metric Card (Red Theme) --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#F54E4E] to-[#d63a3a] p-6 shadow-md flex flex-col h-[150px] relative overflow-hidden">
            <div class="bg-white/20 w-max p-2 rounded-xl backdrop-blur-sm shadow-sm mb-auto z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
            </div>
            
            <div class="flex justify-between w-full mt-2 z-10">
                <div>
                    <p class="text-white/80 font-medium text-sm uppercase tracking-wide">All Vouchers</p>
                    <p class="text-4xl font-bold text-white mt-1">{{ $totalVouchers }}</p>
                </div>
            </div>
        </div>

        {{-- Secondary Metric Card (White Theme) --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col h-[150px]">
            <div class="bg-green-50 w-max p-2 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="flex justify-between w-full mt-2">
                <div>
                    <p class="text-gray-500 font-bold text-sm uppercase tracking-wide">Active Vouchers</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-4xl font-bold text-gray-800">{{ $activeVouchers }}</p>
                        <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $totalVouchers > 0 ? number_format(($activeVouchers / max($totalVouchers, 1)) * 100, 1) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Table Container --}}
    <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-5 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700">Voucher Items</h3>
            <form method="GET" action="{{ route('admin.vouchers.index') }}" class="flex items-center gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search voucher name" class="h-[40px] pl-9 pr-4 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-all w-[300px]" />
                </div>
                <button type="submit" class="h-[40px] rounded-lg bg-gray-800 hover:bg-black transition-colors px-5 text-sm text-white font-medium">Filter</button>
                @if($search !== '')
                    <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-medium text-red-500 hover:text-red-700 ml-2">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full whitespace-nowrap border-collapse text-left">
                <thead class="bg-gray-50/50">
                    <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 pl-6 pr-4">#</th>
                        <th class="py-4 px-4">Voucher Name</th>
                        <th class="py-4 px-4">Type</th>
                        <th class="py-4 px-4">Discount</th>
                        <th class="py-4 px-4">Uses</th>
                        <th class="py-4 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                    @forelse($vouchers as $voucher)
                        <tr class="transition-colors hover:bg-gray-50 group">
                            <td class="py-4 pl-6 pr-4 font-medium text-gray-500">{{ $voucher->voucher_id }}</td>
                            <td class="py-4 px-4 font-semibold text-gray-900">{{ $voucher->voucherName }}</td>
                            <td class="py-4 px-4">
                                @if($voucher->voucherType === 'percentage')
                                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wide border border-blue-100">Percentage</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wide border border-emerald-100">Flat Amount</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-800">{{ $voucher->voucherType === 'percentage' ? $voucher->voucherAmount . '%' : '₱' . number_format($voucher->voucherAmount, 2) }}</td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{ $voucher->voucherUsed }}
                                </div>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Delete this voucher?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <p class="text-gray-500 font-medium">No vouchers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500 bg-gray-50/50">
            <span class="font-medium">Showing {{ $vouchers->count() }} of {{ $vouchers->total() }} items</span>
            <div>{{ $vouchers->links() }}</div>
        </div>
    </div>
</div>
@endsection