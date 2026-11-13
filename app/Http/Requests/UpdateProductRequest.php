<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0|max:999999.99|decimal:0,2',
            'selling_price' => 'required|numeric|min:0|max:999999.99|decimal:0,2',
            'stock_quantity' => 'required|integer|min:0|max:2147483647',
            'shelf_location' => 'nullable|string|max:50',
        ];
    }
}
