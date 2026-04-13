<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Text Search (if you add a search input later)
        $query->when($request->search, function($q) use ($request) {
            $q->where(function($sub) use ($request) {
                $sub->where('Title', 'like', '%' . $request->search . '%')
                    ->orWhere('Author', 'like', '%' . $request->search . '%');
            });
        });

        // 2. Checkbox Arrays (Genre, Language, Format, Rating, Age)
        $query->when($request->genre, fn($q) => $q->whereIn('Genre', $request->genre));
        $query->when($request->language, fn($q) => $q->whereIn('Language', $request->language));
        $query->when($request->format, fn($q) => $q->whereIn('Format', $request->format));
        $query->when($request->rating, fn($q) => $q->whereIn('Rating', $request->rating));
        $query->when($request->age, fn($q) => $q->whereIn('Age_Group', $request->age));

        // 3. Number Ranges (Price & Publication Year)
        $query->when($request->filled('min_price'), fn($q) => $q->where('Price', '>=', $request->min_price));
        $query->when($request->filled('max_price'), fn($q) => $q->where('Price', '<=', $request->max_price));
        $query->when($request->filled('year_start'), fn($q) => $q->where('Publication_Year', '>=', $request->year_start));
        $query->when($request->filled('year_end'), fn($q) => $q->where('Publication_Year', '<=', $request->year_end));

        // Paginate and keep query string for pagination links
        $products = $query->orderBy('Title')->paginate(15)->withQueryString();

        return view('catalog.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}