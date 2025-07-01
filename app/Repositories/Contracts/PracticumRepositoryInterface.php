<?php

namespace App\Repositories\Contracts;

interface PracticumRepositoryInterface
{
    public function getAllPracticums($sortBy = 'kode_praktikum', $sortOrder = 'asc', $perpage = null);

    public function getPracticumByKode($kode_praktikum);

    public function getPracticumByKodeIncludeTrashed($kode_praktikum);

    public function storePracticum(array $data);

    public function updatePracticum($kode_praktikum, array $data);

    public function deletePracticum($kode_praktikum);
}