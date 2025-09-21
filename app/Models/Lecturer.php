<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecturer extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'name'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'lecturer_id', 'id');
    }
}
