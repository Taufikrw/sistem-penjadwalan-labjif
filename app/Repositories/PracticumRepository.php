<?php

namespace App\Repositories;

use App\Models\Practicum;
use App\Repositories\Contracts\PracticumRepositoryInterface;

class PracticumRepository implements PracticumRepositoryInterface
{
    public function getAllPracticums($sortBy = 'kode_praktikum', $sortOrder = 'asc')
    {
        return Practicum::with('schedules')->orderBy($sortBy, $sortOrder)->get();
    }
    
    public function getPracticumByKode($kode_praktikum)
    {
        return Practicum::with('schedules')->where('kode_praktikum', $kode_praktikum)->first();
    }

    public function getPracticumByKodeIncludeTrashed($kode_praktikum)
    {
        return Practicum::with('schedules')->withTrashed()->where('kode_praktikum', $kode_praktikum)->first();
    }

    public function storePracticum(array $data)
    {
        return Practicum::create($data);
    }

    public function updatePracticum($kode_praktikum, array $data)
    {
        $practicum = $this->getPracticumByKode($kode_praktikum);

        $practicum->update($data);
        
        return $practicum;
    }

    public function deletePracticum($kode_praktikum)
    {
        $practicum = Practicum::findOrFail($kode_praktikum);

        $practicum->delete();
        
        return $practicum;
    }
}