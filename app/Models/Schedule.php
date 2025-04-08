<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'kode_praktikum',
        'room_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function practicum()
    {
        return $this->belongsTo(Practicum::class, 'kode_praktikum', 'kode_praktikum');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function assistantSchedules()
    {
        return $this->hasMany(AssistantSchedule::class, 'schedule_id', 'id');
    }
}
