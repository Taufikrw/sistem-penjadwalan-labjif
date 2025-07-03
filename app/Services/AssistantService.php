<?php

namespace App\Services;

use App\Models\CourseSchedule;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AssistantService
{
    protected $assistantRepository;
    protected $courseScheduleRepository;
    protected $scheduleRepository;

    public function __construct(
        AssistantRepositoryInterface $assistantRepository, 
        CourseScheduleRepositoryInterface $courseScheduleRepository,
        ScheduleRepositoryInterface $scheduleRepository
    )
    {
        $this->assistantRepository = $assistantRepository;
        $this->courseScheduleRepository = $courseScheduleRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getAssistantsList($sortBy, $sortOrder)
    {
        $assistants = $this->assistantRepository->getAllAssistants($sortBy, $sortOrder, 8);

        return compact('assistants');
    }

    public function getAssistantsData($sortBy, $sortOrder)
    {
        return $this->assistantRepository->getAllAssistants($sortBy, $sortOrder, 8);
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

    public function getAssistantWithSchedule($nim)
    {
        $assistant = $this->getAssistantsDetails($nim);

        if (!$assistant) {
            return null;
        }

        $schedules = $this->scheduleRepository->getSchedulesByNim($nim);

        $histories = $this->scheduleRepository->getHistoryByNim($nim);

        return compact('assistant', 'schedules', 'histories');
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

    public function createAssistant(array $data)
    {
        $existing = $this->assistantRepository->getAssistantByNimIncludeTrashedWithUser($data['nim']);
        $userexisting = $this->assistantRepository->getUserByNimIncludeTrashed($data['nim']);

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                $userexisting->restore();

                $this->assistantRepository->updateAssistant($data['nim'], $data);
            } else {
                throw new \Exception('Assistant with this NIM already exists.');
            }
        } else {
            $this->assistantRepository->storeAssistant($data);
        }
        
    }

    public function updateAssistant(array $data, string $nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        $this->assistantRepository->updateAssistant($nim, $data);
    }

    public function deleteAssistant(string $nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        $this->assistantRepository->deleteAssistant($nim);
    }

    public function getSetAssistantPage(string $id)
    {
        $schedule = $this->scheduleRepository->getScheduleById($id);

        $assistants = $this->assistantRepository->getAssistantAvailableSchedules($id);

        $assistantInSchedule = $this->scheduleRepository->getAssistantByScheduleId($id);

        $selectedAssistants = [];
        foreach ($assistantInSchedule as $assistant) {
            $selectedAssistants[] = $this->assistantRepository->getAssistantByNim($assistant->nim);
        }

        return compact('schedule', 'assistants', 'selectedAssistants');
    }

    public function setAssistantToSchedule(array $data, string $id)
    {
        $schedule = $this->scheduleRepository->getScheduleById($id);

        if (!$schedule) {
            return null;
        }

        foreach ($data['assistants'] as $nim) {
            $this->scheduleRepository->setAssistantSchedule($id, $nim);
        }
    }

    public function updateAssistantToSchedule(array $data, string $id)
    {
        $schedule = $this->scheduleRepository->getScheduleById($id);

        if (!$schedule) {
            return null;
        }

        // Delete all existing assistants for the schedule
        $this->scheduleRepository->deleteAssistantsByScheduleId($id);

        // Add new assistants to the schedule
        foreach ($data['assistants'] as $nim) {
            $this->scheduleRepository->setAssistantSchedule($id, $nim);
        }
    }

    public function getAssistantHistory(string $nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        $histories = $this->scheduleRepository->getHistoryByNim($nim);

        return compact('assistant', 'histories');
    }
}