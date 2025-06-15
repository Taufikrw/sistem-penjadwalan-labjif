<?php

namespace App\Repositories\Contracts;

interface AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'nim', $order = 'asc');
    
    public function getAssistantByNim($nim);

    public function getAssistantByNimIncludeTrashedWithUser($nim);
    
    public function getUserByNimIncludeTrashed($nim);

    public function storeAssistant(array $data);

    public function updateAssistant($nim, array $data);

    public function deleteAssistant($nim);

    public function getAssistantAvailableSchedules($schedule_id);
}