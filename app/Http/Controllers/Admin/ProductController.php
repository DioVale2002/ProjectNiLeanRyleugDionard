<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Archive;
use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Reason: list all non-archived products with pagination
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $stock = trim((string) $request->query('stock', ''));
        $status = (string) $request->query('status', 'all');

        $productsQuery = Product::query();

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('Title', 'like', "%{$search}%")
                    ->orWhere('Author', 'like', "%{$search}%");
            });
        }

        if ($stock !== '' && is_numeric($stock)) {
            $productsQuery->where('Stock', (int) $stock);
        }

        if ($status === 'active') {
            $productsQuery->where('Stock', '>', 0);
        } elseif ($status === 'low') {
            $productsQuery->whereBetween('Stock', [1, 5]);
        } elseif ($status === 'out') {
            $productsQuery->where('Stock', 0);
        }

        $products = $productsQuery->orderBy('Title')->paginate(10)->withQueryString();

        $allProducts = Product::query();
        $totalProducts = $allProducts->count();
        $activeProducts = (clone $allProducts)->where('Stock', '>', 0)->count();
        $lowStockProducts = (clone $allProducts)->where('Stock', '<=', 5)->count();
        $ratingOneProducts = (clone $allProducts)->where('Rating', '<=', 1)->count();

        return view('admin.products.index', compact(
            'products',
            'search',
            'stock',
            'status',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'ratingOneProducts'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            if (($validated['Stock'] ?? 0) > 0) {
                StockIn::create([
                    'stockIn_date' => now()->toDateString(),
                    'productIn' => $product->product_ID,
                ]);
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Reason: archive instead of hard delete to preserve historical records
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            Archive::create([
                'archived_date'   => now()->toDateString(),
                'archivedProduct' => $product->product_ID,
            ]);
            $product->Stock = 0;
            $product->save();
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product archived successfully.');
    }
}