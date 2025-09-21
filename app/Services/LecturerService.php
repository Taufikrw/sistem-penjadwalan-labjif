<?php

namespace App\Services;

use App\Repositories\Contracts\LecturerRepositoryInterface;

class LecturerService
{
    protected $lecturerRepository;

    public function __construct(
        LecturerRepositoryInterface $lecturerRepository
    ) {
        $this->lecturerRepository = $lecturerRepository;
    }

    public function getLecturerData($sortBy, $sortOrder, $search)
    {
        return $this->lecturerRepository->getAllLecturers([$sortBy], $sortOrder, $search, 8);
    }

    public function createLecturer(array $data)
    {
        $exist = $this->lecturerRepository->getByNipIncludeTrashed($data['nip']);
        
        if ($exist) {
            if ($exist->trashed()) {
                $exist->restore();

                $this->lecturerRepository->updateLecturer($exist->id, $data);
                return;
            } else {
                throw new \Exception('Lecturer with this NIP already exists.');
            }
        } else {
            return $this->lecturerRepository->storeLecturer($data);
        }
    }

    public function isLecturerExists($id)
    {
        return $this->lecturerRepository->getLecturerById($id) !== null;
    }

    public function updateLecturer($id, array $data)
    {
        $this->lecturerRepository->updateLecturer($id, $data);
    }

    public function deleteLecturer($id)
    {
        $this->lecturerRepository->deleteLecturer($id);
    }

    public function bulkDeleteLecturers(array $ids)
    {
        $this->lecturerRepository->deleteByIds($ids);
    }
}
