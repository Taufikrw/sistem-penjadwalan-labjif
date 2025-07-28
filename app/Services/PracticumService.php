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

    public function storePracticum(array $data)
    {
        $existing = $this->practicumRepository->getPracticumByKodeIncludeTrashed($data['kode_praktikum']);

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();

                $this->practicumRepository->updatePracticum($data['kode_praktikum'], $data);
            } else {
                throw new \Exception('Practicum with this code already exists.');
            }
        } else {
            $this->practicumRepository->storePracticum($data);
        }
    }

    public function getPracticumDetails($kode_praktikum)
    {
        $practicum = $this->practicumRepository->getPracticumByKode($kode_praktikum);

        if (empty($practicum)) {
            return null;
        }

        return $practicum;
    }

    public function isPracticumExists($kode_praktikum)
    {
        return $this->practicumRepository->getPracticumByKode($kode_praktikum) !== null;
    }

    public function updatePracticum($kode_praktikum, array $data)
    {
        $this->practicumRepository->updatePracticum($kode_praktikum, $data);
    }
}
