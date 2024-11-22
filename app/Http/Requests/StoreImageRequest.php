<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'images' => ['required', 'array', 'min:1'], // Ensure it's an array
            'images.*.base64' => 'required|string', // Validate Base64 strings
        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'At least one image is required.',
            'images.*.base64.required' => 'Each image must be provided as a Base64 string.',
        ];
    }
}
