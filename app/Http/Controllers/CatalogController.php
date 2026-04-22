<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $minPrice = request()->filled('min_price') ? (float) request('min_price') : null;
        $maxPrice = request()->filled('max_price') ? (float) request('max_price') : null;
        $publicationDateFrom = request('publication_date_from');
        $publicationDateTo = request('publication_date_to');
        $search = trim((string) request('search', ''));

        $products = Product::where('Stock', '>', 0)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('Title', 'like', '%' . $search . '%')
                        ->orWhere('Author', 'like', '%' . $search . '%')
                        ->orWhere('ISBN', 'like', '%' . $search . '%')
                        ->orWhere('Publisher', 'like', '%' . $search . '%')
                        ->orWhere('Genre', 'like', '%' . $search . '%')
                        ->orWhere('Review', 'like', '%' . $search . '%')
                        ->orWhere('Description', 'like', '%' . $search . '%');
                });
            })
            ->when(request('genre'), fn($q) => $q->where('Genre', request('genre')))
            ->when(request('subject'), fn($q) => $q->where('Subject', request('subject')))
            ->when(request('branch'), fn($q) => $q->where('Branch', request('branch')))
            ->when(request('format'), fn($q) => $q->where('Format', request('format')))
            ->when(request('language'), fn($q) => $q->where('Language', request('language')))
            ->when($minPrice !== null, fn($q) =>
                $q->where('Price', '>=', $minPrice)
            )
            ->when($maxPrice !== null, fn($q) =>
                $q->where('Price', '<=', $maxPrice)
            )
            ->when($publicationDateFrom, fn($q) =>
                $q->whereDate('Publication_Date', '>=', $publicationDateFrom)
            )
            ->when($publicationDateTo, fn($q) =>
                $q->whereDate('Publication_Date', '<=', $publicationDateTo)
            )
            ->orderBy('Title')
            ->paginate(12)
            ->withQueryString();

        $genres = Product::select('Genre')->distinct()->orderBy('Genre')->pluck('Genre');
        $subjects = Product::whereNotNull('Subject')->select('Subject')->distinct()->orderBy('Subject')->pluck('Subject');
        $branches = Product::whereNotNull('Branch')->select('Branch')->distinct()->orderBy('Branch')->pluck('Branch');
        $formats = Product::whereNotNull('Format')->select('Format')->distinct()->orderBy('Format')->pluck('Format');
        $languages = Product::whereNotNull('Language')->select('Language')->distinct()->orderBy('Language')->pluck('Language');

        return view('catalog.index', compact('products', 'genres', 'subjects', 'branches', 'formats', 'languages'));
    }

    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}