<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_ID' => 'required|exists:products,product_ID',
            'quantity'   => 'required|integer|min:1',
        ];
    }
}