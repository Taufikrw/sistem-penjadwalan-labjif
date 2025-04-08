<?php

namespace App\Repositories;

use App\Models\Assistant;
use App\Repositories\Contracts\AssistantRepositoryInterface;

class AssistantRepository implements AssistantRepositoryInterface
{
    public function getAllAssistants()
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')->get();
    }

    public function getAssistantByNim($nim)
    {
        return Assistant::with('user', 'courseSchedules', 'assistantSchedules')->where('nim', $nim)->first();
    }
}