<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssistantRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'nim' => ['required', 'string', 'max:9', 'regex:/^(123|124)\d{6}$/' ],
            'prodi' => 'required|string|in:Informatika,Sistem Informasi',
            'angkatan' => 'required|integer|min:2020|max:' . date('Y'),
            'tahun_masuk' => 'required|integer|min:2020|max:' . date('Y'),
            'status' => 'required|string|in:aktif,non-aktif,selesai',
        ];
    }
}
