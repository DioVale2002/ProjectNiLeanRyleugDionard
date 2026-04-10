@extends('admin.layouts.app')
@section('title', 'Add Voucher')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xl text-black/60">Add New Voucher</p>
        <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="grid grid-cols-[1fr_1fr] gap-12">
        <div>
            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="voucherName" value="{{ old('voucherName') }}" placeholder="Voucher Name" required />
                <div class="relative w-[375px]">
                    <select class="h-[52px] w-full appearance-none rounded-xl border border-black/50 bg-white px-[16px] py-[8px] pr-[40px] outline-none" name="voucherType" required>
                        <option value="">-- Select Type --</option>
                        <option value="percentage" {{ old('voucherType') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="flat" {{ old('voucherType') === 'flat' ? 'selected' : '' }}>Flat (₱)</option>
                    </select>
                    <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="voucherAmount" step="0.01" min="0" value="{{ old('voucherAmount') }}" placeholder="Amount" required />
                <button type="submit" class="mt-5 w-[161px] h-[36px] rounded-xl bg-[#F54E4E] text-white">Save &amp; Publish</button>
            </form>
        </div>
        <div>
            <p class="mb-2 text-[12px]">Voucher Details</p>
            <textarea class="h-[163px] w-[375px] rounded-md border border-black/60 p-2.5" placeholder="Your text goes here"></textarea>
        </div>
    </div>
</div>
@endsection