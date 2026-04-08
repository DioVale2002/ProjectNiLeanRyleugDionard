<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'paymentMethod_id' => 'required|exists:payment_methods,paymentMethod_id',
            'add_id'           => 'nullable|exists:addresses,add_id',
            'voucher_id'       => 'nullable|exists:vouchers,voucher_id',
        ];
    }
}