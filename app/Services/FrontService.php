<?php

namespace App\Services;

use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class FrontService
{
    protected $scheduleRepository;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getDashboardData()
    {
        $dayName = now()->locale('id')->translatedFormat('l');
        
        $schedules = $this->scheduleRepository->getScheduleByDay($dayName);

        return compact('schedules');
    }
}