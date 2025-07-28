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

    public function getPracticumList($sortBy, $sortOrder)
    {
        return $this->practicumRepository->getAllPracticums($sortBy, $sortOrder, 8);
    }

    public function getPracticumDetails($kode_praktikum)
    {
        $practicum = $this->practicumRepository->getPracticumByKode($kode_praktikum);

        if (empty($practicum)) {
            return null;
        }

        return $practicum;
    }

    public function isPracticumExists($kode_praktikum)
    {
        return $this->practicumRepository->getPracticumByKode($kode_praktikum) !== null;
    }

    public function updatePracticum($kode_praktikum, array $data)
    {
        $this->practicumRepository->updatePracticum($kode_praktikum, $data);
    }

    public function deletePracticum($kode_praktikum)
    {
        $this->practicumRepository->deletePracticum($kode_praktikum);
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

    public function getTodaySchedules()
    {
        $dayName = now()->locale('id')->translatedFormat('l');

        return $this->scheduleRepository->getScheduleByDay($dayName, 8);
    }

    public function getCourseSchedules($nim, $sortBy, $sortOrder)
    {
        return $this->scheduleRepository->getCourseSchedulesByNim($nim, $sortBy, $sortOrder, 8);
    }
}
