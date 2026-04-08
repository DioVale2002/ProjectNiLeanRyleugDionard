<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'productIn'   => 'required|exists:products,product_ID',
            'stockIn_date' => 'required|date',
            'quantity'    => 'required|integer|min:1',
        ];
    }
}