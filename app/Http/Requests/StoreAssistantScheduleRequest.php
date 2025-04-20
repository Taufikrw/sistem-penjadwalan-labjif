<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssistantScheduleRequest extends FormRequest
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
        return [
            'assistants.0.nim' => 'required|exists:assistants,nim',
            'assistants.1.nim' => [
                'required',
                'exists:assistants,nim',
                function ($attribute, $value, $fail) {
                    if ($value === request('assistants')[0]['nim']) {
                        $fail('The NIM in assistant 2 must not be the same as assistant 1.');
                    }
                },
            ],
        ];
    }
}
