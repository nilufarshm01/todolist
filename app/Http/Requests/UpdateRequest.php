<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ];
    }
    public function messages(): array
    {
        return [
            'status.in' => 'The status field must be one of: Done, Trying, Not Done, Forgotten, No Need, Time Outed.',
        ];

    }
}
