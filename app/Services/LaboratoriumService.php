<?php

namespace App\Services;

use App\Repositories\Contracts\LaboratoriumRepositoryInterface;

class LaboratoriumService
{
    protected $laboratoriumRepository;

    public function __construct(
        LaboratoriumRepositoryInterface $laboratoriumRepository
    ) {
        $this->laboratoriumRepository = $laboratoriumRepository;
    }

    public function getLaboratoriumData($sortBy, $sortOrder, $search, $filters)
    {
        return $this->laboratoriumRepository->getAllLaboratoriums($sortBy, $sortOrder, $search, $filters, 8);
    }
    
    public function createLab(array $data)
    {
        $this->laboratoriumRepository->storeLab($data);
    }

    public function getLabDetails($id)
    {
        $lab = $this->laboratoriumRepository->getLaboratoriumById($id);

        if (empty($lab)) {
            return null;
        }

        return $lab;
    }

    public function isLabExists($id)
    {
        return $this->laboratoriumRepository->getLaboratoriumById($id) !== null;
    }

    public function updateLab($id, array $data)
    {
        $this->laboratoriumRepository->updateLab($id, $data);
    }
}
