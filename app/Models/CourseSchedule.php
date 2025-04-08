<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSchedule extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'course',
        'day',
        'start_time',
        'end_time',
        'owner',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, 'owner', 'nim');
    }
}
