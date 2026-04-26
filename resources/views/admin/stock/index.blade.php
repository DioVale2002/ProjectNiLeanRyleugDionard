@extends('admin.layouts.app')
@section('title', 'Stock Management')

@section('content')
<div class="font-sans">
    
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Stock Management</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor inventory levels and record stock movements.</p>
        </div>
    </div>

    {{-- Metrics Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Total Products (Red Gradient) --}}
        <div class="rounded-2xl bg-gradient-to-br from-[#F54E4E] to-[#d63a3a] p-6 shadow-md flex flex-col justify-between h-[130px] relative overflow-hidden">
            <svg class="absolute right-[-10px] bottom-[-20px] w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
            <p class="text-white/80 font-medium text-sm uppercase tracking-wide z-10">All Products</p>
            <p class="text-4xl font-bold text-white z-10">{{ $totalProducts }}</p>
        </div>
        
        {{-- Active --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[130px]">
            <div class="flex items-center gap-2">
                <div class="bg-green-100 p-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-gray-500 font-medium text-sm uppercase tracking-wide">Active</p>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $activeProducts }}</p>
        </div>

        {{-- Low Stock --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[130px]">
            <div class="flex items-center gap-2">
                <div class="bg-yellow-100 p-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="text-gray-500 font-medium text-sm uppercase tracking-wide">Low Stock</p>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $lowStockProducts }}</p>
        </div>

        {{-- Out of Stock --}}
        <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm flex flex-col justify-between h-[130px]">
            <div class="flex items-center gap-2">
                <div class="bg-red-100 p-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-500 font-medium text-sm uppercase tracking-wide">Out of Stock</p>
            </div>
            <p class="text-4xl font-bold text-gray-800">{{ $outOfStockProducts }}</p>
        </div>
    </div>

    {{-- Stock Actions (In / Out) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        {{-- Stock IN Panel --}}
        <div class="rounded-2xl bg-white p-7 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <div class="bg-[#FCAE42]/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-[#FCAE42]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Receive Stock (In)</h3>
            </div>
            
            <form action="{{ route('admin.stock.in') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Product</label>
                    <select name="productIn" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors bg-white" required>
                        <option value="">-- Choose a product --</option>
                        @foreach($inventoryProducts as $product)
                            <option value="{{ $product->product_ID }}">{{ $product->Title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Received</label>
                        <input type="date" name="stockIn_date" value="{{ date('Y-m-d') }}" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Added</label>
                        <input type="number" name="quantity" min="1" placeholder="0" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-[#FCAE42] focus:ring-1 focus:ring-[#FCAE42] transition-colors" required />
                    </div>
                </div>
                <button type="submit" class="mt-2 w-full py-3 rounded-xl bg-[#F54E4E] hover:bg-yellow-500 transition-colors text-white font-bold flex justify-center items-center gap-2">
                    
                    Add to Inventory
                </button>
            </form>
        </div>

        {{-- Stock OUT Panel --}}
        <div class="rounded-2xl bg-white p-7 shadow-sm border border-gray-100">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <div class="bg-red-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Deduct Stock (Out)</h3>
            </div>

            <form action="{{ route('admin.stock.out') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Product</label>
                    <select name="productOut" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors bg-white" required>
                        <option value="">-- Choose a product --</option>
                        @foreach($inventoryProducts as $product)
                            <option value="{{ $product->product_ID }}">{{ $product->Title }} ({{ $product->Stock }} available)</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Removed</label>
                        <input type="date" name="stockOut_date" value="{{ date('Y-m-d') }}" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Removed</label>
                        <input type="number" name="quantity" min="1" placeholder="0" class="w-full h-11 rounded-lg border border-gray-300 px-4 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors" required />
                    </div>
                </div>
                <button type="submit" class="mt-2 w-full py-3 rounded-xl bg-[#F54E4E] hover:bg-yellow-500 transition-colors text-white font-bold flex justify-center items-center gap-2">
                    
                    Remove from Inventory
                </button>
            </form>
        </div>
    </div>

    {{-- Main Inventory Filter & Table --}}
    <div class="mt-8 rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-5 bg-gray-50 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700">Inventory Status</h3>
            <form method="GET" action="{{ route('admin.stock.index') }}" class="flex flex-wrap items-center gap-3">
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
                    <a href="{{ route('admin.stock.index') }}" class="text-sm font-medium text-red-500 hover:text-red-700 ml-2">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 pl-6 pr-4">ID</th>
                        <th class="py-4 px-4">Book</th>
                        <th class="py-4 px-4">Author</th>
                        <th class="py-4 px-4">Current Stock</th>
                        <th class="py-4 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="py-4 pl-6 pr-4 font-medium text-gray-500">#{{ $product->product_ID }}</td>
                            <td class="py-4 px-4 font-semibold text-gray-900">{{ $product->Title }}</td>
                            <td class="py-4 px-4">{{ $product->Author }}</td>
                            <td class="py-4 px-4 font-medium">{{ $product->Stock }}</td>
                            <td class="py-4 px-4">
                                @if($product->Stock === 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Out of stock
                                    </span>
                                @elseif($product->Stock <= 5)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Low stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">No products match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500 bg-gray-50/50">
            <span class="font-medium">Showing {{ $products->count() }} of {{ $products->total() }} items</span>
            <div>{{ $products->links() }}</div>
        </div>
    </div>

    {{-- History Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        
        {{-- Recent Stock In --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Recent Stock In</h3>
                <span class="text-xs font-medium text-[#FCAE42] bg-[#FCAE42]/10 px-2 py-1 rounded-md">Additions</span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-white">
                        <tr class="border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                            <th class="py-3 pl-5 pr-4">ID</th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($stockIns as $s)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 pl-5 pr-4 text-gray-500 text-xs">#{{ $s->stockIn_id }}</td>
                                <td class="py-3 px-4 font-medium text-gray-800">{{ $s->product->Title ?? '—' }}</td>
                                <td class="py-3 px-4 text-gray-500 text-right text-xs">{{ \Illuminate\Support\Carbon::parse($s->stockIn_date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-400">No recent stock additions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Stock Out --}}
        <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Recent Stock Out</h3>
                <span class="text-xs font-medium text-red-500 bg-red-50 px-2 py-1 rounded-md">Deductions</span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead class="bg-white">
                        <tr class="border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                            <th class="py-3 pl-5 pr-4">ID</th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($stockOuts as $s)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 pl-5 pr-4 text-gray-500 text-xs">#{{ $s->stockOut_id }}</td>
                                <td class="py-3 px-4 font-medium text-gray-800">{{ $s->product->Title ?? '—' }}</td>
                                <td class="py-3 px-4 text-gray-500 text-right text-xs">{{ \Illuminate\Support\Carbon::parse($s->stockOut_date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-400">No recent stock deductions.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection