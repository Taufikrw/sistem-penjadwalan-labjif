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
        $course = $data['course'];
        $courseEnum = null;
        foreach (\App\Enums\Course::cases() as $enumCase) {
            if ($enumCase->value === $course) {
                $courseEnum = $enumCase;
                break;
            }
        }
        
        $prodi = $courseEnum->prodi();
        $data['name'] = ($prodi === 'Teknik Informatika' ? 'IF-' : 'SI-') . $data['name'];
        
        $this->assistantRepository->storeCourseSchedule($data);

        return $data['owner'];
    }
}