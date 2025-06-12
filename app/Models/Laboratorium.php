<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratorium extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'capacity'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'laboratorium_id', 'id');
    }
}
