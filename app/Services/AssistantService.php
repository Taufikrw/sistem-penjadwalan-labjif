<?php

namespace App\Services;

use App\Models\CourseSchedule;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;

class AssistantService
{
    protected $assistantRepository;
    protected $courseScheduleRepository;

    public function __construct(AssistantRepositoryInterface $assistantRepository, CourseScheduleRepositoryInterface $courseScheduleRepository)
    {
        $this->assistantRepository = $assistantRepository;
        $this->courseScheduleRepository = $courseScheduleRepository;
    }

    public function getAssistantsList()
    {
        $assistants = $this->assistantRepository->getAllAssistants();

        return compact('assistants');
    }

    public function getAssistantsDetails($nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        return $assistant;
    }

    public function getAssistantWithCourse($nim)
    {
        $assistant = $this->getAssistantsDetails($nim);

        if (!$assistant) {
            return null;
        }

        $courses = $this->courseScheduleRepository->getCourseScheduleByNim($nim);

        return compact('assistant', 'courses');
    }

    public function getCourseDetails($id)
    {
        $course = $this->courseScheduleRepository->getCourseScheduleById($id);

        if (!$course) {
            return null;
        }

        return $course;
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
        
        $this->courseScheduleRepository->storeCourseSchedule($data);

        return $data['owner'];
    }

    public function updateCourseSchedule(array $data, string $id)
    {
        $courseSchedule = CourseSchedule::where('id', $id)->first();

        if (!$courseSchedule) {
            return null;
        }

        $this->courseScheduleRepository->updateCourseSchedule($id, $data);
    }

    public function deleteCourseSchedule(string $id)
    {
        $this->courseScheduleRepository->deleteCourseSchedule($id);
    }
}