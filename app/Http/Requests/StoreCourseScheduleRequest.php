<?php

namespace App\Http\Requests;

use App\Enums\Day;
use App\Models\CourseSchedule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseScheduleRequest extends FormRequest
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
            'course' => 'required|string|max:50',
            'day' => ['required', 'string', Rule::enum(Day::class)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'owner' => 'required|string|exists:assistants,nim',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Kelas',
            'course' => 'Mata Kuliah',
            'day' => 'Hari',
            'start_time' => 'Waktu Mulai',
            'end_time' => 'Waktu Selesai',
            'owner' => 'Asisten',
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
                // Jangan jalankan pengecekan ini jika validasi dasar sudah gagal
                if ($validator->errors()->hasAny(['day', 'start_time', 'end_time'])) {
                    return;
                }

                // Ambil data yang sudah divalidasi dari $this
                $data = $this->validated();

                $day = $data['day'];
                $startTime = $data['start_time'];
                $endTime = $data['end_time'];
                $owner = $data['owner'];

                // Query untuk mengecek apakah ada jadwal yang tumpang tindih (overlap)
                $query = CourseSchedule::where('owner', $owner) // <-- TAMBAHKAN BARIS INI
                    ->where('day', $day)
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);

                if ($scheduleBeingUpdated = $this->route('id')) {
                    // Tambahkan kondisi untuk MENGECUALIKAN ID jadwal yang sedang diedit
                    $query->where('id', '!=', $scheduleBeingUpdated);
                }

                $isConflict = $query->exists();

                // Jika ditemukan bentrok, tambahkan pesan error ke validator
                if ($isConflict) {
                    $validator->errors()->add(
                        'start_time',
                        'Jadwal sudah ada'
                    );
                }
            }
        ];
    }
}
