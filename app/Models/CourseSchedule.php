<?php

namespace App\Models;

use App\Enums\Course;
use App\Enums\Day;
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
        'tahun_ajar',
        'is_final'
    ];

    protected $casts = [
        'course' => Course::class,
        'day' => Day::class,
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, 'owner', 'nim');
    }
}
