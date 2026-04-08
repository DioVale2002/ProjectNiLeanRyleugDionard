@extends('admin.layouts.app')
@section('title', 'Product Details')

@section('content')
<div class="page-header">
    <h3>Product Details</h3>
    <div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.products.index') }}" class="btn">← Back</a>
    </div>
</div>

<div class="card">
    <table class="detail-table">
        <tr><th>ID</th><td>{{ $product->product_ID }}</td></tr>
        <tr><th>Title</th><td>{{ $product->Title }}</td></tr>
        <tr><th>Author</th><td>{{ $product->Author }}</td></tr>
        <tr><th>ISBN</th><td>{{ $product->ISBN }}</td></tr>
        <tr><th>Genre</th><td>{{ $product->Genre }}</td></tr>
        <tr><th>Publisher</th><td>{{ $product->Publisher }}</td></tr>
        <tr><th>Price</th><td>₱{{ number_format($product->Price, 2) }}</td></tr>
        <tr><th>Stock</th><td>{{ $product->Stock }}</td></tr>
        <tr><th>Rating</th><td>{{ $product->Rating ?? '—' }}</td></tr>
        <tr><th>Age Group</th><td>{{ $product->Age_Group ?? '—' }}</td></tr>
        <tr><th>Length</th><td>{{ $product->Length ?? '—' }}</td></tr>
        <tr><th>Width</th><td>{{ $product->Width ?? '—' }}</td></tr>
        <tr><th>Review</th><td>{{ $product->Review ?? '—' }}</td></tr>
    </table>
</div>
@endsection