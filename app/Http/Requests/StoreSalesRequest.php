<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSalesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_id' => 'required|integer|exists:shops,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.supplier_id' => 'required|integer|exists:suppliers,id',
            'items.*.variant' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $productId = $this->input("items.$index.product_id");
                    $product = Product::find($productId);

                    if (!$product) {
                        return $fail("The product for {$attribute} does not exist.");
                    }

                    $variants = collect($product->metadata['variants'] ?? [])->pluck('variant')->toArray();
                    if (!in_array($value, $variants)) {
                        $fail("The selected {$attribute} is invalid. Available variants: " . implode(', ', $variants));
                    }
                },
            ],
            'items.*.boxes_sold' => 'required|integer|min:0',
            'items.*.bottles_per_box' => 'required|integer|min:0',
            'items.*.total_quantity' => 'required|integer|min:0',
            'items.*.free_bottles' => 'required|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.new_unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.profit' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.variant.required' => 'The variant field is required.',
            'items.*.product_id.exists' => 'The selected product does not exist.',
            'items.*.supplier_id.exists' => 'The selected supplier does not exist.',
        ];
    }
}

