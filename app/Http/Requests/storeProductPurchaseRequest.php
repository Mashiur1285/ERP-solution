<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeProductPurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'variants' => 'required|array|min:1',
            'variants.*.variant' => 'required|string|max:255',
            'variants.*.quantity' => 'required|integer|min:1',
            'variants.*.bottles_per_box' => 'required|integer|min:1',
            'variants.*.free_bottles' => 'nullable|integer|min:0',
            'variants.*.unit_price' => 'required|numeric|min:0',
        ];
    }
}
