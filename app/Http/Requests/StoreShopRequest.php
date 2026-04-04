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
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|max:255',
            'road' => 'nullable|string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'shop_address' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20|unique:shops,phone_number',
            'email' => 'nullable|email|max:255|unique:shops,email',
            'website' => 'nullable|url|max:255',
            'national_id' => 'nullable|string|max:50',
            'trade_license' => 'nullable|string|max:50',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}
