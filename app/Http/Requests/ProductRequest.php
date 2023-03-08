<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image',
            'category_id' => 'required|integer|exists:categories,id',
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
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than 0',
            'stock.required' => 'Stock is required',
            'stock.integer' => 'Stock must be an integer',
            'stock.min' => 'Stock must be greater than 0',
            'image.required' => 'Image is required',
            'image.image' => 'Image must be an image',
            'category_id.required' => 'Category is required',
            'category_id.integer' => 'Category must be an integer',
            'category_id.exists' => 'Category does not exist',
        ];
    }
}
