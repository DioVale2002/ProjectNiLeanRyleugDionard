<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Notifications\OrderStatusNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\StockOut;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class OrderController extends Controller
{
    protected function getActiveCartForCustomer($customer): Cart
    {
        $cart = Cart::where('cus_id', $customer->cus_id)
            ->where('status', 'active')
            ->with('items.product')
            ->first();

        abort_if(!$cart || $cart->items->isEmpty(), 422, 'Your cart is empty.');

        return $cart;
    }

    protected function computeDiscountedTotal(Cart $cart, ?int $voucherId): array
    {
        $subtotal = (float) $cart->items->sum('subtotal');
        $discount = 0.0;
        $voucher = null;

        if ($voucherId) {
            $voucher = Voucher::findOrFail($voucherId);

            if (!$voucher->is_active) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'Selected voucher is inactive.',
                ]);
            }

            if ($voucher->valid_from && now()->toDateString() < $voucher->valid_from->toDateString()) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'Selected voucher is not active yet.',
                ]);
            }

            if ($voucher->valid_until && now()->toDateString() > $voucher->valid_until->toDateString()) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'Selected voucher has already expired.',
                ]);
            }

            if ($voucher->minimum_order_amount !== null && $subtotal < (float) $voucher->minimum_order_amount) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'This voucher requires a minimum order amount of ₱' . number_format((float) $voucher->minimum_order_amount, 2) . '.',
                ]);
            }

            if ($voucher->max_uses !== null && (int) $voucher->voucherUsed >= (int) $voucher->max_uses) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'Selected voucher has reached its usage limit.',
                ]);
            }

            if ($voucher->voucherType === 'percentage') {
                $discount = $subtotal * ((float) $voucher->voucherAmount / 100);
            } else {
                $discount = min($subtotal, (float) $voucher->voucherAmount);
            }
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount),
            'voucher' => $voucher,
        ];
    }

    protected function placeOrder($customer, Cart $cart, ?int $addressId, int $paymentMethodId, ?int $voucherId): Order
    {
        $pricing = $this->computeDiscountedTotal($cart, $voucherId);

        if ($voucherId && $pricing['voucher'] && $pricing['voucher']->per_customer_limit !== null) {
            $perCustomerUsage = Order::query()
                ->where('cus_id', $customer->cus_id)
                ->where('voucher_id', $voucherId)
                ->count();

            if ($perCustomerUsage >= (int) $pricing['voucher']->per_customer_limit) {
                throw ValidationException::withMessages([
                    'voucher_id' => 'You have already reached your usage limit for this voucher.',
                ]);
            }
        }

        return DB::transaction(function () use ($customer, $cart, $addressId, $paymentMethodId, $voucherId, $pricing) {
            if ($voucherId && $pricing['voucher']) {
                $pricing['voucher']->increment('voucherUsed');
            }

            $order = Order::create([
                'order_status'     => 'Pending',
                'order_date'       => now()->toDateString(),
                'total_price'      => $pricing['total'],
                'voucher_id'       => $voucherId,
                'add_id'           => $addressId,
                'paymentMethod_id' => $paymentMethodId,
                'cus_id'           => $customer->cus_id,
                'cart_id'          => $cart->cart_id,
            ]);

            foreach ($cart->items as $item) {
                Sale::create([
                    'order_id'    => $order->order_id,
                    'product_id'  => $item->product_ID,
                    'quantity'    => $item->quantity,
                    'total_price' => $item->subtotal,
                ]);

                StockOut::create([
                    'stockOut_date' => now()->toDateString(),
                    'productOut'    => $item->product_ID,
                ]);

                $item->product->decrement('Stock', $item->quantity);
            }

            $cart->update(['status' => 'checked_out']);

            $customer->notify(new OrderStatusNotification(
                $order,
                'Order Confirmation',
                'Your order has been placed successfully and is now pending processing.'
            ));

            return $order;
        });
    }

    public function address(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $cart = $this->getActiveCartForCustomer($customer);

        $selectedVoucherId = $request->filled('voucher_id')
            ? (int) $request->voucher_id
            : session('checkout.voucher_id');

        if ($selectedVoucherId) {
            session(['checkout.voucher_id' => $selectedVoucherId]);
        }

        try {
            $pricing = $this->computeDiscountedTotal($cart, $selectedVoucherId ? (int) $selectedVoucherId : null);
        } catch (ValidationException $exception) {
            return redirect()->route('cart.index')
                ->withErrors($exception->errors())
                ->withInput(['voucher_id' => $selectedVoucherId]);
        } catch (Throwable $exception) {
            Log::error('Unable to load checkout address step.', [
                'customer_id' => $customer->cus_id,
                'voucher_id' => $selectedVoucherId,
                'error' => $exception->getMessage(),
            ]);

            return redirect()->route('cart.index')->with('error', 'We could not load that voucher right now. Please try again.');
        }

        return view('checkout.address', [
            'cart' => $cart,
            'address' => $customer->address,
            'vouchers' => Voucher::query()->availableForCheckout()->orderBy('voucherName')->get(),
            'selectedVoucherId' => $selectedVoucherId,
            'subtotal' => $pricing['subtotal'],
            'discount' => $pricing['discount'],
            'total' => $pricing['total'],
        ]);
    }

    public function saveAddressStep(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_postal_code' => 'nullable|string|regex:/^[0-9]+$/|max:10',
            'voucher_id' => 'nullable|exists:vouchers,voucher_id',
        ]);

        $addressData = [
            'country' => $validated['country'] ?? null,
            'province' => $validated['province'] ?? null,
            'city' => $validated['city'] ?? null,
            'barangay' => $validated['barangay'] ?? null,
            'zip_postal_code' => $validated['zip_postal_code'] ?? null,
        ];

        if (array_filter($addressData)) {
            if ($customer->address) {
                $customer->address->update($addressData);
            } else {
                $customer->address()->create($addressData);
            }
        }

        $address = $customer->fresh()->address;
        if (!$address) {
            return back()->withErrors(['address' => 'Please provide a delivery address before continuing.'])->withInput();
        }

        session([
            'checkout.add_id' => $address->add_id,
            'checkout.voucher_id' => $validated['voucher_id'] ?? null,
        ]);

        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        $customer = Auth::guard('customer')->user();
        $cart = $this->getActiveCartForCustomer($customer);

        $address = $customer->address;
        if (!$address) {
            return redirect()->route('checkout.address')->withErrors([
                'address' => 'Please provide a delivery address first.',
            ]);
        }

        $selectedVoucherId = session('checkout.voucher_id');
        try {
            $pricing = $this->computeDiscountedTotal($cart, $selectedVoucherId ? (int) $selectedVoucherId : null);
        } catch (ValidationException $exception) {
            return redirect()->route('cart.index')
                ->withErrors($exception->errors())
                ->withInput(['voucher_id' => $selectedVoucherId]);
        } catch (Throwable $exception) {
            Log::error('Unable to load checkout payment step.', [
                'customer_id' => $customer->cus_id,
                'voucher_id' => $selectedVoucherId,
                'error' => $exception->getMessage(),
            ]);

            return redirect()->route('cart.index')->with('error', 'We could not load that voucher right now. Please try again.');
        }

        return view('checkout.payment', [
            'cart' => $cart,
            'address' => $address,
            'paymentMethods' => PaymentMethod::all(),
            'selectedVoucherId' => $selectedVoucherId,
            'selectedPaymentMethodId' => session('checkout.paymentMethod_id'),
            'subtotal' => $pricing['subtotal'],
            'discount' => $pricing['discount'],
            'total' => $pricing['total'],
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $validated = $request->validate([
            'paymentMethod_id' => 'required|exists:payment_methods,paymentMethod_id',
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = $this->getActiveCartForCustomer($customer);

        $addressId = session('checkout.add_id') ?: optional($customer->address)->add_id;
        if (!$addressId) {
            return redirect()->route('checkout.address')->withErrors([
                'address' => 'Please provide a delivery address first.',
            ]);
        }

        $voucherId = session('checkout.voucher_id');

        session(['checkout.paymentMethod_id' => (int) $validated['paymentMethod_id']]);

        try {
            $order = $this->placeOrder(
                $customer,
                $cart,
                (int) $addressId,
                (int) $validated['paymentMethod_id'],
                $voucherId ? (int) $voucherId : null
            );
        } catch (ValidationException $exception) {
            return redirect()->route('cart.index')
                ->withErrors($exception->errors())
                ->withInput(['voucher_id' => $voucherId, 'paymentMethod_id' => $validated['paymentMethod_id']]);
        } catch (Throwable $exception) {
            Log::error('Checkout payment failed.', [
                'customer_id' => $customer->cus_id,
                'voucher_id' => $voucherId,
                'payment_method_id' => $validated['paymentMethod_id'],
                'error' => $exception->getMessage(),
            ]);

            return redirect()->route('checkout.payment')->with('error', 'Something went wrong while placing your order. Please try again.');
        }

        session()->forget('checkout');

        return redirect()->route('checkout.receipt', $order);
    }

    public function receipt(Order $order)
    {
        $customer = Auth::guard('customer')->user();
        abort_if($order->cus_id !== $customer->cus_id, 403);

        $order->load(['cart.items.product', 'paymentMethod', 'address', 'voucher']);

        return view('checkout.receipt', compact('order'));
    }

    public function store(PlaceOrderRequest $request)
    {
        $customer = Auth::guard('customer')->user();
        $cart = $this->getActiveCartForCustomer($customer);

        $this->placeOrder(
            $customer,
            $cart,
            $request->add_id ? (int) $request->add_id : null,
            (int) $request->paymentMethod_id,
            $request->voucher_id ? (int) $request->voucher_id : null
        );

        return redirect()->route('account.orders')->with('success', 'Order placed successfully!');
    }
}