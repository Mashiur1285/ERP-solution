<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesRequest extends FormRequest
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
            'items' => 'array',
            'items.*.product_id' => 'integer|exists:products,id',
            'items.*.supplier_id' => 'integer|exists:suppliers,id',
            'items.*.variant' => 'string|in:1000,250,100',
            'items.*.boxes_sold' => 'integer|min:0',
            'items.*.bottles_per_box' => 'integer|min:1',
            'items.*.available_bottles' => 'integer|min:0',
            'items.*.available_boxes' => 'integer|min:0',
            'items.*.total_quantity' => 'integer|min:0',
            'items.*.free_bottles' => 'integer|min:0',
            'items.*.unit_price' => 'numeric|min:0',
            'items.*.new_unit_price' => 'numeric|min:0',
            'items.*.total_price' => 'numeric|min:0',
            'items.*.profit' => 'numeric',
            'items.*.availableVariants' => 'required|array',
            'items.*.availableVariants.*' => 'string|in:1000,250,100',
        ];
    }
}
