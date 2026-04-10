<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $minPrice = request()->filled('min_price') ? (float) request('min_price') : null;
        $maxPrice = request()->filled('max_price') ? (float) request('max_price') : null;

        $products = Product::where('Stock', '>', 0)
            ->when(request('search'), fn($q) =>
                $q->where('Title', 'like', '%' . request('search') . '%')
                  ->orWhere('Author', 'like', '%' . request('search') . '%')
            )
            ->when(request('genre'), fn($q) =>
                $q->where('Genre', request('genre'))
            )
            ->when($minPrice !== null, fn($q) =>
                $q->where('Price', '>=', $minPrice)
            )
            ->when($maxPrice !== null, fn($q) =>
                $q->where('Price', '<=', $maxPrice)
            )
            ->orderBy('Title')
            ->paginate(12)
            ->withQueryString();

        $genres = Product::select('Genre')->distinct()->orderBy('Genre')->pluck('Genre');

        return view('catalog.index', compact('products', 'genres'));
    }

    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}