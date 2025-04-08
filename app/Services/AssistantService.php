<?php

namespace App\Services;

use App\Models\CourseSchedule;
use App\Repositories\Contracts\AssistantRepositoryInterface;

class AssistantService
{
    protected $assistantRepository;

    public function __construct(AssistantRepositoryInterface $assistantRepository)
    {
        $this->assistantRepository = $assistantRepository;
    }

    public function getAllAssistants()
    {
        $assistants = $this->assistantRepository->getAllAssistants();

        return compact('assistants');
    }

    public function getAssistantByNim($nim)
    {
        return $this->assistantRepository->getAssistantByNim($nim);
    }

    public function createCourseSchedule(array $data)
    {
        CourseSchedule::create([
            'name' => $data['name'],
            'course' => $data['course'],
            'day' => $data['day'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'owner' => $data['owner']
        ]);

        return $data['owner'];
    }
}