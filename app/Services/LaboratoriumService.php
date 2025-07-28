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
}
