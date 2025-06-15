<?php

namespace App\Http\Requests;

use App\Enums\Day;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'kode_praktikum' => 'required|string|max:255|exists:practicums,kode_praktikum',
            'laboratorium_id' => 'required|uuid|exists:laboratoriums,id',
            'dosen' => 'required|string|max:255',
            'tahun_ajar' => 'required|integer|digits:4',
            'day' => ['required', 'string', Rule::enum(Day::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }
}
