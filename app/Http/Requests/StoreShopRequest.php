<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Blank optional fields arrive as "" from the quick-create modal. Store them
     * as NULL so the unique indexes on phone_number/email don't collide across
     * shops that simply left the field empty.
     */
    protected function prepareForValidation(): void
    {
        $nullable = ['road', 'owner_name', 'shop_address', 'phone_number', 'email', 'website', 'national_id', 'trade_license', 'tax_id', 'notes'];

        foreach ($nullable as $field) {
            if ($this->has($field) && is_string($this->input($field)) && trim($this->input($field)) === '') {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:30',
            'road' => 'nullable|string|max:100',
            'owner_name' => 'nullable|string|max:30',
            'shop_address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20|unique:shops,phone_number',
            'email' => 'nullable|email|max:255|unique:shops,email',
            'website' => 'nullable|url|max:255',
            'national_id' => 'nullable|string|max:50',
            'trade_license' => 'nullable|string|max:50',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}
