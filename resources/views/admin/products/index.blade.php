@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
<div class="page-header">
    <h3>All Products</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Price</th>
                <th>Stock</th>
                <th>ISBN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->product_ID }}</td>
                <td>{{ $product->Title }}</td>
                <td>{{ $product->Author }}</td>
                <td>{{ $product->Genre }}</td>
                <td>₱{{ number_format($product->Price, 2) }}</td>
                <td>{{ $product->Stock }}</td>
                <td>{{ $product->ISBN }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm">View</a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Archive this product?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Archive</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $products->links() }}</div>
</div>
@endsection