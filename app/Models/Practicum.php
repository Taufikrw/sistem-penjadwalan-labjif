<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practicum extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $primaryKey = 'kode_praktikum';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_praktikum',
        'name',
        'for_prodi',
        'semester',
    ];

    protected $appends = ['semester_romawi'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'kode_praktikum', 'kode_praktikum');
    }

    public function preferences()
    {
        return $this->hasMany(Preference::class, 'kode_praktikum', 'kode_praktikum');
    }

    public function getSemesterRomawiAttribute()
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
        ];

        return $romawi[$this->semester] ?? null;
    }
}
