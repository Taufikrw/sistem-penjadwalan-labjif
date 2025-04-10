<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePracticumRequest extends FormRequest
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
            'kode_praktikum' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'for_prodi' => 'required|string|in:Informatika,Sistem Informasi',
            'is_odd' => 'required|boolean',
        ];
    }
}
