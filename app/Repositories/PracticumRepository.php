<?php

namespace App\Repositories;

use App\Models\Practicum;
use App\Repositories\Contracts\PracticumRepositoryInterface;

class PracticumRepository implements PracticumRepositoryInterface
{
    public function getAllPracticums($sortBy = ['kode_praktikum', 'name', 'semester_romawi', 'for_prodi'], $sortOrder = 'asc', $search = '', array $filters = [], $perpage = null)
    {
        $allowedSortColumns = ['kode_praktikum', 'name', 'semester_romawi', 'for_prodi', 'updated_at'];
        $sortBy = array_filter($sortBy, fn($column) => in_array($column, $allowedSortColumns));
        $sortBy = empty($sortBy) ? ['kode_praktikum', 'name', 'semester_romawi', 'for_prodi'] : $sortBy;
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        
        $query = Practicum::with('schedules');
        
        foreach ((array) $sortBy as $column) {
            if ($column === 'semester_romawi') {
                $query->orderBy('semester', $sortOrder);
            } else {
                $query->orderBy($column, $sortOrder);
            }
        }

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($lowerSearch) {
                $q->whereRaw('LOWER(kode_praktikum) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('CAST(semester AS TEXT) LIKE ?', ["%{$lowerSearch}%"])
                    ->orWhereRaw('LOWER(for_prodi) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        foreach ($filters as $key => $value) {
            if ($key === 'semester') {
                if (is_array($value)) {
                    $query->whereIn('semester', $value);
                } else {
                    $query->whereRaw("CAST(semester AS TEXT) LIKE ?", ["%".strtolower($value)."%"]);
                }
            } elseif ($key === 'for_prodi') {
                $query->whereRaw("LOWER(for_prodi) LIKE ?", ["%".strtolower($value)."%"]);
            }
        }

        if ($perpage) {
            return $query->paginate($perpage);
        }

        return $query->get();
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

    public function deleteByKodePraktikums(array $kode_praktikums)
    {
        Practicum::whereIn('kode_praktikum', $kode_praktikums)->delete();

        return true;
    }
}