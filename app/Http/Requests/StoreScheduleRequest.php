<?php

namespace App\Http\Requests;

use App\Enums\Day;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\Validator;
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
            'name' => 'required|string|size:1',
            'kode_praktikum' => 'required|string|max:255|exists:practicums,kode_praktikum',
            'laboratorium_id' => 'required|uuid|exists:laboratoriums,id',
            'dosen' => 'required|string|max:255',
            'tahun_ajar' => 'required|integer|digits:4',
            'jenis_semester' => 'required|string|in:ganjil,genap',
            'day' => ['required', 'string', Rule::enum(Day::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_praktikum' => 'Nama Praktikum',
            'laboratorium_id' => 'Laboratorium',
            'dosen' => 'Dosen',
            'tahun_ajar' => 'Tahun Ajar',
            'jenis_semester' => 'Jenis Semester',
            'day' => 'Hari',
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
        ];
    }

    public function messages()
    {
        return [
            'end_time.after' => 'Waktu Selesai harus setelah Waktu Mulai.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $this->validated();

                $scheduleId = $this->route('id') ? $this->route('id') : null;
                
                $scheduleConflict = Schedule::where('laboratorium_id', $data['laboratorium_id'])
                    ->where('day', $data['day'])
                    ->where('tahun_ajar', $data['tahun_ajar'])
                    ->where('jenis_semester', $data['jenis_semester'])
                    ->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time'])
                    ->when($scheduleId, function ($query) use ($scheduleId) {
                        return $query->where('id', '!=', $scheduleId);
                    })
                    ->exists();

                if ($scheduleConflict) {
                    // Menambahkan error ke validator
                    $validator->errors()->add(
                        'start_time', // Field yang akan menampilkan pesan error
                        'Jadwal praktikum di laboratorium dan jam yang sama sudah ada.'
                    );
                    return; // Hentikan pengecekan jika sudah ditemukan error
                }

                // 2. Pengecekan Kelas Praktikum yang Sama (kode_praktikum + nama kelas)
                $classExists = Schedule::where('kode_praktikum', $data['kode_praktikum'])
                    ->where('name', $data['name'])
                    ->where('tahun_ajar', $data['tahun_ajar'])
                    ->where('jenis_semester', $data['jenis_semester'])
                    ->when($scheduleId, function ($query) use ($scheduleId) {
                        return $query->where('id', '!=', $scheduleId);
                    })
                    ->exists();

                if ($classExists) {
                    $validator->errors()->add(
                        'name', // Field yang akan menampilkan pesan error
                        'Kelas untuk praktikum ini sudah terdaftar.'
                    );
                }
            }
        ];
    }
}
