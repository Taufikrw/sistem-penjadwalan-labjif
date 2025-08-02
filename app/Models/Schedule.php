<?php

namespace App\Models;

use App\Enums\Day;
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
        'laboratorium_id',
        'dosen',
        'tahun_ajar',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'day' => Day::class,
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    protected $appends = ['laboratorium_name', 'practicum_name', 'jam', 'assistant_names'];

    public function practicum()
    {
        return $this->belongsTo(Practicum::class, 'kode_praktikum', 'kode_praktikum');
    }

    public function laboratorium()
    {
        return $this->belongsTo(Laboratorium::class, 'laboratorium_id', 'id');
    }

    public function assistantSchedules()
    {
        return $this->hasMany(AssistantSchedule::class, 'schedule_id', 'id');
    }

    public function getAssistantNamesAttribute()
    {
        if ($this->assistantSchedules->isEmpty()) {
            return 'Tidak ada asisten';
        }

        return $this->assistantSchedules->map(function ($assistantSchedule) {
            return $assistantSchedule->assistant->name ?? 'Asisten tidak ditemukan';
        })->implode(', ');
    }

    public function getLaboratoriumNameAttribute()
    {
        return $this->laboratorium ? $this->laboratorium->name : 'Laboratorium tidak ditemukan';
    }

    public function getPracticumNameAttribute()
    {
        return $this->practicum ? $this->practicum->name : 'Praktikum tidak ditemukan';
    }

    public function getJamAttribute()
    {
        return $this->start_time->format('H:i A') . ' - ' . $this->end_time->format('H:i A');
    }
}
