<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAllRooms($sortBy = 'name', $order = 'asc')
    {
        return Room::with('schedules')->orderBy($sortBy, $order)->get();
    }

    public function getRoomById($id)
    {
        return Room::with('schedules')->find($id);
    }

    public function storeRoom(array $data)
    {
        return Room::create($data);
    }

    public function updateRoom($id, array $data)
    {
        $room = $this->getRoomById($id);

        if ($room) {
            $room->update($data);
            return $room;
        }
        return null;
    }

    public function deleteRoom($id)
    {
        $room = $this->getRoomById($id);

        if ($room) {
            $room->delete();
            return true;
        }

        return false;
    }
}
