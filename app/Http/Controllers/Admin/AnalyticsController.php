<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = (string) $request->query('period', 'monthly');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo = (string) $request->query('date_to', '');

        [$from, $to] = $this->resolveDateRange($period, $dateFrom, $dateTo);

        $ordersInRange = Order::query()->whereBetween('order_date', [$from->toDateString(), $to->toDateString()]);
        $salesOrders = Order::query()
            ->whereBetween('order_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotIn('order_status', ['Cancelled', 'Failed']);

        $totalOrders = (clone $ordersInRange)->count();
        $pendingOrders = (clone $ordersInRange)->where('order_status', 'Pending')->count();
        $processingOrders = (clone $ordersInRange)->where('order_status', 'Processing')->count();
        $completedOrders = (clone $ordersInRange)->where('order_status', 'Completed')->count();

        $totalRevenue = (float) (clone $salesOrders)->sum('total_price');
        $completedRevenue = (float) (clone $ordersInRange)
            ->where('order_status', 'Completed')
            ->sum('total_price');
        $averageOrderValue = $completedOrders > 0 ? ($completedRevenue / $completedOrders) : 0;

        $performanceRows = Order::query()
            ->selectRaw('order_date as period_date, COUNT(*) as order_count, SUM(total_price) as revenue')
            ->whereBetween('order_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotIn('order_status', ['Cancelled', 'Failed'])
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();

        $topSelling = Sale::query()
            ->join('orders', 'orders.order_id', '=', 'sales.order_id')
            ->join('products', 'products.product_ID', '=', 'sales.product_id')
            ->whereBetween('orders.order_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotIn('orders.order_status', ['Cancelled', 'Failed'])
            ->selectRaw('sales.product_id, products.Title as title, SUM(sales.quantity) as units_sold, SUM(sales.total_price) as revenue')
            ->groupBy('sales.product_id', 'products.Title')
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get();

        $salesByProduct = Sale::query()
            ->join('orders', 'orders.order_id', '=', 'sales.order_id')
            ->whereBetween('orders.order_date', [$from->toDateString(), $to->toDateString()])
            ->whereNotIn('orders.order_status', ['Cancelled', 'Failed'])
            ->selectRaw('sales.product_id, SUM(sales.quantity) as units_sold, SUM(sales.total_price) as revenue')
            ->groupBy('sales.product_id');

        $lowSelling = Product::query()
            ->leftJoinSub($salesByProduct, 'sales_range', function ($join) {
                $join->on('products.product_ID', '=', 'sales_range.product_id');
            })
            ->selectRaw('products.product_ID, products.Title as title, COALESCE(sales_range.units_sold, 0) as units_sold, COALESCE(sales_range.revenue, 0) as revenue, products.Stock')
            ->orderBy('units_sold')
            ->orderByDesc('products.Stock')
            ->limit(10)
            ->get();

        $activeProducts = Product::query()->where('Stock', '>', 0)->count();
        $lowStockProducts = Product::query()->whereBetween('Stock', [1, 5])->count();
        $outOfStockProducts = Product::query()->where('Stock', 0)->count();

        $stockInCount = StockIn::query()
            ->whereBetween('stockIn_date', [$from->toDateString(), $to->toDateString()])
            ->count();
        $stockOutCount = StockOut::query()
            ->whereBetween('stockOut_date', [$from->toDateString(), $to->toDateString()])
            ->count();

        return view('admin.analytics.index', [
            'period' => $period,
            'dateFrom' => $from->toDateString(),
            'dateTo' => $to->toDateString(),
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'completedOrders' => $completedOrders,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue,
            'performanceRows' => $performanceRows,
            'topSelling' => $topSelling,
            'lowSelling' => $lowSelling,
            'activeProducts' => $activeProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'stockInCount' => $stockInCount,
            'stockOutCount' => $stockOutCount,
        ]);
    }

    private function resolveDateRange(string $period, string $dateFrom, string $dateTo): array
    {
        if ($period === 'daily') {
            return [now()->startOfDay(), now()->endOfDay()];
        }

        if ($period === 'yearly') {
            return [now()->startOfYear(), now()->endOfDay()];
        }

        if ($period === 'custom' && $dateFrom !== '' && $dateTo !== '') {
            $from = Carbon::parse($dateFrom)->startOfDay();
            $to = Carbon::parse($dateTo)->endOfDay();

            if ($to->lt($from)) {
                return [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            return [$from, $to];
        }

        return [now()->startOfMonth(), now()->endOfDay()];
    }
}
