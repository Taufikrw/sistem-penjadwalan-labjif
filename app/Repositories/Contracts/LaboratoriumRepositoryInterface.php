<?php

namespace App\Repositories\Contracts;

interface LaboratoriumRepositoryInterface
{
    public function getAllLaboratoriums($sortBy = 'name', $order = 'asc', $perPage = null);

    public function getLaboratoriumById($id);

    public function storeLab(array $data);

    public function updateLab($id, array $data);

    public function deleteLab($id);
}