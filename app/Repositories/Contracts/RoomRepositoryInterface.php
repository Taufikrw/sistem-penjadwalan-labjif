<?php

namespace App\Repositories\Contracts;

interface RoomRepositoryInterface
{
    public function getAllRooms($sortBy = 'name', $order = 'asc');

    public function getRoomById($id);

    public function storeRoom(array $data);

    public function updateRoom($id, array $data);

    public function deleteRoom($id);
}