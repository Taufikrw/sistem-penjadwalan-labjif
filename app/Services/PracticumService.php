<?php

namespace App\Services;

use App\Repositories\Contracts\PracticumRepositoryInterface;

class PracticumService
{
    protected $practicumRepository;

    public function __construct(PracticumRepositoryInterface $practicumRepository)
    {
        $this->practicumRepository = $practicumRepository;
    }

    public function getPracticumData($sortBy, $sortOrder, $search, $filters)
    {
        return $this->practicumRepository->getAllPracticums($sortBy, $sortOrder, $search, $filters, 8);
    }
}
