<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assistant extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'name',
        'prodi',
        'angkatan',
        'tahun_masuk',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function courseSchedules()
    {
        return $this->hasMany(CourseSchedule::class, 'owner', 'nim');
    }

    public function assistantSchedules()
    {
        return $this->hasMany(AssistantSchedule::class, 'nim', 'nim');
    }
}
