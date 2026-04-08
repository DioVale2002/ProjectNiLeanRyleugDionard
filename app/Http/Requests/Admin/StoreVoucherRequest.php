<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'voucherName'   => 'required|string|max:255',
            'voucherType'   => 'required|in:percentage,flat',
            'voucherAmount' => 'required|numeric|min:0',
        ];
    }
}