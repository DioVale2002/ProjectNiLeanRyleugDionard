@extends('admin.layouts.app')
@section('title', 'Edit Voucher')

@section('content')
<div class="font-sans">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Voucher</h2>
            <p class="text-sm text-gray-500 mt-1">Update details for Voucher #{{ $voucher->voucher_id }}</p>
        </div>
        <a href="{{ route('admin.vouchers.index') }}" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-sm">
            Back to Vouchers
        </a>
    </div>

    
    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl">
            
            {{-- Left Side: Voucher Configuration --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-2">Voucher Settings</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Code / Name</label>
                    <input type="text" name="voucherName" value="{{ old('voucherName', $voucher->voucherName) }}" placeholder="e.g. SUMMER2024" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" required />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type</label>
                        <div class="relative">
                            <select name="voucherType" class="w-full h-11 appearance-none rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors bg-white" required>
                                <option value="">-- Select Type --</option>
                                <option value="percentage" {{ old('voucherType', $voucher->voucherType) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="flat" {{ old('voucherType', $voucher->voucherType) === 'flat' ? 'selected' : '' }}>Flat Amount (₱)</option>
                            </select>
                            <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount / Value</label>
                        <input type="number" name="voucherAmount" step="0.01" min="0" value="{{ old('voucherAmount', $voucher->voucherAmount) }}" placeholder="0.00" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" required />
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-[#FCAE42] hover:bg-yellow-500 transition-colors text-black font-bold text-lg shadow-sm flex justify-center items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    Update Voucher
                </button>
            </div>

            {{-- Right Side: Details / Notes --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-100 pb-2 mb-6">Internal Notes</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Description</label>
                    <textarea name="voucherDetails" class="w-full h-[200px] rounded-lg border border-gray-300 p-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors resize-none" placeholder="Add any internal notes, terms, or conditions for this voucher here.">{{ old('voucherDetails', $voucher->voucherDetails ?? '') }}</textarea>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection