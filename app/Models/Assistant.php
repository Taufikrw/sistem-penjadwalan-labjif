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
        'foto',
        'nomor_telp',
    ];

    protected $appends = ['prodi_angkatan', 'final'];

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

    public function preferences()
    {
        return $this->hasMany(Preference::class, 'nim', 'nim');
    }

    public function getProdiAngkatanAttribute()
    {
        $prodiCode = '';
        if ($this->prodi === 'Informatika') {
            $prodiCode = 'IF';
        } elseif ($this->prodi === 'Sistem Informasi') {
            $prodiCode = 'SI';
        } else {
            $prodiCode = $this->prodi;
        }
        return $prodiCode . '-' . substr($this->angkatan, -2);
    }

    public function getFinalAttribute()
    {
        return $this->courseSchedules()->count() > 0 &&
            $this->courseSchedules()->where('is_final', false)->count() === 0;
    }
}
