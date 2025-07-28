<?php

namespace App\Repositories\Contracts;

interface LaboratoriumRepositoryInterface
{
    public function getAllLaboratoriums($sortBy = 'updated_at', $order = 'desc', $search = '', array $filters = [], $perPage = null);

    public function getLaboratoriumById($id);

    public function storeLab(array $data);

    public function updateLab($id, array $data);

    public function deleteLab($id);

    public function deleteByIds(array $ids);
}