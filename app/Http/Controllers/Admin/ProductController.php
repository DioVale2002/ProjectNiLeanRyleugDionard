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
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Reason: list all non-archived products with pagination
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $stock = trim((string) $request->query('stock', ''));
        $status = (string) $request->query('status', 'all');

        $productsQuery = Product::query()->whereDoesntHave('archives');

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

        $archivedProductsQuery = Archive::query()->with('product');

        if ($search !== '') {
            $archivedProductsQuery->whereHas('product', function ($query) use ($search) {
                $query->where('Title', 'like', "%{$search}%")
                    ->orWhere('Author', 'like', "%{$search}%");
            });
        }

        $archivedProducts = $archivedProductsQuery
            ->orderByDesc('archived_date')
            ->paginate(10, ['*'], 'archived_page')
            ->withQueryString();

        $allProducts = Product::query()->whereDoesntHave('archives');
        $totalProducts = $allProducts->count();
        $activeProducts = (clone $allProducts)->where('Stock', '>', 0)->count();
        $lowStockProducts = (clone $allProducts)->where('Stock', '<=', 5)->count();
        $ratingOneProducts = (clone $allProducts)->where('Rating', '<=', 1)->count();
        $archivedCount = Archive::query()->count();

        return view('admin.products.index', compact(
            'products',
            'archivedProducts',
            'search',
            'stock',
            'status',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'ratingOneProducts',
            'archivedCount'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['Stock'] = 0;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        unset($validated['image']);

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
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        unset($validated['image']);

        $product->update($validated);

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

    public function unarchive(Product $product)
    {
        Archive::query()->where('archivedProduct', $product->product_ID)->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product unarchived successfully.');
    }
}