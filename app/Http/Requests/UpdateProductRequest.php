<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
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
        $method = $this->method();

        if ($method === 'PUT') {
            return [
                'name' => ['required'],
                'price' => ['required'],
                'description' => ['required', 'string'],
                'images' => ['required'],
                'category_id' => ['required', 'exists:categories,id'],
            ];
        } else {
            return [
                'name' => ['sometimes', 'required'],
                'price' => ['sometimes', 'required'],
                'description' => ['sometimes', 'required', 'string'],
                'images' => ['sometimes', 'required'],
                'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            ];
        }

    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if (isset($this->categoryId)) {
            $this->merge([
                'category_id' => $this->categoryId
            ]);
        }
        if (isset($this->name)) {
            $this->merge([
                'slug' => Str::slug($this->name)
            ]);
        }
    }
}
