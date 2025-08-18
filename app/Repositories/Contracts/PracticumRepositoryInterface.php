<?php

namespace App\Repositories\Contracts;

interface PracticumRepositoryInterface
{
    public function getAllPracticums($sortBy = ['kode_praktikum', 'name', 'semester_romawi', 'for_prodi'], $sortOrder = 'asc', $search = '', array $filters = [], $perpage = null);

    public function getPracticumByKode($kode_praktikum);

    public function getPracticumByKodeIncludeTrashed($kode_praktikum);

    public function storePracticum(array $data);

    public function updatePracticum($kode_praktikum, array $data);

    public function deletePracticum($kode_praktikum);

    public function deleteByKodePraktikums(array $kode_praktikums);
}