@extends('admin.layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xl text-black/60">Edit Product</p>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back</a>
    </div>

    <div class="grid grid-cols-[1.1fr_1.1fr_1fr] gap-12">
        <div>
            <form action="{{ route('admin.products.update', $product) }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Title" value="{{ old('Title', $product->Title) }}" placeholder="Product Title" required />
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Author" value="{{ old('Author', $product->Author) }}" placeholder="Author" required />
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Price" step="0.01" min="0" value="{{ old('Price', $product->Price) }}" placeholder="Price" required />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Stock" min="0" value="{{ old('Stock', $product->Stock) }}" placeholder="Stock" required />
                </div>
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="ISBN" value="{{ old('ISBN', $product->ISBN) }}" placeholder="ISBN" required />
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Publisher" value="{{ old('Publisher', $product->Publisher) }}" placeholder="Publisher" required />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Genre" value="{{ old('Genre', $product->Genre) }}" placeholder="Genre" required />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Subject" value="{{ old('Subject', $product->Subject) }}" placeholder="Subject" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Branch" value="{{ old('Branch', $product->Branch) }}" placeholder="Branch" />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Format" value="{{ old('Format', $product->Format) }}" placeholder="Format (Paperback, Hardcover, eBook)" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Language" value="{{ old('Language', $product->Language) }}" placeholder="Language" />
                </div>
                <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="date" name="Publication_Date" value="{{ old('Publication_Date', optional($product->Publication_Date)->toDateString()) }}" placeholder="Publication Date" />
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Rating" step="0.01" min="0" max="5" value="{{ old('Rating', $product->Rating) }}" placeholder="Rating" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="text" name="Age_Group" value="{{ old('Age_Group', $product->Age_Group) }}" placeholder="Age Group" />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Length" min="0" value="{{ old('Length', $product->Length) }}" placeholder="Length" />
                    <input class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" type="number" name="Width" min="0" value="{{ old('Width', $product->Width) }}" placeholder="Width" />
                </div>
                <textarea class="min-h-[120px] rounded-xl border border-black/50 bg-white px-[16px] py-[12px]" name="Description" rows="4" placeholder="Book Description">{{ old('Description', $product->Description) }}</textarea>
                <textarea class="min-h-[100px] rounded-xl border border-black/50 bg-white px-[16px] py-[12px]" name="Review" rows="3" placeholder="Book Reviews or Notes">{{ old('Review', $product->Review) }}</textarea>
                <button type="submit" class="mt-4 w-[161px] h-[36px] rounded-xl bg-[#F54E4E] text-white">Save &amp; Publish</button>
            </form>
        </div>
        <div>
            <p class="text-sm text-gray-600">Tip: keep metadata up-to-date to improve catalog relevance and reporting quality.</p>
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