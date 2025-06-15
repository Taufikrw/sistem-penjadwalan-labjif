<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryInterface
{   
    public function getAllSchedules();

    public function getScheduleById($id);

    public function createSchedule(array $data);

    public function updateSchedule($id, array $data);

    public function deleteSchedule($id);

    public function getAssistantByScheduleId($scheduleId);

    public function setAssistantSchedule($scheduleId, $nim);

    public function updateAssistantSchedule($scheduleId, $nim);

    public function getEmptyAssistantSchedules();

    public function deleteAssistantsByScheduleId($scheduleId);

    public function getScheduleByDay($day);
}