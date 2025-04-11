<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAllSchedules($sortBy = ['day', 'start_time'], $order = 'asc')
    {
        $query = Schedule::with(['practicum', 'room', 'assistantSchedules']);

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 WHEN 'Sunday' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($column, $order);
            }
        }

        return $query->get();
    }

    public function getScheduleById($id)
    {
        return Schedule::with(['practicum', 'room', 'assistantSchedules'])->where('id', $id)->first();
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
}