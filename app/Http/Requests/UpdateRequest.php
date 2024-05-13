<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:complete,incomplete'],
        ];
    }
    public function messages(): array
    {
        return [
            'task_status.in' => __('validation.task_status_in'),
        ];
    }
}
