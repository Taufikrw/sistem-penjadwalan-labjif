<?php

namespace App\Repositories;

use App\Models\Laboratorium;
use App\Repositories\Contracts\LaboratoriumRepositoryInterface;

class LaboratoriumRepository implements LaboratoriumRepositoryInterface
{
    public function getAllLaboratoriums($sortBy = 'name', $order = 'asc')
    {
        return Laboratorium::with('schedules')->orderBy($sortBy, $order)->get();
    }

    public function getLaboratoriumById($id)
    {
        return Laboratorium::with('schedules')->find($id);
    }

    public function storeLab(array $data)
    {
        return Laboratorium::create($data);
    }

    public function updateLab($id, array $data)
    {
        $room = $this->getLaboratoriumById($id);

        if ($room) {
            $room->update($data);
            return $room;
        }
        return null;
    }

    public function deleteLab($id)
    {
        $room = $this->getLaboratoriumById($id);

        if ($room) {
            $room->delete();
            return true;
        }

        return false;
    }
}
