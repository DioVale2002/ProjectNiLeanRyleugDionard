<?php

namespace App\Http\Controllers;

use App\Mail\CustomerOtpMail;
use App\Models\Address;
use App\Models\CustomerActionOtp;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    private const TIMEOUT_DAYS = 14;

    private function applyTimeoutFailuresForCustomer(int $customerId): void
    {
        Order::query()
            ->where('cus_id', $customerId)
            ->where('order_status', 'Processing')
            ->whereDate('order_date', '<=', now()->subDays(self::TIMEOUT_DAYS)->toDateString())
            ->update(['order_status' => 'Failed']);
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $this->applyTimeoutFailuresForCustomer($customer->cus_id);
        
        // REFACTORED: Changed ->get() to ->paginate(5)
        $orders = Order::where('cus_id', $customer->cus_id)
            ->active()
            ->with(['cart.items.product', 'paymentMethod', 'address'])
            ->orderByDesc('created_at')
            ->paginate(5); // Shows 5 orders per page
        
        return view('account.orders', compact('orders'));
    }

    public function archived()
    {
        $customer = Auth::guard('customer')->user();
        $this->applyTimeoutFailuresForCustomer($customer->cus_id);
        
        // REFACTORED: Changed ->get() to ->paginate(5)
        $orders = Order::where('cus_id', $customer->cus_id)
            ->archived()
            ->with(['cart.items.product', 'paymentMethod', 'address'])
            ->orderByDesc('created_at')
            ->paginate(5); // Shows 5 orders per page
        
        return view('account.archived', compact('orders'));
    }

    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        $address = $customer->address;
        
        return view('account.addresses', compact('address'));
    }

    public function security()
    {
        $customer = Auth::guard('customer')->user();
        
        return view('account.security', compact('customer'));
    }

    public function updateAddress(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $addressData = $request->validate([
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_postal_code' => 'nullable|string|regex:/^[0-9]+$/|max:10',
        ]);

        // Only update if at least one field has a value
        if (array_filter($addressData)) {
            if ($customer->address) {
                $customer->address->update($addressData);
            } else {
                Address::create(array_merge($addressData, ['cus_id' => $customer->cus_id]));
            }
            return redirect()->route('account.addresses')->with('success', 'Address updated successfully!');
        }

        return redirect()->route('account.addresses')->with('success', 'No changes made.');
    }

    public function updateInfo(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|regex:/^[0-9+\-\s()]+$/|max:20',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->cus_id . ',cus_id',
        ], [
            'contact_num.regex' => 'Contact number must contain only numbers and valid phone characters (+, -, spaces, parentheses).',
        ]);

        // Update basic information
        $customer->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'contact_num' => $validated['contact_num'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('account.security')->with('success', 'Account information updated successfully!');
    }

    public function requestDeleteOtp(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $hasOngoingTransactions = Order::query()
            ->where('cus_id', $customer->cus_id)
            ->active()
            ->exists();

        if ($hasOngoingTransactions) {
            return back()->withErrors([
                'otp_code' => 'Account deletion is not allowed while you still have ongoing transactions.',
            ]);
        }

        $email = Str::lower(trim($customer->email));
        // Rate limiting disabled for local development
        // $minuteKey = 'delete-otp-minute:' . $email;
        // $hourKey = 'delete-otp-hour:' . $email;
        // if (RateLimiter::tooManyAttempts($minuteKey, 1) || RateLimiter::tooManyAttempts($hourKey, 5)) {
        //     return back()->withErrors([
        //         'otp_code' => 'Too many OTP requests. Please try again later.',
        //     ]);
        // }
        // RateLimiter::hit($minuteKey, 60);
        // RateLimiter::hit($hourKey, 3600);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        CustomerActionOtp::query()
            ->where('email', $email)
            ->where('action', 'delete')
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        CustomerActionOtp::create([
            'email' => $email,
            'action' => 'delete',
            'code_hash' => hash('sha256', $code),
            'expires_at' => $expiresAt,
        ]);

        Mail::to($customer->email)->send(new CustomerOtpMail(
            'Confirm account deletion',
            'Use this code to delete your account:',
            $code,
            5
        ));

        return back()->with('success', 'We sent a deletion code to your email.');
    }

    public function markReceived(Order $order)
    {
        $customer = Auth::guard('customer')->user();

        abort_if($order->cus_id !== $customer->cus_id, 403);

        $this->applyTimeoutFailuresForCustomer($customer->cus_id);
        $order->refresh();

        if (!in_array($order->order_status, ['Shipped', 'Delivered', 'Processing'], true)) {
            return redirect()->route('account.orders')->with('error', 'This order cannot be marked as received yet.');
        }

        $order->update(['order_status' => 'Completed']);
        $customer->notify(new OrderStatusNotification(
            $order,
            'Order Completed',
            'Thanks for confirming delivery. Your order is now marked as completed.'
        ));

        return redirect()->route('account.archived')->with('success', 'Order marked as received. Thank you!');
    }

    public function cancelOrder(Order $order)
    {
        $customer = Auth::guard('customer')->user();

        abort_if($order->cus_id !== $customer->cus_id, 403);

        if (!in_array($order->order_status, ['Pending', 'Processing'], true)) {
            return redirect()->route('account.orders')->with('error', 'This order can no longer be cancelled.');
        }

        $validated = request()->validate([
            'cancellation_note' => 'nullable|string|max:500',
        ]);

        $order->update([
            'order_status' => 'Cancelled',
            'cancellation_note' => $validated['cancellation_note'] ?? 'Cancelled by customer.',
        ]);

        $customer->notify(new OrderStatusNotification(
            $order,
            'Order Cancelled',
            'Your order was successfully cancelled.'
        ));

        return redirect()->route('account.archived')->with('success', 'Order cancelled successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'otp_code' => 'required|string',
        ]);

        $email = Str::lower(trim($customer->email));
        $otp = CustomerActionOtp::query()
            ->where('email', $email)
            ->where('action', 'delete')
            ->whereNull('consumed_at')
            ->where('expires_at', '>=', now())
            ->orderByDesc('created_at')
            ->first();

        if (!$otp || !hash_equals($otp->code_hash, hash('sha256', trim($request->otp_code)))) {
            return back()->withErrors(['otp_code' => 'Invalid or expired code.']);
        }

        $hasOngoingTransactions = Order::query()
            ->where('cus_id', $customer->cus_id)
            ->active()
            ->exists();

        if ($hasOngoingTransactions) {
            return back()->withErrors([
                'otp_code' => 'Account deletion is not allowed while you still have ongoing transactions.',
            ]);
        }

        $otp->update(['consumed_at' => now()]);

        // Delete the customer (address will be deleted automatically due to cascade)
        $customer->delete();

        // Logout
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }
}
