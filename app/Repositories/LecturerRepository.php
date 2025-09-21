<?php

namespace App\Repositories;

use App\Models\Lecturer;
use App\Repositories\Contracts\LecturerRepositoryInterface;

class LecturerRepository implements LecturerRepositoryInterface
{
    public function getAllLecturers($sortBy = ['name', 'nip'], $order = 'asc', $search = '', $perPage = null)
    {
        $allowedSortColumns = ['name', 'nip'];
        $sortBy = array_filter($sortBy, fn($column) => in_array($column, $allowedSortColumns));
        $sortBy = empty($sortBy) ? ['name', 'nip'] : $sortBy;
        $order = in_array(strtolower($order), ['asc', 'desc']) ? strtolower($order) : 'asc';

        $query = Lecturer::with('schedules');
        
        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$lowerSearch}%"])
                ->orWhereRaw('LOWER(nip) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }
        
        if ($sortBy) {
            foreach ($sortBy as $column) {
                $query->orderBy($column, $order);
            }
        }

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getLecturerById($id)
    {
        return Lecturer::with('schedules')->where('id', $id)->first();
    }

    public function getByNipIncludeTrashed($nip)
    {
        return Lecturer::with('schedules')->withTrashed()->where('nip', $nip)->first();
    }

    public function storeLecturer(array $data)
    {
        return Lecturer::create($data);
    }

    public function updateLecturer($id, array $data)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->update($data);
        return $lecturer;
    }

    public function deleteLecturer($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->delete();
        return $lecturer;
    }

    public function deleteByIds(array $ids)
    {
        Lecturer::whereIn('id', $ids)->delete();

        return true;
    }
}