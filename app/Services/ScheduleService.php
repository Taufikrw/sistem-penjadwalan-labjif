<?php

namespace App\Services;

use App\Models\Room;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\RoomRepositoryInterface;

class ScheduleService
{
    protected $practicumRepository;
    protected $roomRepository;

    public function __construct(PracticumRepositoryInterface $practicumRepository, RoomRepositoryInterface $roomRepository)
    {
        $this->practicumRepository = $practicumRepository;
        $this->roomRepository = $roomRepository;
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
}
