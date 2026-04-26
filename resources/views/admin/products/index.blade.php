@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
<div class="font-sans">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Inventory Summary</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your catalog, stock, and pricing.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 rounded-xl bg-[#FCAE42] hover:bg-yellow-500 transition-colors px-5 py-2.5 text-white font-semibold shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add New Product
        </a>
    </div>

    {{-- Metrics Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Primary Metric Card (Red Theme) --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#F54E4E] to-[#d63a3a] p-6 shadow-md flex flex-col h-[150px] relative overflow-hidden">
            {{-- Refined Inventory Icon --}}
            <div class="bg-white/20 w-max p-2.5 rounded-xl backdrop-blur-sm shadow-sm mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            
            <div class="flex justify-between w-full mt-2">
                <div>
                    <p class="text-white/80 font-medium text-sm uppercase tracking-wide">All Products</p>
                    <p class="text-4xl font-bold text-white mt-1">{{ $totalProducts }}</p>
                </div>
                <div>
                    <p class="text-white/80 font-medium text-sm uppercase tracking-wide">Active</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <p class="text-4xl font-bold text-white">{{ $activeProducts }}</p>
                        <span class="text-sm font-medium text-white/90 bg-white/20 px-2 py-0.5 rounded-full">{{ $totalProducts > 0 ? number_format(($activeProducts / max($totalProducts, 1)) * 100, 1) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Warning Metric Card (White Theme) --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col h-[150px]">
            {{-- Refined Alert Icon --}}
            <div class="bg-red-50 w-max p-2.5 rounded-xl mb-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <div class="flex justify-between w-full mt-2">
                <div>
                    <p class="text-red-500 font-bold text-sm uppercase tracking-wide">Low Stock Alert</p>
                    <p class="text-4xl font-bold text-gray-800 mt-1">{{ $lowStockProducts }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-medium text-sm uppercase tracking-wide">1 Star Ratings</p>
                    <p class="text-4xl font-bold text-gray-800 mt-1">{{ $ratingOneProducts }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
        <h3 class="font-semibold text-gray-700">Inventory Items</h3>
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="search" name="search" value="{{ $search }}" placeholder="Search catalog..." class="h-[40px] pl-9 pr-4 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-all w-[240px]" />
            </div>
            <input type="number" name="stock" value="{{ $stock }}" placeholder="Exact stock" min="0" class="h-[40px] w-[120px] rounded-lg border border-gray-200 px-4 text-sm focus:outline-none focus:border-[#FCAE42]" />
            <select name="status" class="h-[40px] rounded-lg border border-gray-200 px-4 text-sm focus:outline-none focus:border-[#FCAE42] bg-white">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All stock</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active only</option>
                <option value="low" {{ $status === 'low' ? 'selected' : '' }}>Low stock</option>
                <option value="out" {{ $status === 'out' ? 'selected' : '' }}>Out of stock</option>
            </select>
            <button type="submit" class="h-[40px] rounded-lg bg-gray-800 hover:bg-black transition-colors px-5 text-sm text-white font-medium">Filter</button>
            @if($search !== '' || $stock !== '' || $status !== 'all')
                <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-red-500 hover:text-red-700 ml-2">Clear</a>
            @endif
        </form>
    </div>

    {{-- Data Table --}}
    <div class="overflow-x-auto w-full rounded-xl bg-white shadow-sm border border-gray-200">
        <table class="w-full whitespace-nowrap border-collapse text-left">
            <thead class="bg-gray-50/50">
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="py-4 pl-6 pr-4">ID</th>
                    <th class="py-4 px-4">Product Name</th>
                    <th class="py-4 px-4">Genre</th>
                    <th class="py-4 px-4">Price</th>
                    <th class="py-4 px-4">Stock</th>
                    <th class="py-4 px-4">Rating</th>
                    <th class="py-4 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="py-4 pl-6 pr-4 font-medium text-gray-500">#{{ $product->product_ID }}</td>
                        <td class="py-4 px-4 font-semibold text-gray-900">{{ $product->Title }}</td>
                        <td class="py-4 px-4"><span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-xs font-medium">{{ $product->Genre }}</span></td>
                        <td class="py-4 px-4 font-medium">₱{{ number_format($product->Price, 2) }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $product->Stock > 5 ? 'bg-green-500' : ($product->Stock > 0 ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                                <span>{{ $product->Stock }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-1.5">
                                @if($product->Rating)
                                    <img src="{{ asset('images/StarVal.png') }}" class="w-[14px] h-[14px]" alt="Star" />
                                    <span>{{ $product->Rating }}</span>
                                @else
                                    <span>—</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Archive this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Archive">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <p class="text-gray-500 font-medium">No products found in inventory.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
        <span class="text-sm text-gray-500 font-medium">Showing {{ $products->count() }} of {{ $products->total() }} items</span>
        <div>{{ $products->links() }}</div>
    </div>
</div>
@endsection