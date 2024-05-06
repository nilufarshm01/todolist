<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_title' => ['required', 'string', 'max:255'],
            'task_desc' => ['nullable', 'string'],
            'task_stat' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ];
    }
    public function messages(): array
    {
        return [
            'task_stat.in' => 'The status field must be one of: Done, Trying, Not Done, Forgotten, No Need, Time Outed.',
        ];

    }
}
