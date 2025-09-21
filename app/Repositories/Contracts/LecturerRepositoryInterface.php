<?php

namespace App\Repositories\Contracts;

interface LecturerRepositoryInterface
{
    public function getAllLecturers($sortBy = ['name', 'nip'], $order = 'asc', $search = '', $perPage = null);

    public function getLecturerById($id);
    
    public function getByNipIncludeTrashed($nip);

    public function storeLecturer(array $data);

    public function updateLecturer($id, array $data);

    public function deleteLecturer($id);

    public function deleteByIds(array $ids);
}