<?php

namespace App\Services;

use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class FrontService
{
    protected $scheduleRepository;
    protected $assistantRepository;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        AssistantRepositoryInterface $assistantRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->assistantRepository = $assistantRepository;
    }

    public function getDashboardData()
    {
        $dayName = now()->locale('id')->translatedFormat('l');
        
        $schedules = $this->scheduleRepository->getScheduleByDay($dayName);

        return compact('schedules');
    }

    public function getDashboardAssistantData($nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        return compact('assistant');
    }
}