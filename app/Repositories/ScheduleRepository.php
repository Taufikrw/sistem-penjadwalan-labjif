<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\AssistantSchedule;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAllSchedules($sortBy = ['day', 'start_time'], $order = 'asc')
    {
        $query = Schedule::with(['practicum', 'laboratorium', 'assistantSchedules']);

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
        return Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])->where('id', $id)->first();
    }

    public function getSchedulesByNim($nim)
    {
        return Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])
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

    public function getScheduleByDay($day)
    {
        return Schedule::with(['practicum', 'laboratorium', 'assistantSchedules'])
            ->where('day', $day)
            ->get();
    }
}