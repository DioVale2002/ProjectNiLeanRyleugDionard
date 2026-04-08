<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'Title'     => 'required|string|max:255',
            'Author'    => 'required|string|max:255',
            'Price'     => 'required|numeric|min:0',
            'Stock'     => 'required|integer|min:0',
            'ISBN'      => 'required|string|unique:products,ISBN',
            'Publisher' => 'required|string|max:255',
            'Genre'     => 'required|string|max:255',
            'Rating'    => 'nullable|numeric|min:0|max:5',
            'Review'    => 'nullable|string',
            'Age_Group' => 'nullable|string|max:255',
            'Length'    => 'nullable|integer|min:0',
            'Width'     => 'nullable|integer|min:0',
        ];
    }
}