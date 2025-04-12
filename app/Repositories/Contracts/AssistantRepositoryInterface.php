<?php

namespace App\Repositories\Contracts;

interface AssistantRepositoryInterface
{
    public function getAllAssistants($sortBy = 'nim', $order = 'asc');
    
    public function getAssistantByNim($nim);

    public function storeAssistant(array $data);
}