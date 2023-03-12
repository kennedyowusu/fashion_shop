<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'total_price' => 'required|numeric|min:0',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
