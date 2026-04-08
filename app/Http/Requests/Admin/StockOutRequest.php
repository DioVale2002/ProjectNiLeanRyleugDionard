<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StockOutRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'productOut'    => 'required|exists:products,product_ID',
            'stockOut_date' => 'required|date',
            'quantity'      => 'required|integer|min:1',
        ];
    }
}