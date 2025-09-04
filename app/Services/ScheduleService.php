<?php

namespace App\Services;

use App\Repositories\Contracts\LaboratoriumRepositoryInterface;
use App\Repositories\Contracts\PracticumRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;

class ScheduleService
{
    protected $practicumRepository;
    protected $laboratoriumRepository;
    protected $scheduleRepository;

    public function __construct(
        PracticumRepositoryInterface $practicumRepository,
        LaboratoriumRepositoryInterface $laboratoriumRepository,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->practicumRepository = $practicumRepository;
        $this->laboratoriumRepository = $laboratoriumRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getScheduleList()
    {
        $schedules = $this->scheduleRepository->getAllSchedules();

        return compact('schedules');
    }

    public function getScheduleDetails($id)
    {
        $schedule = $this->scheduleRepository->getScheduleById($id);

        if (empty($schedule)) {
            return null;
        }

        return $schedule;
    }

    public function isScheduleExists($id)
    {
        return $this->scheduleRepository->getScheduleById($id) !== null;
    }

    public function getScheduleCreatePage(string $semester = '')
    {
        $semesterFilters = [];
        if (strtolower($semester) === 'genap') {
            $semesterFilters = [2, 4, 6];
        } else if (strtolower($semester) === 'ganjil') {
            $semesterFilters = [1, 3, 5];
        } else {
            $semesterFilters = [1, 2, 3, 4, 5, 6];
        }

        $practicums = $this->practicumRepository->getAllPracticums(
            $sortBy = ['name'],
            $sortOrder = 'asc',
            $search = '',
            $filters = ['semester' => $semesterFilters]
        );
        $labs = $this->laboratoriumRepository->getAllLaboratoriums();

        return compact('practicums', 'labs');
    }

    public function storeSchedule(array $data)
    {
        $this->scheduleRepository->createSchedule($data);
    }

    public function updateSchedule($id, array $data)
    {
        $this->scheduleRepository->updateSchedule($id, $data);
    }

    public function deleteSchedule($id)
    {
        $this->scheduleRepository->deleteSchedule($id);
    }

    public function bulkDeleteSchedules(array $ids)
    {
        $this->scheduleRepository->deleteScheduleByIds($ids);
    }

    public function getTodaySchedules()
    {
        $dayName = now()->locale('id')->translatedFormat('l');

        return $this->scheduleRepository->getAllSchedules(sortBy: ['start_time', 'practicum_name', 'name'], filters: ['day' => $dayName], perPage: 6);
    }

    public function getCourseSchedules($nim, $sortBy, $sortOrder, $search)
    {
        return $this->scheduleRepository->getCourseSchedulesByNim($nim, $sortBy, $sortOrder, $search, 8);
    }

    public function bulkDeleteCourses(array $ids)
    {
        $this->scheduleRepository->deleteCourseByIds($ids);
    }

    public function getYearScheduleList()
    {
        return $this->scheduleRepository->getYearScheduleList();
    }

    public function getScheduleData($sortBy, $sortOrder, $search, $filters)
    {
        return $this->scheduleRepository->getAllSchedules(sortBy: [$sortBy], order: $sortOrder, search: $search, filters: $filters, perPage: 8);
    }

    public function getNewestTahunAjaran()
    {
        return $this->scheduleRepository->getNewestTahunAjaran();
    }

    public function clearAssistantAssignments(array $scheduleIds): void
    {
        foreach ($scheduleIds as $id) {
            $this->scheduleRepository->deleteAssistantsByScheduleId($id);
        }
    }

    public function assignAssistantsToSchedule(string $scheduleId, array $assistantNims): void
    {
        foreach ($assistantNims as $nim) {
            $this->scheduleRepository->setAssistantSchedule($scheduleId, $nim);
        }
    }

    public function getFilterSchedules(string $semester = '')
    {
        $semesterFilters = [];
        if (strtolower($semester) === 'genap') {
            $semesterFilters = [2, 4, 6];
        } else if (strtolower($semester) === 'ganjil') {
            $semesterFilters = [1, 3, 5];
        } else {
            $semesterFilters = [1, 2, 3, 4, 5, 6];
        }

        $practicums = $this->practicumRepository->getAllPracticums(
            $sortBy = ['name'],
            $sortOrder = 'asc',
            $search = '',
            $filters = ['semester' => $semesterFilters]
        );
        $labs = $this->laboratoriumRepository->getAllLaboratoriums();

        return compact('practicums', 'labs');
    }
}
