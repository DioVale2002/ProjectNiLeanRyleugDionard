<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function orders()
    {
        return view('account.orders');
    }

    public function archived()
    {
        return view('account.archived');
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
            'zip_postal_code' => 'nullable|string|max:255',
        ]);

        if (array_filter($addressData)) {
            if ($customer->address) {
                $customer->address->update($addressData);
            } else {
                Address::create(array_merge($addressData, ['cus_id' => $customer->cus_id]));
            }
        }

        return redirect()->route('account.addresses')->with('success', 'Address updated successfully!');
    }

    public function updateInfo(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->cus_id . ',cus_id',
            'new_password' => 'nullable|string|min:8',
        ]);

        $customer->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'contact_num' => $validated['contact_num'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['new_password'])) {
            $customer->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }

        return redirect()->route('account.security')->with('success', 'Account information updated successfully!');
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