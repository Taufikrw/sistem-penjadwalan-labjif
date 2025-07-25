<?php

namespace App\Repositories;

use App\Models\Laboratorium;
use App\Repositories\Contracts\LaboratoriumRepositoryInterface;

class LaboratoriumRepository implements LaboratoriumRepositoryInterface
{
    public function getAllLaboratoriums($sortBy = 'updated_at', $order = 'desc', $search = '', array $filters = [], $perPage = null)
    {
        $allowedSortColumns = ['name', 'location', 'capacity', 'updated_at'];
        $sortBy = in_array(strtolower($sortBy), $allowedSortColumns) ? strtolower($sortBy) : 'updated_at';
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'desc';

        $query = Laboratorium::with('schedules')->orderBy($sortBy, $order);

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(location) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(CAST(capacity AS TEXT)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ($filters as $key => $value) {
            if (in_array($key, ['location'])) {
                $lowerValue = strtolower($value);
                $query->whereRaw("LOWER({$key}) LIKE ?", ["%{$lowerValue}%"]);
            }
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
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
