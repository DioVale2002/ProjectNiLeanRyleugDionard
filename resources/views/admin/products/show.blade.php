@extends('admin.layouts.app')
@section('title', 'Product Details')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xl text-black/60">Product Details</p>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="rounded-md bg-[#FCAE42] px-[22px] py-[6px] text-white">Edit</a>
            <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">← Back</a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm border border-gray-100">
        <table class="w-full border-collapse text-left">
            <tbody class="text-sm text-gray-600">
                <tr class="border-b border-gray-100"><th class="w-[220px] px-6 py-4 font-semibold text-gray-700">ID</th><td class="px-6 py-4">{{ $product->product_ID }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Title</th><td class="px-6 py-4">{{ $product->Title }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Author</th><td class="px-6 py-4">{{ $product->Author }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">ISBN</th><td class="px-6 py-4">{{ $product->ISBN }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Genre</th><td class="px-6 py-4">{{ $product->Genre }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Publisher</th><td class="px-6 py-4">{{ $product->Publisher }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Price</th><td class="px-6 py-4">₱{{ number_format($product->Price, 2) }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Stock</th><td class="px-6 py-4">{{ $product->Stock }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Rating</th><td class="px-6 py-4">{{ $product->Rating ?? '—' }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Age Group</th><td class="px-6 py-4">{{ $product->Age_Group ?? '—' }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Length</th><td class="px-6 py-4">{{ $product->Length ?? '—' }}</td></tr>
                <tr class="border-b border-gray-100"><th class="px-6 py-4 font-semibold text-gray-700">Width</th><td class="px-6 py-4">{{ $product->Width ?? '—' }}</td></tr>
                <tr><th class="px-6 py-4 font-semibold text-gray-700">Review</th><td class="px-6 py-4">{{ $product->Review ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection