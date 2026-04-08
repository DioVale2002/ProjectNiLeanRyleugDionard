<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Sale;
use App\Models\StockOut;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(PlaceOrderRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        $cart = Cart::where('cus_id', $customer->cus_id)
            ->where('status', 'active')
            ->with('items.product')
            ->first();

        abort_if(!$cart || $cart->items->isEmpty(), 422, 'Your cart is empty.');

        $total = $cart->items->sum('subtotal');

        // Apply voucher discount if provided
        if ($request->voucher_id) {
            $voucher = Voucher::findOrFail($request->voucher_id);
            if ($voucher->voucherType === 'percentage') {
                $total = $total - ($total * $voucher->voucherAmount / 100);
            } else {
                $total = max(0, $total - $voucher->voucherAmount);
            }
            $voucher->increment('voucherUsed');
        }

        DB::transaction(function () use ($request, $customer, $cart, $total) {
            // Place order
            $order = Order::create([
                'order_status'     => 'Pending',
                'order_date'       => now()->toDateString(),
                'total_price'      => $total,
                'voucher_id'       => $request->voucher_id,
                'add_id'           => $request->add_id,
                'paymentMethod_id' => $request->paymentMethod_id,
                'cus_id'           => $customer->cus_id,
                'cart_id'          => $cart->cart_id,
            ]);

            foreach ($cart->items as $item) {
                // Record sale
                Sale::create([
                    'order_id'    => $order->order_id,
                    'product_id'  => $item->product_ID,
                    'quantity'    => $item->quantity,
                    'total_price' => $item->subtotal,
                ]);

                // Record stock out
                StockOut::create([
                    'stockOut_date' => now()->toDateString(),
                    'productOut'    => $item->product_ID,
                ]);

                // Decrement stock
                $item->product->decrement('Stock', $item->quantity);
            }

            // Mark cart as checked out
            $cart->update(['status' => 'checked_out']);
        });

        return redirect()->route('account.orders')->with('success', 'Order placed successfully!');
    }
}