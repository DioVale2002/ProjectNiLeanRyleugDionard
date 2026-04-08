<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockInRequest;
use App\Http\Requests\Admin\StockOutRequest;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $products  = Product::orderBy('Title')->paginate(10);
        $stockIns  = StockIn::with('product')->latest()->take(20)->get();
        $stockOuts = StockOut::with('product')->latest()->take(20)->get();
        return view('admin.stock.index', compact('products', 'stockIns', 'stockOuts'));
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