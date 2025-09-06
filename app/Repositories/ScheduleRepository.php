<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\AssistantSchedule;
use App\Models\CourseSchedule;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAllSchedules($sortBy = ['start_time', 'practicum_name'], $order = 'asc', $search = '', array $filters = [], $perPage = null)
    {
        $allowedSortColumns = ['day', 'practicum_name', 'start_time', 'name', 'course', 'jam'];
        $sortBy = array_filter($sortBy, fn($column) => in_array($column, $allowedSortColumns));
        $sortBy = empty($sortBy) ? ['day', 'start_time', 'laboratorium_name'] : $sortBy;
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

        $query = Schedule::with(['assistantSchedules']);

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } elseif ($column === 'practicum_name') {
                $query->orderBy(
                    DB::raw('(SELECT name FROM practicums WHERE practicums.kode_praktikum = schedules.kode_praktikum)'),
                    $order
                );
            } elseif ($column === 'laboratorium_name') {
                $query->orderBy(
                    DB::raw('(SELECT name FROM laboratoriums WHERE laboratoriums.id = schedules.laboratorium_id)'),
                    $order
                );
            } elseif ($column === 'jam') {
                $query->orderBy('start_time', $order);
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
            } elseif (in_array($key, ['tahun_ajar', 'jenis_semester'])) {
                $query->whereRaw("LOWER({$key}) LIKE ?", ["%{$lowerValue}%"]);
            } elseif ($key === 'day') {
                $query->where($key, 'ILIKE', "%{$value}%");
            } elseif ($key === 'start_time') {
                // Filter schedules where start_time >= $value
                $query->where('start_time', '>=', $value);
            } elseif ($key === 'end_time') {
                // Filter schedules where end_time <= $value
                $query->where('end_time', '<=', $value);
            } elseif ($key === 'assistant_count') {
                if ($value !== '' && !is_null($value)) {
                    if ($value == 0) {
                        $query->whereDoesntHave('assistantSchedules');
                    } elseif (is_numeric($value) && $value > 0) {
                        $query->has('assistantSchedules', '=', (int)$value);
                    }
                }
            } elseif ($key === 'assistant_nim') {
                if (!empty($value)) {
                    $query->whereHas('assistantSchedules', function ($q) use ($value) {
                        $q->where('nim', $value);
                    });
                }
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

    public function getSchedulesByNim($nim, $perPage = null)
    {
        $currentYear = date('Y');
        $query = Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])
            ->where('tahun_ajar', $currentYear)
            ->whereHas('assistantSchedules', function ($query) use ($nim) {
                $query->where('nim', $nim);
            });

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
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

    public function deleteScheduleByIds(array $ids)
    {
        Schedule::whereIn('id', $ids)->delete();

        return true;
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

    public function getEmptyAssistantSchedules(array $filters = [])
    {
        $query = Schedule::with(['practicum', 'laboratorium'])
            ->whereDoesntHave('assistantSchedules');

        foreach ($filters as $key => $value) {
            if (in_array($key, ['tahun_ajar', 'jenis_semester'])) {
                $query->where($key, 'ILIKE', "%{$value}%");
            }
        }

        return $query->orderBy('created_at', 'asc')->get();
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

    public function getHistoryByNim($nim, $perPage = null)
    {
        $query = Schedule::selectRaw("
            practicums.name,
            CASE 
                WHEN practicums.semester % 2 = 1 THEN CONCAT('Gasal ', tahun_ajar, '/', (tahun_ajar::int + 1))
                ELSE CONCAT('Genap ', (tahun_ajar::int - 1), '/', tahun_ajar)
            END as tahun_ajar
            ")
            ->join('practicums', 'practicums.kode_praktikum', '=', 'schedules.kode_praktikum')
            ->whereHas('assistantSchedules', function ($query) use ($nim) {
                $query->where('nim', $nim);
            })
            ->distinct();

        if ($perPage) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }

    public function getCourseSchedulesByNim($nim, $sortBy = 'day', $order = 'asc', $search = '', $perPage = null)
    {
        $allowedSortColumns = ['jam', 'class_name', 'course'];
        $sortBy = in_array(strtolower($sortBy), $allowedSortColumns) ? strtolower($sortBy) : 'day';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

        $query = CourseSchedule::where('owner', $nim);

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->where('name', 'ILIKE', "%{$lowerSearch}%")
                    ->orWhere('course', 'ILIKE', "%{$lowerSearch}%")
                    ->orWhere('day', 'ILIKE', "%{$lowerSearch}%") // <--- Corrected line
                    ->orWhereRaw('LOWER(CAST(start_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(end_time AS TEXT)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else if ($column === 'class_name') {
                $query->orderBy('name', $order);
            } elseif ($column === 'jam') {
                $query->orderBy('start_time', $order);
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
        $schedules = Schedule::select('tahun_ajar', 'jenis_semester')
            ->orderBy('tahun_ajar', 'desc')
            ->orderByRaw("CASE WHEN jenis_semester = 'ganjil' THEN 0 ELSE 1 END")
            ->get()
            ->unique(function ($item) {
                return $item->tahun_ajar . '-' . $item->jenis_semester;
            })
            ->values();

        return $schedules->map(function ($item) {
            $tahunAjarFormatted = $item->jenis_semester === 'ganjil'
                ? $item->tahun_ajar . '/' . ((int)$item->tahun_ajar + 1)
                : ((int)$item->tahun_ajar - 1) . '/' . $item->tahun_ajar;

            return [
                'semester' => $item->jenis_semester,
                'tahun_ajaran' => $tahunAjarFormatted,
                'tahun_ajar' => $item->tahun_ajar,
            ];
        });
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

        $ganjil = $newest->where('tahun_ajar', $maxTahunAjar)
            ->where('jenis_semester', 'ganjil')
            ->first();

        if ($ganjil) {
            return $ganjil;
        }

        return $newest->first();
    }
}
