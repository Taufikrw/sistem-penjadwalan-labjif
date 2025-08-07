<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBiodataRequest extends FormRequest
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
            'prodi' => 'required|string|in:Informatika,Sistem Informasi',
            'angkatan' => 'required|integer|min:2020|max:' . date('Y'),
            'nomor_telp' => ['nullable', 'string', 'max:15', 'regex:/^(\+62|0)[0-9]{8,14}$/'],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'prodi' => 'Program Studi',
            'angkatan' => 'Angkatan',
            'nomor_telp' => 'Nomor Telepon',
            'foto' => 'Foto',
        ];
    }
}
