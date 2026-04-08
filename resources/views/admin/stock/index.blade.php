@extends('admin.layouts.app')
@section('title', 'Stock Management')

@section('content')

{{-- Stock In Form --}}
<div class="card form-card">
    <h3>Stock In</h3>
    <form action="{{ route('admin.stock.in') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Product</label>
                <select name="productIn" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_ID }}">{{ $product->Title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="stockIn_date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Stock</button>
    </form>
</div>

{{-- Stock Out Form --}}
<div class="card form-card">
    <h3>Stock Out</h3>
    <form action="{{ route('admin.stock.out') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Product</label>
                <select name="productOut" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_ID }}">
                            {{ $product->Title }} ({{ $product->Stock }} in stock)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="stockOut_date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" required>
            </div>
        </div>
        <button type="submit" class="btn btn-danger">Remove Stock</button>
    </form>
</div>

{{-- Stock In Log --}}
<div class="card">
    <h3>Recent Stock In</h3>
    <table class="admin-table">
        <thead>
            <tr><th>ID</th><th>Product</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse($stockIns as $s)
            <tr>
                <td>{{ $s->stockIn_id }}</td>
                <td>{{ $s->product->Title ?? '—' }}</td>
                <td>{{ $s->stockIn_date }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Stock Out Log --}}
<div class="card">
    <h3>Recent Stock Out</h3>
    <table class="admin-table">
        <thead>
            <tr><th>ID</th><th>Product</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse($stockOuts as $s)
            <tr>
                <td>{{ $s->stockOut_id }}</td>
                <td>{{ $s->product->Title ?? '—' }}</td>
                <td>{{ $s->stockOut_date }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection