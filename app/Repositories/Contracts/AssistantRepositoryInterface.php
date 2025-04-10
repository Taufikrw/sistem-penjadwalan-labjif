<?php

namespace App\Repositories\Contracts;

interface AssistantRepositoryInterface
{
    public function getAllAssistants();
    
    public function getAssistantByNim($nim);

    public function storeCourseSchedule(array $data);
}