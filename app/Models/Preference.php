<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preference extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'nim',
        'kode_praktikum',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, 'nim', 'nim');
    }

    public function practicum()
    {
        return $this->belongsTo(Practicum::class, 'kode_praktikum', 'kode_praktikum');
    }
}
