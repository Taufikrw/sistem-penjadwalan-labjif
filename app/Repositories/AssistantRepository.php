<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Repositories\Contracts\AssistantRepositoryInterface;

class AssistantRepository implements AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'nim', $order = 'asc')
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')
            ->orderBy($sortBy, $order)
            ->get();
    }

    public function getAssistantByNim($nim)
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')->where('nim', $nim)->first();
    }
}