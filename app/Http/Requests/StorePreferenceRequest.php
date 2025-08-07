<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePreferenceRequest extends FormRequest
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
            'practicums' => 'required|array',
            'practicums.*' => 'exists:practicums,kode_praktikum',
        ];
    }

    public function attributes(): array
    {
        return [
            'practicums' => 'Preferensi',
            'practicums.*' => 'Praktikum',
        ];
    }
}
