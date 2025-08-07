<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function authenticate()
    {
        $credentials = $this->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'validation' => 'Password dan username tidak sesuai.',
            ]);
        }

        return Auth::user();
    }

    public function attributes()
    {
        return [
            'username' => 'Nama Pengguna',
            'password' => 'Kata Sandi',
        ];
    }
}
