<?php

namespace App\Repositories\Contracts;

interface AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'updated_at', $order = 'desc', $search = '', array $filters = [], $perPage = null);

    public function getAssistantByNim($nim);

    public function getAssistantByNimIncludeTrashedWithUser($nim);
    
    public function getUserByNimIncludeTrashed($nim);

    public function storeAssistant(array $data);

    public function updateAssistant($nim, array $data);

    public function deleteAssistant($nim);

    public function deleteByNims(array $nims);

    public function getAssistantAvailableSchedules($schedule_id);
    
    public function deletePreferencesByNim($nim);

    public function storePreference($nim, $kodePraktikum);
}