<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        
        // Get active orders (Pending, Processing)
        $orders = Order::where('cus_id', $customer->cus_id)
            ->active()
            ->with(['cart.items.product', 'paymentMethod', 'address'])
            ->orderBy('order_date', 'desc')
            ->get();

        $notifications = $customer->notifications()->latest()->limit(5)->get();
        $customer->unreadNotifications->markAsRead();
        
        return view('account.orders', compact('orders', 'notifications'));
    }

    public function archived()
    {
        $customer = Auth::guard('customer')->user();
        $this->applyTimeoutFailuresForCustomer($customer->cus_id);
        
        // Get archived orders (Completed, Cancelled, Failed)
        $orders = Order::where('cus_id', $customer->cus_id)
            ->archived()
            ->with(['cart.items.product', 'paymentMethod', 'address'])
            ->orderBy('order_date', 'desc')
            ->get();

        $notifications = $customer->notifications()->latest()->limit(5)->get();
        
        return view('account.archived', compact('orders', 'notifications'));
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
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ], [
            'contact_num.regex' => 'Contact number must contain only numbers and valid phone characters (+, -, spaces, parentheses).',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'current_password.required_with' => 'Current password is required when setting a new password.',
        ]);

        // Update basic information
        $customer->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'contact_num' => $validated['contact_num'],
            'email' => $validated['email'],
        ]);

        // Handle password change
        if (!empty($validated['new_password'])) {
            // Verify current password
            if (!Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $customer->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            return redirect()->route('account.security')->with('success', 'Account information and password updated successfully!');
        }

        return redirect()->route('account.security')->with('success', 'Account information updated successfully!');
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

        $order->update(['order_status' => 'Cancelled']);

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

        // Validate password confirmation
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Please try again.']);
        }

        // Delete the customer (address will be deleted automatically due to cascade)
        $customer->delete();

        // Logout
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }
}
