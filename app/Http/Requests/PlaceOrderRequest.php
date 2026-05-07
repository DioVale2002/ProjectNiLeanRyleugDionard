<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $customer = $this->user('customer');

        return [
            'paymentMethod_id' => 'required|exists:payment_methods,paymentMethod_id',
            'add_id'           => [
                'nullable',
                Rule::exists('addresses', 'add_id')
                    ->where(fn ($query) => $query->where('cus_id', $customer?->cus_id)),
            ],
            'voucher_id'       => 'nullable|exists:vouchers,voucher_id',
        ];
    }
}