@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xl text-black/60">Add New Product</p>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="grid grid-cols-[1.1fr_1.1fr_1fr] gap-12">
        <div>
            <form action="{{ route('admin.products.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Title" value="{{ old('Title') }}" placeholder="Product Title" required />
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Author" value="{{ old('Author') }}" placeholder="Author" required />
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Price" step="0.01" min="0" value="{{ old('Price') }}" placeholder="Price" required />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Stock" min="0" value="{{ old('Stock') }}" placeholder="Stock" required />
                </div>
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="ISBN" value="{{ old('ISBN') }}" placeholder="ISBN" required />
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Publisher" value="{{ old('Publisher') }}" placeholder="Publisher" required />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Genre" value="{{ old('Genre') }}" placeholder="Genre" required />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Rating" step="0.01" min="0" max="5" value="{{ old('Rating') }}" placeholder="Rating" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Age_Group" value="{{ old('Age_Group') }}" placeholder="Age Group" />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Length" min="0" value="{{ old('Length') }}" placeholder="Length" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Width" min="0" value="{{ old('Width') }}" placeholder="Width" />
                </div>
                <button type="submit" class="mt-4 w-[161px] h-[36px] rounded-xl bg-[#F54E4E] text-white">Save &amp; Publish</button>
            </form>
        </div>
        <div>
            <p class="mb-2 text-[12px]">Book Short Description</p>
            <textarea class="h-[163px] w-[375px] rounded-md border border-black/60 p-2.5" placeholder="Your text goes here" name="Review" rows="3">{{ old('Review') }}</textarea>
            <p class="my-2 text-[12px]">Book Long Description</p>
            <textarea class="h-[163px] w-[375px] rounded-md border border-black/60 p-2.5" placeholder="Your text goes here" name="LongReview" rows="3"></textarea>
        </div>
        <div>
            <div class="max-w-lg rounded-2xl border border-gray-100 bg-[#F9FAFC] p-6 shadow-sm">
                <label for="file-upload" class="relative flex h-80 w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 p-8 transition-all hover:border-red-300 hover:bg-gray-100/50">
                    <div class="mb-6 rounded-2xl bg-red-100/60 p-4">
                        <svg class="h-14 w-14 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div class="mb-5 flex items-center gap-2.5">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="text-2xl font-semibold text-red-500">Upload Image</span>
                    </div>
                    <p class="mb-1 text-center text-lg text-gray-600">Upload a cover image for your product.</p>
                    <p class="text-center text-sm text-gray-500">File Format <strong class="font-medium text-gray-700">jpeg, png</strong> Recommended Size <strong class="font-medium text-gray-700">600×600 (1:1)</strong></p>
                    <input id="file-upload" name="file-upload" type="file" accept="image/png, image/jpeg" class="sr-only" />
                </label>
            </div>
        </div>
    </div>
</div>
@endsection