@extends('admin.layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="page-header">
    <h3>Add New Product</h3>
    <a href="{{ route('admin.products.index') }}" class="btn">← Back</a>
</div>

<div class="card form-card">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="Title" value="{{ old('Title') }}" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="Author" value="{{ old('Author') }}" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price (₱)</label>
                <input type="number" name="Price" step="0.01" min="0" value="{{ old('Price') }}" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="Stock" min="0" value="{{ old('Stock') }}" required>
            </div>
        </div>
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="ISBN" value="{{ old('ISBN') }}" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Publisher</label>
                <input type="text" name="Publisher" value="{{ old('Publisher') }}" required>
            </div>
            <div class="form-group">
                <label>Genre</label>
                <input type="text" name="Genre" value="{{ old('Genre') }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Rating (0–5)</label>
                <input type="number" name="Rating" step="0.01" min="0" max="5" value="{{ old('Rating') }}">
            </div>
            <div class="form-group">
                <label>Age Group</label>
                <input type="text" name="Age_Group" value="{{ old('Age_Group') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Length</label>
                <input type="number" name="Length" min="0" value="{{ old('Length') }}">
            </div>
            <div class="form-group">
                <label>Width</label>
                <input type="number" name="Width" min="0" value="{{ old('Width') }}">
            </div>
        </div>
        <div class="form-group">
            <label>Review</label>
            <textarea name="Review" rows="3">{{ old('Review') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
</div>
@endsection