<?php

namespace App\Services;

use App\Models\Room;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleService
{
    protected $practicumRepository;
    protected $roomRepository;
    protected $scheduleRepository;

    public function __construct(
        PracticumRepositoryInterface $practicumRepository,
        RoomRepositoryInterface $roomRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->practicumRepository = $practicumRepository;
        $this->roomRepository = $roomRepository;
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
        $rooms = $this->roomRepository->getAllRooms();

        return compact('rooms');
    }

    public function getRoomDetails($id)
    {
        $room = $this->roomRepository->getRoomById($id);

        if (empty($room)) {
            return null;
        }

        return $room;
    }

    public function isRoomExists($id)
    {
        return $this->roomRepository->getRoomById($id) !== null;
    }

    public function createRoom(array $data)
    {
        $this->roomRepository->storeRoom($data);
    }

    public function updateRoom($id, array $data)
    {
        $this->roomRepository->updateRoom($id, $data);
    }

    public function deleteRoom($id)
    {
        $this->roomRepository->deleteRoom($id);
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
        $rooms = $this->roomRepository->getAllRooms();

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
