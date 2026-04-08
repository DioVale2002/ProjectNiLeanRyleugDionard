<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function getOrCreateCart()
    {
        $customer = Auth::guard('customer')->user();

        $cart = Cart::where('cus_id', $customer->cus_id)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'createdDate' => now()->toDateString(),
                'status'      => 'active',
                'cus_id'      => $customer->cus_id,
            ]);
        }

        return $cart;
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $cart = Cart::where('cus_id', $customer->cus_id)
            ->where('status', 'active')
            ->with('items.product')
            ->first();

        $total = $cart ? $cart->items->sum('subtotal') : 0;

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(StoreCartItemRequest $request)
    {
        $product = Product::findOrFail($request->product_ID);

        abort_if($product->Stock < $request->quantity, 422, 'Insufficient stock.');

        $cart = $this->getOrCreateCart();

        // If product already in cart, increment quantity
        $existing = $cart->items()->where('product_ID', $product->product_ID)->first();

        if ($existing) {
            $newQty = $existing->quantity + $request->quantity;
            abort_if($product->Stock < $newQty, 422, 'Insufficient stock.');

            $existing->update([
                'quantity' => $newQty,
                'subtotal' => $product->Price * $newQty,
            ]);
        } else {
            CartItem::create([
                'cart_id'    => $cart->cart_id,
                'product_ID' => $product->product_ID,
                'quantity'   => $request->quantity,
                'unitPrice'  => $product->Price,
                'subtotal'   => $product->Price * $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(CartItem $cartItem)
    {
        $quantity = request()->validate([
            'quantity' => 'required|integer|min:1',
        ])['quantity'];

        $product = $cartItem->product;
        abort_if($product->Stock < $quantity, 422, 'Insufficient stock.');

        $cartItem->update([
            'quantity' => $quantity,
            'subtotal' => $product->Price * $quantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }
}