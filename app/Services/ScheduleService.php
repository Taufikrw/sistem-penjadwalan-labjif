<?php

namespace App\Services;

use App\Repositories\Contracts\LaboratoriumRepositoryInterface;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleService
{
    protected $practicumRepository;
    protected $laboratoriumRepository;
    protected $scheduleRepository;

    public function __construct(
        PracticumRepositoryInterface $practicumRepository,
        LaboratoriumRepositoryInterface $laboratoriumRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->practicumRepository = $practicumRepository;
        $this->laboratoriumRepository = $laboratoriumRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getScheduleList()
    {
        $schedules = $this->scheduleRepository->getAllSchedules();

        return compact('schedules');
    }

    public function getScheduleDetails($id)
    {
        $schedule = $this->scheduleRepository->getScheduleById($id);

        if (empty($schedule)) {
            return null;
        }

        return $schedule;
    }

    public function isScheduleExists($id)
    {
        return $this->scheduleRepository->getScheduleById($id) !== null;
    }

    public function getScheduleCreatePage()
    {
        $practicums = $this->practicumRepository->getAllPracticums();
        $labs = $this->laboratoriumRepository->getAllLaboratoriums();

        return compact('practicums', 'labs');
    }

    public function storeSchedule(array $data)
    {
        $this->scheduleRepository->createSchedule($data);
    }

    public function updateSchedule($id, array $data)
    {
        $this->scheduleRepository->updateSchedule($id, $data);
    }

    public function deleteSchedule($id)
    {
        $this->scheduleRepository->deleteSchedule($id);
    }

    public function bulkDeleteSchedules(array $ids)
    {
        $this->scheduleRepository->deleteScheduleByIds($ids);
    }

    public function getTodaySchedules()
    {
        $dayName = now()->locale('id')->translatedFormat('l');

        return $this->scheduleRepository->getScheduleByDay($dayName, 8);
    }

    public function getCourseSchedules($nim, $sortBy, $sortOrder, $search)
    {
        return $this->scheduleRepository->getCourseSchedulesByNim($nim, $sortBy, $sortOrder, $search, 8);
    }

    public function bulkDeleteCourses(array $ids)
    {
        $this->scheduleRepository->deleteCourseByIds($ids);
    }

    public function getYearScheduleList()
    {
        return $this->scheduleRepository->getYearScheduleList();
    }

    public function getScheduleData($sortBy, $sortOrder, $search, $filters)
    {
        return $this->scheduleRepository->getAllSchedules($sortBy, $sortOrder, $search, $filters, 8);
    }

    public function getNewestTahunAjaran()
    {
        return $this->scheduleRepository->getNewestTahunAjaran();
    }
}
