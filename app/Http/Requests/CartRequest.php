<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            // 'image' => 'sometimes|image|max:2048|mimes:jpeg,png,gif',
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|integer|exists:products,id',
            'total_price' => 'sometimes|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must be less than 255 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than 0',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be an integer',
            'quantity.min' => 'Quantity must be greater than 0',
            'image.required' => 'Image is required',
            'image.image' => 'Image must be an image',
            'image.max' => 'Image must be less than 2MB',
            'image.mimes' => 'Image must be a file of type: jpeg, png, gif',
            'user_id.required' => 'User is required',
            'user_id.integer' => 'User must be an integer',
            'user_id.exists' => 'User does not exist',
            'product_id.required' => 'Product is required',
            'product_id.integer' => 'Product must be an integer',
            'product_id.exists' => 'Product does not exist',
        ];
    }
}
