<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->whereDoesntHave('archives')
            ->where('Stock', '>', 0);

        // 1. Fetch dynamic genres for the sidebar filter
        $genres = Product::query()
            ->whereDoesntHave('archives')
            ->select('Genre')
            ->distinct()
            ->whereNotNull('Genre')
            ->pluck('Genre');

        // 2. Text Search (if you add a search input later)
        $query->when($request->search, function($q) use ($request) {
            $q->where(function($sub) use ($request) {
                $sub->where('Title', 'like', '%' . $request->search . '%')
                    ->orWhere('Author', 'like', '%' . $request->search . '%');
            });
        });

        // 3. Checkbox Arrays (Genre, Rating, Age)
        // Note: Language and Format are static in Blade for now, so we comment them out here to prevent DB errors
        $query->when($request->genre, function ($q) use ($request) {
            $genres = $request->genre;
            $genres = is_array($genres) ? $genres : [$genres];
            $q->whereIn('Genre', $genres);
        });
        $query->when($request->rating, fn($q) => $q->where('Rating', $request->rating));
        $ageGroups = Product::query()
            ->whereDoesntHave('archives')
            ->select('Age_Group')
            ->distinct()
            ->whereNotNull('Age_Group')
            ->pluck('Age_Group');
        $query->when($request->agegroup, function ($q) use ($request) {
            $ageGroupsFilter = $request->agegroup;
            $ageGroupsFilter = is_array($ageGroupsFilter) ? $ageGroupsFilter : [$ageGroupsFilter];
            $q->whereIn('Age_Group', $ageGroupsFilter);
        });
        // $query->when($request->language, fn($q) => $q->whereIn('Language', $request->language));
        // $query->when($request->format, fn($q) => $q->whereIn('Format', $request->format));

        // 4. Number Ranges (Price & Publication Date)
        $query->when($request->filled('min_price'), fn($q) => $q->where('Price', '>=', $request->min_price));
        $query->when($request->filled('max_price'), fn($q) => $q->where('Price', '<=', $request->max_price));
        
        // If your database column is not 'created_at', change it to the correct date column.
        $query->when($request->filled('min_date'), fn($q) => $q->whereDate('Publication_Date', '>=', $request->min_date));
        $query->when($request->filled('max_date'), fn($q) => $q->whereDate('Publication_Date', '<=', $request->max_date));

        // 5. Paginate and keep query string for pagination links
        $products = $query->orderBy('Title')->paginate(15)->withQueryString();

        // 6. Return the single, final view with all data
        return view('catalog.index', compact('products', 'genres', 'ageGroups'));    }

    public function show(Product $product)
    {
        abort_if($product->archives()->exists(), 404);

        return view('catalog.show', compact('product'));
    }
}