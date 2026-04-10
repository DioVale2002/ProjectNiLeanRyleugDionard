<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockInRequest;
use App\Http\Requests\Admin\StockOutRequest;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $stock = trim((string) $request->query('stock', ''));
        $status = (string) $request->query('status', 'all');

        $inventoryQuery = Product::query();

        if ($search !== '') {
            $inventoryQuery->where(function ($query) use ($search) {
                $query->where('Title', 'like', "%{$search}%")
                    ->orWhere('Author', 'like', "%{$search}%");
            });
        }

        if ($stock !== '' && is_numeric($stock)) {
            $inventoryQuery->where('Stock', (int) $stock);
        }

        if ($status === 'active') {
            $inventoryQuery->where('Stock', '>', 0);
        } elseif ($status === 'low') {
            $inventoryQuery->whereBetween('Stock', [1, 5]);
        } elseif ($status === 'out') {
            $inventoryQuery->where('Stock', 0);
        }

        $products = $inventoryQuery->orderBy('Title')->paginate(10)->withQueryString();
        $inventoryProducts = Product::orderBy('Title')->get();
        $stockIns = StockIn::with('product')->latest()->take(20)->get();
        $stockOuts = StockOut::with('product')->latest()->take(20)->get();

        $allProducts = Product::query();
        $totalProducts = $allProducts->count();
        $activeProducts = (clone $allProducts)->where('Stock', '>', 0)->count();
        $lowStockProducts = (clone $allProducts)->whereBetween('Stock', [1, 5])->count();
        $outOfStockProducts = (clone $allProducts)->where('Stock', 0)->count();

        return view('admin.stock.index', compact(
            'products',
            'inventoryProducts',
            'stockIns',
            'stockOuts',
            'search',
            'stock',
            'status',
            'totalProducts',
            'activeProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    // Reason: stock increase — log entry + increment product stock atomically
    public function storeIn(StockInRequest $request)
    {
        DB::transaction(function () use ($request) {
            StockIn::create([
                'stockIn_date' => $request->stockIn_date,
                'productIn'    => $request->productIn,
            ]);

            Product::where('product_ID', $request->productIn)
                ->increment('Stock', $request->quantity);
        });

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock increased successfully.');
    }

    // Reason: stock decrease — guard against negative stock before decrement
    public function storeOut(StockOutRequest $request)
    {
        $product = Product::findOrFail($request->productOut);

        abort_if(
            $product->Stock < $request->quantity,
            422,
            'Insufficient stock.'
        );

        DB::transaction(function () use ($request, $product) {
            StockOut::create([
                'stockOut_date' => $request->stockOut_date,
                'productOut'    => $request->productOut,
            ]);

            $product->decrement('Stock', $request->quantity);
        });

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stock decreased successfully.');
    }
}