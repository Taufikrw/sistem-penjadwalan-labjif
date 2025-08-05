<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AssistantRepository implements AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'updated_at', $order = 'desc', $search = '', array $filters = [], $perPage = null)
    {
        $allowedSortColumns = ['nim', 'name', 'prodi', 'angkatan', 'tahun_masuk', 'updated_at'];
        $sortBy = in_array(strtolower($sortBy), $allowedSortColumns) ? strtolower($sortBy) : 'updated_at';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';

        $query = Assistant::with('user')->orderBy($sortBy, $order);

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(nim AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(prodi) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(angkatan AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(tahun_masuk AS TEXT)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ($filters as $key => $value) {
            if (in_array($key, ['status', 'prodi', 'angkatan', 'tahun_masuk'])) {
                $lowerValue = strtolower($value);
                $query->whereRaw("LOWER({$key}) LIKE ?", ["%{$lowerValue}%"]);
            }
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getAssistantByNim($nim)
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')->where('nim', $nim)->first();
    }

    public function getAssistantByNimIncludeTrashedWithUser($nim)
    {
        return Assistant::with('user')->withTrashed()->where('nim', $nim)->first();
    }

    public function getUserByNimIncludeTrashed($nim)
    {
        return User::withTrashed()->where('username', $nim)->first();
    }

    public function storeAssistant(array $data)
    {
        $existing = Assistant::where('nim', $data['nim'])->first();
        if ($existing) {
            throw ValidationException::withMessages([
                'nim' => 'Assistant with this NIM already exists.',
            ]);
        }

        $user = User::create([
            'username' => $data['nim'],
            'password' => bcrypt($data['password']),
        ]);

        $assistant = Assistant::create([
            'name' => $data['name'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'angkatan' => $data['angkatan'],
            'tahun_masuk' => $data['tahun_masuk'],
            'user_id' => $user->id,
        ]);

        return $assistant;
    }

    public function updateAssistant($nim, array $data)
    {
        $assistant = $this->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        if (isset($data['nim']) && $data['nim'] !== $assistant->nim) {
            $existing = Assistant::where('nim', $data['nim'])->first();
            if ($existing) {
                throw new \Exception('Assistant with this NIM already exists.');
            }
            $assistant->user->update(['username' => $data['nim']]);
            $assistant->update(['nim' => $data['nim']]);
        }

        $assistant->update([
            'name' => $data['name'],
            'prodi' => $data['prodi'],
            'angkatan' => $data['angkatan'],
            'tahun_masuk' => $data['tahun_masuk'],
            'status' => $data['status'],
        ]);

        if (isset($data['password'])) {
            if ($assistant->user) {
                $assistant->user->update(['password' => bcrypt($data['password'])]);
            }
        }

        return $assistant;
    }

    public function deleteAssistant($nim)
    {
        $assistant = $this->getAssistantByNim($nim);
        $user = $this->getUserByNimIncludeTrashed($nim);

        if (!$assistant) {
            return null;
        }

        $assistant->delete();
        $user->delete();

        return true;
    }

    public function deleteByNims(array $nims)
    {
        $assistants = Assistant::whereIn('nim', $nims)->get();
        $userIds = $assistants->pluck('user_id')->unique();

        Assistant::whereIn('nim', $nims)->delete();
        User::whereIn('id', $userIds)->delete();

        return true;
    }

    public function getAssistantAvailableSchedules($schedule_id)
    {
        $schedule = Schedule::with('assistantSchedules', 'practicum')->where('id', $schedule_id)->first();
        $assistants = $this->getAllAssistants();
        if (!$schedule || !$schedule->practicum) {
            return [];
        }

        $day = $schedule->day;
        $start = $schedule->start_time;
        $end = $schedule->end_time;
        $forProdi = $schedule->practicum->for_prodi;
        $tahunAjar = $schedule->tahun_ajar;
        $jenisSemester = $schedule->jenis_semester;
        $semester = $schedule->practicum->semester;
        $kodePraktikum = $schedule->practicum->kode_praktikum ?? null;

        // Determine minimal year difference based on practicum semester
        $minDiff = 0;
        if (in_array($semester, [1])) {
            $minDiff = 1;
        } elseif (in_array($semester, [2, 3])) {
            $minDiff = 2;
        } elseif (in_array($semester, [4, 5])) {
            $minDiff = 3;
        } elseif (in_array($semester, [6])) {
            $minDiff = 4;
        }

        return $assistants->filter(function ($assistant) use ($day, $start, $end, $forProdi, $tahunAjar, $minDiff, $semester, $kodePraktikum) {
            // Jika kode praktikum 124210241, tidak perlu cek prodi dan angkatan 2023 prodi informatika termasuk true
            if ($kodePraktikum == '124210241') {
                if (
                    strtolower($assistant->prodi) == 'informatika' &&
                    ($tahunAjar - $assistant->angkatan) >= 1
                ) {
                    // Termasuk true, lanjut cek jadwal bentrok
                } else {
                    if (!in_array($semester, [1, 2]) && $assistant->prodi !== $forProdi) {
                        return false;
                    }
                    // Check minimal year difference
                    if (($tahunAjar - $assistant->angkatan) < $minDiff) {
                        return false;
                    }
                }
            } else if ($kodePraktikum == '123210241') {
                if (
                    strtolower($assistant->prodi) == 'sistem informasi' &&
                    ($tahunAjar - $assistant->angkatan) >= 1
                ) {
                    // Termasuk true, lanjut cek jadwal bentrok
                } else {
                    if (!in_array($semester, [1, 2]) && $assistant->prodi !== $forProdi) {
                        return false;
                    }
                    // Check minimal year difference
                    if (($tahunAjar - $assistant->angkatan) < $minDiff) {
                        return false;
                    }
                }
            } else {
                // Jika semester 1 atau 2, tidak perlu cek prodi
                if (!in_array($semester, [1, 2]) && $assistant->prodi !== $forProdi) {
                    return false;
                }
                // Check minimal year difference
                if (($tahunAjar - $assistant->angkatan) < $minDiff) {
                    return false;
                }
            }

            foreach ($assistant->courseSchedules as $courseSchedule) {
                if (
                    $courseSchedule->day === $day &&
                    (
                        ($courseSchedule->start_time <= $start && $courseSchedule->end_time > $start) ||
                        ($courseSchedule->start_time < $end && $courseSchedule->end_time >= $end) ||
                        ($courseSchedule->start_time >= $start && $courseSchedule->end_time <= $end)
                    )
                ) {
                    return false;
                }
            }
            return true;
        })->map(function ($assistant) use ($tahunAjar, $jenisSemester, $schedule) {
            $jumlahKelas = $assistant->assistantSchedules
                ->filter(function ($assistantSchedule) use ($tahunAjar, $jenisSemester) {
                    return $assistantSchedule->schedule &&
                        $assistantSchedule->schedule->tahun_ajar == $tahunAjar &&
                        $assistantSchedule->schedule->jenis_semester == $jenisSemester;
                })
                ->count();
            $assistant->jumlah_kelas = $jumlahKelas;

            // Add preference data
            $praktikumKode = $schedule->practicum->kode_praktikum ?? null;
            $assistant->preference = $assistant->preferences
                ->where('kode_praktikum', $praktikumKode)
                ->isNotEmpty();

            return $assistant;
        })->values();
    }
}
