<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'task_title' => ['required', 'string', 'max:255'],
            'task_description' => ['nullable', 'string'],
            'task_status' => ['required', 'in:complete,incomplete'],
        ];
    }
    public function messages(): array
    {
        return [
            'task_status.in' => __('validation.task_status_in'),
        ];
    }
}
