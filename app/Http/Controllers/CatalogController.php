<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Fetch dynamic genres for the sidebar filter
        $genres = Product::select('Genre')->distinct()->whereNotNull('Genre')->pluck('Genre');        

        // 2. Text Search (if you add a search input later)
        $query->when($request->search, function($q) use ($request) {
            $q->where(function($sub) use ($request) {
                $sub->where('Title', 'like', '%' . $request->search . '%')
                    ->orWhere('Author', 'like', '%' . $request->search . '%');
            });
        });

        // 3. Checkbox Arrays (Genre, Rating, Age)
        // Note: Language and Format are static in Blade for now, so we comment them out here to prevent DB errors
        $query->when($request->genre, fn($q) => $q->whereIn('Genre', $request->genre));
        $query->when($request->rating, fn($q) => $q->where('Rating', $request->rating));        
        $ageGroups = Product::select('Age_Group')->distinct()->whereNotNull('Age_Group')->pluck('Age_Group');        // $query->when($request->language, fn($q) => $q->whereIn('Language', $request->language));
        // $query->when($request->format, fn($q) => $q->whereIn('Format', $request->format));

        // 4. Number Ranges (Price & Publication Date)
        $query->when($request->filled('min_price'), fn($q) => $q->where('Price', '>=', $request->min_price));
        $query->when($request->filled('max_price'), fn($q) => $q->where('Price', '<=', $request->max_price));
        
        // If your database column is not 'created_at', change it to the correct date column.
        $query->when($request->filled('min_date'), fn($q) => $q->whereDate('created_at', '>=', $request->min_date));
        $query->when($request->filled('max_date'), fn($q) => $q->whereDate('created_at', '<=', $request->max_date));

        // 5. Paginate and keep query string for pagination links
        $products = $query->orderBy('Title')->paginate(15)->withQueryString();

        // 6. Return the single, final view with all data
        return view('catalog.index', compact('products', 'genres', 'ageGroups'));    }

    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}