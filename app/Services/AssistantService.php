<?php

namespace App\Services;

use App\Models\CourseSchedule;
use App\Repositories\Contracts\AssistantRepositoryInterface;
use App\Repositories\Contracts\CourseScheduleRepositoryInterface;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class AssistantService
{
    protected $assistantRepository;
    protected $courseScheduleRepository;
    protected $scheduleRepository;
    protected $practicumRepository;

    public function __construct(
        AssistantRepositoryInterface $assistantRepository,
        CourseScheduleRepositoryInterface $courseScheduleRepository,
        ScheduleRepositoryInterface $scheduleRepository,
        PracticumRepositoryInterface $practicumRepository
    ) {
        $this->assistantRepository = $assistantRepository;
        $this->courseScheduleRepository = $courseScheduleRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->practicumRepository = $practicumRepository;
    }

    public function getAssistantsData($sortBy, $sortOrder, $search, $filters)
    {
        return $this->assistantRepository->getAllAssistants([$sortBy], [$sortOrder], $search, $filters, 8);
    }

    public function getAssistantsDetails($nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        return $assistant;
    }

    public function getPreferencesByNim($nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        return $assistant->preferences->pluck('practicum.name')->filter()->values();
    }

    public function getAssistantDetail($nim)
    {
        return $this->assistantRepository->getAssistantByNim($nim);
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

        return compact('assistant', 'schedules');
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
        return $this->courseScheduleRepository->storeCourseSchedule($data);
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

    public function bulkDeleteAssistants(array $nims)
    {
        $this->assistantRepository->deleteByNims($nims);
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
        return $this->scheduleRepository->getHistoryByNim($nim);
    }

    public function getCreatePreferencePage(string $nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        $practicums = $this->practicumRepository->getAllPracticums(filters: ['for_prodi' => $assistant->prodi]);
        $selectedPracticums = [];
        foreach ($assistant->preferences as $preference) {
            $selectedPracticums[] = $this->practicumRepository->getPracticumByKode($preference->kode_praktikum);
        }

        return compact('assistant', 'practicums', 'selectedPracticums');
    }

    public function storePreference(array $data, string $nim)
    {
        $assistant = $this->assistantRepository->getAssistantByNim($nim);

        if (!$assistant) {
            return null;
        }

        // Delete existing preferences
        $this->assistantRepository->deletePreferencesByNim($nim);

        // Store new preferences
        foreach ($data['practicums'] as $kode_praktikum) {
            $this->assistantRepository->storePreference($nim, $kode_praktikum);
        }
    }

    public function getAssistantOverview($filterType = 'status')
    {
        return $this->assistantRepository->getAssistantOverview($filterType);
    }
}
