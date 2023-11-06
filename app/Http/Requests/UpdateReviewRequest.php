<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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

        if ($method == 'PUT') {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'rating' => ['required', 'numeric', 'min:0', 'max:5'],
                'comment' => ['required', 'string', 'max:255']
            ];
        } else {
            return [
                'user_id' => ['sometimes', 'required', 'exists:users,id'],
                'rating' => ['sometimes', 'required', 'numeric', 'min:0', 'max:5'],
                'comment' => ['sometimes', 'required', 'string', 'max:255']
            ];
        }
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->userId) {
            $this->merge([
                'user_id' => $this->userId,
            ]);
        }
    }
}
