<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Models\CourseSchedule;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;

class CourseScheduleRepository implements CourseScheduleRepositoryInterface
{
    public function getAllCourseSchedules($sortBy = 'day', $order = 'asc')
    {
        return CourseSchedule::with('assistant')->orderBy($sortBy, $order)->get();
    }

    public function getCourseScheduleById($id)
    {
        return CourseSchedule::with('assistant')->where('id', $id)->first();
    }

    public function getCourseScheduleByNim($nim, $sortBy = ['day', 'start_time'], $order = 'asc')
    {
        $query = CourseSchedule::with('assistant')->where('owner', $nim);

        foreach ((array) $sortBy as $column) {
            if ($column === 'day') {
                $query->orderByRaw("CASE day WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 WHEN 'Sunday' THEN 7 ELSE 8 END " . ($order === 'desc' ? 'DESC' : 'ASC'));
            } else {
                $query->orderBy($column, $order);
            }
        }

        return $query->get();
    }

    public function storeCourseSchedule(array $data)
    {
        return CourseSchedule::create($data);
    }

    public function updateCourseSchedule($id, array $data)
    {
        $courseSchedule = $this->getCourseScheduleById($id);

        if ($courseSchedule) {
            $courseSchedule->update($data);
            return $courseSchedule;
        }

        return null;
    }

    public function finalizeCourseSchedule(string $nim)
    {
        return Assistant::where('nim', $nim)->update([
            'is_final' => true
        ]);
    }

    public function deleteCourseSchedule($id)
    {
        $courseSchedule = $this->getCourseScheduleById($id);

        if ($courseSchedule) {
            $courseSchedule->delete();
            return true;
        }

        return false;
    }
}
