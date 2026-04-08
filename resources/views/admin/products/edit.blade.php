@extends('admin.layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="page-header">
    <h3>Edit Product</h3>
    <a href="{{ route('admin.products.index') }}" class="btn">← Back</a>
</div>

<div class="card form-card">
    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="Title" value="{{ old('Title', $product->Title) }}" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="Author" value="{{ old('Author', $product->Author) }}" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price (₱)</label>
                <input type="number" name="Price" step="0.01" min="0" value="{{ old('Price', $product->Price) }}" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="Stock" min="0" value="{{ old('Stock', $product->Stock) }}" required>
            </div>
        </div>
        <div class="form-group">
            <label>ISBN</label>
            <input type="text" name="ISBN" value="{{ old('ISBN', $product->ISBN) }}" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Publisher</label>
                <input type="text" name="Publisher" value="{{ old('Publisher', $product->Publisher) }}" required>
            </div>
            <div class="form-group">
                <label>Genre</label>
                <input type="text" name="Genre" value="{{ old('Genre', $product->Genre) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Rating (0–5)</label>
                <input type="number" name="Rating" step="0.01" min="0" max="5" value="{{ old('Rating', $product->Rating) }}">
            </div>
            <div class="form-group">
                <label>Age Group</label>
                <input type="text" name="Age_Group" value="{{ old('Age_Group', $product->Age_Group) }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Length</label>
                <input type="number" name="Length" min="0" value="{{ old('Length', $product->Length) }}">
            </div>
            <div class="form-group">
                <label>Width</label>
                <input type="number" name="Width" min="0" value="{{ old('Width', $product->Width) }}">
            </div>
        </div>
        <div class="form-group">
            <label>Review</label>
            <textarea name="Review" rows="3">{{ old('Review', $product->Review) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection