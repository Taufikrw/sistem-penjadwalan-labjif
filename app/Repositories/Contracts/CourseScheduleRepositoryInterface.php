<?php

namespace App\Repositories\Contracts;

interface CourseScheduleRepositoryInterface
{
    public function getAllCourseSchedules($sortBy = 'day', $order = 'asc');

    public function getCourseScheduleById($id);

    public function getCourseScheduleByNim($nim, $sortBy = 'day', $order = 'asc');

    public function storeCourseSchedule(array $data);

    public function updateCourseSchedule($id, array $data);

    public function deleteCourseSchedule($id);
}