<?php

namespace App\Repositories\Contracts;

interface ScheduleRepositoryInterface
{   
    public function getAllSchedules();

    public function getScheduleById($id);

    public function createSchedule(array $data);

    public function updateSchedule($id, array $data);

    public function deleteSchedule($id);
}