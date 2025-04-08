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
        'is_odd',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'kode_praktikum', 'kode_praktikum');
    }
}
