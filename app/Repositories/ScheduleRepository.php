<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\AssistantSchedule;
use App\Models\CourseSchedule;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAllSchedules($sortBy = 'start_time', $order = 'asc', $search = '', array $filters = [], $perPage = null)
    {
        $allowedSortColumns = ['day', 'start_time', 'name', 'course'];
        $sortBy = in_array(strtolower($sortBy), $allowedSortColumns) ? strtolower($sortBy) : 'start_time';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

        $query = Schedule::with(['assistantSchedules']);

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($column, $order);
            }
        }

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(schedules.name) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereHas('laboratorium', function ($labQuery) use ($lowerSearch) {
                        $labQuery->whereRaw('LOWER(laboratoriums.name) LIKE ?', ["%{$lowerSearch}%"]);
                    })
                    ->orWhereHas('practicum', function ($pracQuery) use ($lowerSearch) {
                        $pracQuery->whereRaw('LOWER(practicums.name) LIKE ?', ["%{$lowerSearch}%"]);
                    })
                    ->orWhereRaw('LOWER(dosen) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereHas('assistantSchedules.assistant', function ($asstQuery) use ($lowerSearch) {
                        $asstQuery->whereRaw('LOWER(assistants.name) LIKE ?', ["%{$lowerSearch}%"]);
                    })
                    ->orWhereRaw('LOWER(CAST(start_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(end_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ($filters as $key => $value) {
            $lowerValue = strtolower($value);
            if ($key === 'practicum_name') {
                $query->whereHas('practicum', function ($q) use ($lowerValue) {
                    $q->whereRaw('LOWER(practicums.name) LIKE ?', ["%{$lowerValue}%"]);
                });
            } elseif ($key === 'laboratorium_name') {
                $query->whereHas('laboratorium', function ($q) use ($lowerValue) {
                    $q->whereRaw('LOWER(laboratoriums.name) LIKE ?', ["%{$lowerValue}%"]);
                });
            } elseif (in_array($key, ['day', 'tahun_ajar', 'jenis_semester', 'start_time'])) {
                $query->whereRaw("LOWER({$key}) LIKE ?", ["%{$lowerValue}%"]);
            }
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getScheduleById($id)
    {
        return Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])->where('id', $id)->first();
    }

    public function getSchedulesByNim($nim)
    {
        $currentYear = date('Y');
        return Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])
            ->where('tahun_ajar', $currentYear)
            ->whereHas('assistantSchedules', function ($query) use ($nim) {
                $query->where('nim', $nim);
            })->get();
    }

    public function createSchedule(array $data)
    {
        return Schedule::create($data);
    }

    public function updateSchedule($id, array $data)
    {
        $schedule = $this->getScheduleById($id);

        if ($schedule) {
            $schedule->update($data);
            return $schedule;
        }

        return null;
    }

    public function deleteSchedule($id)
    {
        $schedule = $this->getScheduleById($id);

        if ($schedule) {
            $schedule->delete();
            return true;
        }

        return false;
    }

    public function getAssistantByScheduleId($scheduleId)
    {
        return AssistantSchedule::with('assistant')->where('schedule_id', $scheduleId)->get();
    }

    public function setAssistantSchedule($scheduleId, $nim)
    {
        $schedule = $this->getScheduleById($scheduleId);
        $assistant = Assistant::where('nim', $nim)->first();

        if (!$schedule || !$assistant) {
            return false;
        }

        return AssistantSchedule::create([
            'schedule_id' => $scheduleId,
            'nim' => $nim,
        ]);
    }

    public function updateAssistantSchedule($id, $nim)
    {
        $assistantSchedule = AssistantSchedule::where('id', $id)->first();
        $assistant = Assistant::where('nim', $nim)->first();

        if (!$assistantSchedule || !$assistant) {
            return false;
        }

        return $assistantSchedule->update([
            'schedule_id' => $assistantSchedule->schedule_id,
            'nim' => $nim,
        ]);
    }

    public function getEmptyAssistantSchedules()
    {
        return Schedule::with('practicum', 'room')
            ->whereNotIn('id', function ($query) {
                $query->select('schedule_id')->from('assistant_schedules');
            })
            ->get();
    }

    public function deleteAssistantsByScheduleId($scheduleId)
    {
        return AssistantSchedule::where('schedule_id', $scheduleId)->forceDelete();
    }

    public function getScheduleByDay($day, $perPage = null)
    {
        $day = "Senin";
        $query = Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])
            ->where('day', $day);

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getHistoryByNim($nim)
    {
        return Schedule::selectRaw("DISTINCT practicums.name, CASE WHEN practicums.semester % 2 = 1 THEN CONCAT(tahun_ajar, '/', (tahun_ajar::int + 1)) ELSE CONCAT((tahun_ajar::int - 1), '/', tahun_ajar) END as tahun_ajar")
            ->join('practicums', 'practicums.kode_praktikum', '=', 'schedules.kode_praktikum')
            ->whereHas('assistantSchedules', function ($query) use ($nim) {
                $query->where('nim', $nim);
            })
            ->get();
    }

    public function getCourseSchedulesByNim($nim, $sortBy = 'day', $order = 'asc', $search = '', $perPage = null)
    {
        $allowedSortColumns = ['start_time', 'name', 'course'];
        $sortBy = in_array(strtolower($sortBy), $allowedSortColumns) ? strtolower($sortBy) : 'day';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

        $query = CourseSchedule::where('owner', $nim);

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(course AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(day) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(start_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(end_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($column, $order);
            }
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function deleteCourseByIds(array $ids)
    {
        CourseSchedule::whereIn('id', $ids)->delete();

        return true;
    }

    public function getYearScheduleList()
    {
        return Schedule::selectRaw("DISTINCT jenis_semester, tahun_ajar as tahun_ajar_asli, CASE WHEN jenis_semester = 'ganjil' THEN CONCAT(tahun_ajar, '/', (tahun_ajar::int + 1)) ELSE CONCAT((tahun_ajar::int - 1), '/', tahun_ajar) END as tahun_ajar")
            ->orderBy('tahun_ajar', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'semester' => $item->jenis_semester,
                    'tahun_ajaran' => $item->tahun_ajar,
                    'tahun_ajar' => $item->tahun_ajar_asli,
                ];
            })
            ->unique(function ($item) {
                return $item['semester'] . '-' . $item['tahun_ajar'];
            })
            ->values();
    }

    public function getNewestTahunAjaran()
    {
        $newest = Schedule::select('tahun_ajar', 'jenis_semester')
            ->orderBy('tahun_ajar', 'desc')
            ->get();

        if ($newest->isEmpty()) {
            return null;
        }

        $maxTahunAjar = $newest->first()->tahun_ajar;

        $genap = $newest->where('tahun_ajar', $maxTahunAjar)
            ->where('jenis_semester', 'genap')
            ->first();

        if ($genap) {
            return $genap;
        }

        return $newest->first();
    }
}
