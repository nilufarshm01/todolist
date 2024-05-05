<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'TaskName' => ['required', 'string', 'max:255'],
            'TaskDesc' => ['nullable', 'string'],
            'TaskStatus' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'],
        ];
    }

}
