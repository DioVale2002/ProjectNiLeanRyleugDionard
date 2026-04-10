@extends('admin.layouts.app')
@section('title', 'Stock Management')

@section('content')
<div class="mx-[70px] mt-8 pb-10 font-sans">
    <div class="flex items-center justify-between mb-3.5">
        <p class="text-xl text-black/60">Stock Management</p>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl bg-[#F54E4E] px-4 py-3 h-[108px] text-white shadow-sm">
            <p class="text-sm">All Products</p>
            <p class="mt-8 text-2xl font-semibold">{{ $totalProducts }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Active</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $activeProducts }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Low Stock</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $lowStockProducts }}</p>
        </div>
        <div class="rounded-2xl bg-white px-4 py-3 h-[108px] shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Out of Stock</p>
            <p class="mt-8 text-2xl font-semibold text-gray-900">{{ $outOfStockProducts }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-6 text-lg font-semibold text-gray-700">Stock In</h3>
            <form action="{{ route('admin.stock.in') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <select name="productIn" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required>
                    <option value="">-- Select Product --</option>
                    @foreach($inventoryProducts as $product)
                        <option value="{{ $product->product_ID }}">{{ $product->Title }}</option>
                    @endforeach
                </select>
                <input type="date" name="stockIn_date" value="{{ date('Y-m-d') }}" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required />
                <input type="number" name="quantity" min="1" placeholder="Quantity" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required />
                <button type="submit" class="w-[161px] h-[36px] rounded-xl bg-[#F54E4E] text-white">Add Stock</button>
            </form>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-6 text-lg font-semibold text-gray-700">Stock Out</h3>
            <form action="{{ route('admin.stock.out') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <select name="productOut" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required>
                    <option value="">-- Select Product --</option>
                    @foreach($inventoryProducts as $product)
                        <option value="{{ $product->product_ID }}">{{ $product->Title }} ({{ $product->Stock }} in stock)</option>
                    @endforeach
                </select>
                <input type="date" name="stockOut_date" value="{{ date('Y-m-d') }}" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required />
                <input type="number" name="quantity" min="1" placeholder="Quantity" class="h-[52px] rounded-xl border border-black/50 bg-white px-[16px] py-[8px]" required />
                <button type="submit" class="w-[161px] h-[36px] rounded-xl bg-[#F54E4E] text-white">Remove Stock</button>
            </form>
        </div>
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Filter Inventory</h3>
            <form method="GET" action="{{ route('admin.stock.index') }}" class="flex flex-wrap items-center gap-3">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search book name or author" class="h-[42px] w-[240px] rounded-md border border-gray-300 px-4 text-sm outline-none" />
                <input type="number" name="stock" value="{{ $stock }}" placeholder="Exact stock" min="0" class="h-[42px] w-[140px] rounded-md border border-gray-300 px-4 text-sm outline-none" />
                <select name="status" class="h-[42px] w-[160px] rounded-md border border-gray-300 px-4 text-sm outline-none">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All stock</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active only</option>
                    <option value="low" {{ $status === 'low' ? 'selected' : '' }}>Low stock</option>
                    <option value="out" {{ $status === 'out' ? 'selected' : '' }}>Out of stock</option>
                </select>
                <button type="submit" class="h-[42px] rounded-md bg-[#FCAE42] px-5 text-sm text-white">Search</button>
                @if($search !== '' || $stock !== '' || $status !== 'all')
                    <a href="{{ route('admin.stock.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-black">
                        <th class="py-3">ID</th>
                        <th class="py-3">Book</th>
                        <th class="py-3">Author</th>
                        <th class="py-3">Current Stock</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b border-gray-100">
                            <td class="py-3">{{ $product->product_ID }}</td>
                            <td class="py-3">{{ $product->Title }}</td>
                            <td class="py-3">{{ $product->Author }}</td>
                            <td class="py-3">{{ $product->Stock }}</td>
                            <td class="py-3">
                                @if($product->Stock === 0)
                                    Out of stock
                                @elseif($product->Stock <= 5)
                                    Low stock
                                @else
                                    Active
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">No products match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <span>{{ $products->total() }} items</span>
            <div>{{ $products->links() }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mt-8">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Recent Stock In</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead><tr class="border-b border-gray-200 text-black"><th class="py-3">ID</th><th class="py-3">Product</th><th class="py-3">Date</th></tr></thead>
                    <tbody>
                        @forelse($stockIns as $s)
                            <tr class="border-b border-gray-100">
                                <td class="py-3">{{ $s->stockIn_id }}</td>
                                <td class="py-3">{{ $s->product->Title ?? '—' }}</td>
                                <td class="py-3">{{ \Illuminate\Support\Carbon::parse($s->stockIn_date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-gray-500">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Recent Stock Out</h3>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">
                    <thead><tr class="border-b border-gray-200 text-black"><th class="py-3">ID</th><th class="py-3">Product</th><th class="py-3">Date</th></tr></thead>
                    <tbody>
                        @forelse($stockOuts as $s)
                            <tr class="border-b border-gray-100">
                                <td class="py-3">{{ $s->stockOut_id }}</td>
                                <td class="py-3">{{ $s->product->Title ?? '—' }}</td>
                                <td class="py-3">{{ \Illuminate\Support\Carbon::parse($s->stockOut_date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-gray-500">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection