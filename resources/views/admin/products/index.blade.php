@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
@php
    $productCollection = $products->getCollection();
    $totalProducts = $products->total();
    $activeProducts = $productCollection->where('Stock', '>', 0)->count();
    $lowStockProducts = $productCollection->where('Stock', '<=', 5)->count();
@endphp

<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-3.5">
        <p class="text-xl text-black/60">Inventory Summary</p>
        <a href="{{ route('admin.products.create') }}" class="flex items-center justify-center gap-3.5 rounded-md bg-[#FCAE42] px-[22px] py-[6px] text-white">
            <img src="/images/Admin-img/plus.png" alt="" width="18" height="18" />
            Add a New Product
        </a>
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div class="rounded-2xl bg-[#F54E4E] px-4 py-3 h-[145px]">
            <img class="mb-[32px]" src="/images/Admin-img/productBanner.png" alt="" width="36" height="36" />
            <div class="grid grid-cols-2">
                <div>
                    <p class="text-white">All Products</p>
                    <p class="text-[20px] font-medium text-white">{{ $totalProducts }}</p>
                </div>
                <div>
                    <p class="text-white">Active</p>
                    <div class="flex items-center gap-2.5">
                        <p class="text-[20px] font-medium text-white">{{ $activeProducts }}</p>
                        <p class="text-[12px] text-white">{{ $totalProducts > 0 ? round(($activeProducts / max($totalProducts, 1)) * 100) : 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-2xl px-4 py-3 h-[145px] bg-white">
            <img class="mb-[32px]" src="/images/Admin-img/stockBanner.png" alt="" width="36" height="36" />
            <div class="grid grid-cols-2">
                <div>
                    <p class="text-red-500">Low Stock Alert</p>
                    <p class="text-[20px] font-medium text-black">{{ $lowStockProducts }}</p>
                </div>
                <div>
                    <p class="text-black">1 Star Rating</p>
                    <p class="text-[20px] font-medium text-black">{{ $productCollection->where('Rating', '<=', 1)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <h1 class="my-6 text-center text-black/60">Inventory Items</h1>

    <div class="overflow-x-auto w-full rounded-xl bg-white shadow-sm border border-gray-100">
        <table class="w-full whitespace-nowrap border-collapse text-left">
            <thead>
                <tr class="border-b border-gray-200 text-sm text-black">
                    <th class="py-4 pl-6 pr-4 font-normal">#</th>
                    <th class="py-4 px-4 font-medium">Product Name</th>
                    <th class="py-4 px-4 font-medium">Genre</th>
                    <th class="py-4 px-4 font-medium">Price</th>
                    <th class="py-4 px-4 font-medium">In-Stock</th>
                    <th class="py-4 px-4 font-medium">Age Group</th>
                    <th class="py-4 px-4 font-medium">Rating</th>
                    <th class="py-4 px-4 font-medium">Edit</th>
                    <th class="py-4 px-4 font-medium">Archive</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-500">
                @forelse($products as $product)
                    <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                        <td class="py-4 pl-6 pr-4">{{ $product->product_ID }}</td>
                        <td class="py-4 px-4 font-medium text-gray-900">{{ $product->Title }}</td>
                        <td class="py-4 px-4">{{ $product->Genre }}</td>
                        <td class="py-4 px-4">₱{{ number_format($product->Price, 2) }}</td>
                        <td class="py-4 px-4">{{ $product->Stock }}</td>
                        <td class="py-4 px-4">{{ $product->Age_Group ?? '—' }}</td>
                        <td class="py-4 px-4">{{ $product->Rating ?? '—' }}</td>
                        <td class="py-4 px-4">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-gray-400 transition-colors hover:text-blue-600">Edit</a>
                        </td>
                        <td class="py-4 px-4">
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Archive this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 transition-colors hover:text-red-600">Archive</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm text-gray-500">
        <span>{{ $products->total() }} items</span>
        <div>{{ $products->links() }}</div>
    </div>
</div>
@endsection