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
        $schedule = Schedule::with('assistantSchedules')->where('id', $schedule_id)->first();
        $assistant = $this->getAllAssistants();
        if (!$schedule) {
            return [];
        }

        $day = $schedule->day;
        $start = $schedule->start_time;
        $end = $schedule->end_time;
        $forProdi = $schedule->practicum->for_prodi;
        $academicYear = $schedule->tahun_ajar;

        return $assistant->filter(function ($assistant) use ($day, $start, $end, $forProdi, $academicYear) {
            if ($assistant->prodi !== $forProdi) {
                return false;
            }

            foreach ($assistant->courseSchedules as $courseSchedule) {
                if (
                    $courseSchedule->tahun_ajar === $academicYear &&
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
        })->values();
    }
}
