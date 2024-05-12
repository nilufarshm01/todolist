<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } // todo : please have a tendency to remove unused methods.

    public function rules(): array
    {
        return [
            'task_title' => ['required', 'string', 'max:255'],
            'task_desc' => ['nullable', 'string'], //todo : specify possible types in your variables.
            'task_stat' => ['required', 'in:Done,Trying,Not Done,Forgotten,No Need,Time Outed'], // todo : If you review the structure of Readme file, you would definitely see that in our business logic there is just two available statuses.
        ];  //todo : specify better names for your request parameters.
    }

    public function messages(): array
    {
        return [
            'task_stat.in' => 'The status field must be one of: Done, Trying, Not Done, Forgotten, No Need, Time Outed.',
        ];
        // todo : it is much sensible to write your messages to the user through localization files provided in laravel: https://laravel.com/docs/11.x/localization
    }
}
