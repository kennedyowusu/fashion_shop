<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
* Form request for creating a new resource.
*/
class CategoryRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Check whether the user is authorized to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // Validate that the name field is required, a string, and no longer than 255 characters
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
