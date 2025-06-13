<?php

namespace App\Services;

use App\Models\Room;
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

    public function getPracticumList()
    {
        $practicums = $this->practicumRepository->getAllPracticums();

        return compact('practicums');
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

    public function storePracticum(array $data)
    {
        $existing = $this->practicumRepository->getPracticumByKodeIncludeTrashed($data['kode_praktikum']);

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();

                $this->practicumRepository->updatePracticum($data['kode_praktikum'], $data);
            } else {
                throw new \Exception('Practicum with this code already exists.');
            }
        } else {
            $this->practicumRepository->storePracticum($data);
        }
    }

    public function updatePracticum($kode_praktikum, array $data)
    {
        $this->practicumRepository->updatePracticum($kode_praktikum, $data);
    }

    public function deletePracticum($kode_praktikum)
    {
        $this->practicumRepository->deletePracticum($kode_praktikum);
    }

    public function getRoomList()
    {
        $rooms = $this->laboratoriumRepository->getAllLaboratoriums();

        return compact('rooms');
    }

    public function getRoomDetails($id)
    {
        $room = $this->laboratoriumRepository->getLaboratoriumById($id);

        if (empty($room)) {
            return null;
        }

        return $room;
    }

    public function isRoomExists($id)
    {
        return $this->laboratoriumRepository->getLaboratoriumById($id) !== null;
    }

    public function createRoom(array $data)
    {
        $this->laboratoriumRepository->storeLab($data);
    }

    public function updateRoom($id, array $data)
    {
        $this->laboratoriumRepository->updateLab($id, $data);
    }

    public function deleteRoom($id)
    {
        $this->laboratoriumRepository->deleteLab($id);
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
        $rooms = $this->laboratoriumRepository->getAllLaboratoriums();

        return compact('practicums', 'rooms');
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
}
