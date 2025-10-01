<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        $address = $customer->address;
        
        return view('account.edit', compact('customer', 'address'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->cus_id . ',cus_id',
        ]);

        $customer->update($validated);

        // Handle address
        $addressData = $request->validate([
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'zip_postal_code' => 'nullable|string|max:255',
        ]);

        // Only update/create address if at least one field is filled
        if (array_filter($addressData)) {
            if ($customer->address) {
                $customer->address->update($addressData);
            } else {
                Address::create(array_merge($addressData, ['cus_id' => $customer->cus_id]));
            }
        }

        return redirect()->route('account.edit')->with('success', 'Account updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.edit')->with('success', 'Password updated successfully!');
    }
}