<?php

namespace App\Http\Requests;

use App\Enums\Day;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreScheduleListRequest extends FormRequest
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
            'name' => 'required|string|size:1',
            'kode_praktikum' => 'required|string|max:255|exists:practicums,kode_praktikum',
            'laboratorium_id' => 'required|uuid|exists:laboratoriums,id',
            'lecturer_id' => 'required|uuid|exists:lecturers,id',
            'tahun_ajar1' => 'required|digits:4|numeric',
            'tahun_ajar2' => 'required|digits:4|numeric',
            'jenis_semester' => 'required|string|in:ganjil,genap',
            'day' => ['required', 'string', Rule::enum(Day::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $this->validated();

                $tahunAjar1 = $data['tahun_ajar1'];
                $tahunAjar2 = $data['tahun_ajar2'];

                if ($tahunAjar2 != ($tahunAjar1 + 1)) {
                    $validator->errors()->add(
                        'tahun_ajar2',
                        'Tahun ajar kedua harus satu tahun setelah tahun ajar pertama.'
                    );
                }
            }
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Kelas',
            'kode_praktikum' => 'Nama Praktikum',
            'laboratorium_id' => 'Laboratorium',
            'lecturer_id' => 'Dosen',
            'tahun_ajar1' => 'Tahun Ajar',
            'tahun_ajar2' => 'Tahun Ajar',
            'jenis_semester' => 'Jenis Semester',
            'day' => 'Hari',
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
        ];
    }
}
