<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistantSchedule extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'nim',
    ];

    public function assistant()
    {
        return $this->belongsTo(Assistant::class, 'nim', 'nim');
    }

    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'schedule_id', 'id');
    }
}
