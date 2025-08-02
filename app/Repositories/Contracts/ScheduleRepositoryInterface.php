<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryInterface
{   
    public function getAllSchedules();

    public function getScheduleById($id);

    public function getSchedulesByNim($nim);

    public function createSchedule(array $data);

    public function updateSchedule($id, array $data);

    public function deleteSchedule($id);

    public function getAssistantByScheduleId($scheduleId);

    public function setAssistantSchedule($scheduleId, $nim);

    public function updateAssistantSchedule($scheduleId, $nim);

    public function getEmptyAssistantSchedules();

    public function deleteAssistantsByScheduleId($scheduleId);

    public function getScheduleByDay($day);

    public function getHistoryByNim($nim);

    public function getCourseSchedulesByNim($nim, $sortBy = 'day', $order = 'asc', $search = '', $perPage = null);
    
    public function deleteCourseByIds(array $ids);

    public function getYearScheduleList();

    public function getNewestTahunAjaran();
}