<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const TIMEOUT_DAYS = 14;

    private function applyTimeoutFailures(): void
    {
        Order::query()
            ->where('order_status', 'Processing')
            ->whereDate('order_date', '<=', now()->subDays(self::TIMEOUT_DAYS)->toDateString())
            ->update(['order_status' => 'Failed']);
    }

    public function index(Request $request)
    {
        $this->applyTimeoutFailures();
        $timeoutDays = self::TIMEOUT_DAYS;

        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', 'all');

        $ordersQuery = Order::query()->with(['customer', 'paymentMethod']);

        if ($search !== '') {
            $ordersQuery->where(function ($query) use ($search) {
                if (is_numeric($search)) {
                    $query->orWhere('order_id', (int) $search);
                }

                $query->orWhereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        if ($status !== 'all') {
            $ordersQuery->where('order_status', $status);
        }

        $orders = $ordersQuery->orderByDesc('order_date')->orderByDesc('order_id')->paginate(12)->withQueryString();

        $allOrders = Order::query();
        $totalOrders = $allOrders->count();
        $pendingOrders = (clone $allOrders)->where('order_status', 'Pending')->count();
        $processingOrders = (clone $allOrders)->where('order_status', 'Processing')->count();
        $completedOrders = (clone $allOrders)->where('order_status', 'Completed')->count();
        $problemOrders = (clone $allOrders)->whereIn('order_status', ['Cancelled', 'Failed'])->count();

        $statusOptions = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed', 'Cancelled', 'Failed'];

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'status',
            'statusOptions',
            'timeoutDays',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'problemOrders'
        ));
    }

    public function handleEvent(Request $request, Order $order)
    {
        $validated = $request->validate([
            'event' => 'required|in:start_processing,ship,deliver,cancel,timeout_fail',
        ]);

        $event = $validated['event'];
        $nextStatus = null;

        if ($event === 'start_processing' && $order->order_status === 'Pending') {
            $nextStatus = 'Processing';
        }

        if ($event === 'ship' && $order->order_status === 'Processing') {
            $nextStatus = 'Shipped';
        }

        if ($event === 'deliver' && $order->order_status === 'Shipped') {
            $nextStatus = 'Delivered';
        }

        if ($event === 'cancel' && in_array($order->order_status, ['Pending', 'Processing', 'Shipped'], true)) {
            $nextStatus = 'Cancelled';
        }

        if (
            $event === 'timeout_fail' &&
            $order->order_status === 'Processing' &&
            $order->order_date->lte(now()->subDays(self::TIMEOUT_DAYS))
        ) {
            $nextStatus = 'Failed';
        }

        if (!$nextStatus) {
            return redirect()->back()->with('error', 'That event is not allowed for this order right now.');
        }

        $order->update([
            'order_status' => $nextStatus,
        ]);

        if ($order->customer) {
            $order->customer->notify(new OrderStatusNotification(
                $order,
                'Order Status Updated',
                'Your order status is now: ' . $nextStatus . '.'
            ));
        }

        return redirect()->back()->with('success', 'Order updated successfully.');
    }
}
