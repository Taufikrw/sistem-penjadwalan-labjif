<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseBulkDeleteRequest;
use App\Http\Requests\StoreCourseScheduleRequest;
use App\Http\Requests\StoreScheduleBulkDeleteRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Services\AssistantService;
use App\Services\GeneticAlgorithmService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    protected $scheduleService;
    protected $assistantService;
    protected $gaService;

    public function __construct(ScheduleService $scheduleService, AssistantService $assistantService, GeneticAlgorithmService $gaService)
    {
        $this->scheduleService = $scheduleService;
        $this->assistantService = $assistantService;
        $this->gaService = $gaService;
    }

    public function index()
    {
        $data = $this->scheduleService->getScheduleCreatePage();
        $dayForm = 'Senin';

        return view('schedule.index', $data, compact('dayForm'));
    }

    public function create(Request $request)
    {
        $tahunAjar = $request->query('tahun_ajar');
        $semester = $request->query('jenis_semester');
        $dayForm = $request->query('day', 'Senin');

        $data = $this->scheduleService->getScheduleCreatePage($semester);

        return view('schedule.form', $data, compact('tahunAjar', 'semester', 'dayForm'));
    }

    public function store(StoreScheduleRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->scheduleService->storeSchedule($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Jadwal Praktikum berhasil dibuat.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat data Jadwal Praktikum: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $tahunAjar = $request->query('tahun_ajar');
        $semester = $request->query('jenis_semester');
        $dayForm = $request->query('day', 'Senin');

        $schedule = $this->scheduleService->getScheduleDetails($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal Praktikum tidak ditemukan',
            ], 404);
        }

        $data = $this->scheduleService->getScheduleCreatePage($semester);

        return view('schedule.form', $data, compact('schedule', 'tahunAjar', 'semester', 'dayForm'));
    }

    public function update(StoreScheduleRequest $request, $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal Praktikum tidak ditemukan',
            ], 404);
        }

        $validated = $request->validated();

        try {
            $this->scheduleService->updateSchedule($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Jadwal Praktikum berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data Jadwal Praktikum: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal Praktikum tidak ditemukan',
            ], 404);
        }

        try {
            $this->scheduleService->deleteSchedule($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Jadwal Praktikum berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus Data Jadwal Praktikum: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkDelete(StoreScheduleBulkDeleteRequest $request)
    {
        $validated = $request->validated();

        try {
            $ids = $validated['ids'];
            $this->scheduleService->bulkDeleteSchedules($ids);

            $count = count($ids);

            return response()->json([
                'status' => 'success',
                'message' => "{$count} data Jadwal Praktikum berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data Jadwal Praktikum: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function todaySchedule()
    {
        try {
            $schedules = $this->scheduleService->getTodaySchedules();
            return response()->json([
                'status' => 'success',
                'data' => $schedules,
                'message' => 'Schedules retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve schedules: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function indexCourse()
    {
        $assistant = $this->assistantService->getAssistantDetail(Auth::user()->username);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }
        return view('course.index', compact('assistant'));
    }

    public function indexCourseSchedule(string $nim)
    {
        $assistant = $this->assistantService->getAssistantDetail($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }
        return view('course.index', compact('assistant'));
    }

    public function courseTable(Request $request, string $nim)
    {
        $sortBy = $request->get('sort_by', 'day');
        $sortOrder = $request->get('sort_order', 'asc');
        $search = $request->get('search', '');

        try {
            $data = $this->scheduleService->getCourseSchedules($nim, $sortBy, $sortOrder, $search);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Assistant not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Course schedules retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve course schedules: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createCourseAssistant()
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }

        return view('course.form', compact('assistant'));
    }

    public function createCourseLaboran(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }

        return view('course.form', compact('assistant'));
    }

    public function storeCourse(StoreCourseScheduleRequest $request)
    {
        $nim = $request->input('owner');
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        $validated = $request->validated();

        try {
            $this->assistantService->createCourseSchedule($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Jadwal Kuliah berhasil dibuat.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat data jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editCourseLaboran(string $nim, string $id)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kuliah tidak ditemukan',
            ], 404);
        }

        return view('course.form', compact('assistant', 'courseItem'));
    }

    public function editCourseAssistant(string $id)
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kuliah tidak ditemukan',
            ], 404);
        }

        return view('course.form', compact('assistant', 'courseItem'));
    }

    public function updateCourse(StoreCourseScheduleRequest $request, string $id)
    {
        $nim = $request->input('owner');
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kuliah tidak ditemukan',
            ], 404);
        }

        $validated = $request->validated();

        try {
            $this->assistantService->updateCourseSchedule($validated, $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Data Jadwal Kuliah berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function finalizeCourse(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        try {
            $data = $this->assistantService->finalizeCourseSchedule($nim);

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Jadwal kuliah Anda sudah tersimpan dan tidak dapat diubah lagi.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memfinalisasi jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyCourseLaboran(string $nim, string $id)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asisten tidak ditemukan',
            ], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kuliah tidak ditemukan',
            ], 404);
        }

        try {
            $this->assistantService->deleteCourseSchedule($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal kuliah berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyCourseAssistant(string $id)
    {
        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal kuliah tidak ditemukan',
            ], 404);
        }

        try {
            $this->assistantService->deleteCourseSchedule($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal kuliah berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkDeleteCourse(StoreCourseBulkDeleteRequest $request)
    {
        $validated = $request->validated();

        try {
            $ids = $validated['ids'];
            $this->scheduleService->bulkDeleteCourses($ids);

            $count = count($ids);

            return response()->json([
                'status' => 'success',
                'message' => "{$count} data jadwal kuliah berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data jadwal kuliah: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function yearScheduleList()
    {
        try {
            $years = $this->scheduleService->getYearScheduleList();
            return response()->json([
                'status' => 'success',
                'data' => $years,
                'message' => 'Daftar tahun jadwal berhasil diambil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar tahun jadwal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $semester = $request->query('semester');
        $tahunAjar = $request->query('tahun_ajar');
        if ($semester === 'genap') {
            $tahunAjaran = ($tahunAjar - 1) . '/' . $tahunAjar;
        } else {
            $tahunAjaran = $tahunAjar . '/' . ($tahunAjar + 1);
        }
        $day = $request->query('day', 'Semua');

        $data = $this->scheduleService->getFilterSchedules($semester);
        $filtersData = [
            'day' => $day,
            'tahun_ajar' => $tahunAjar,
            'jenis_semester' => $semester,
            'practicums' => $data['practicums'],
            'labs' => $data['labs'],
        ];

        return view('schedule.show', compact('semester', 'tahunAjaran', 'tahunAjar', 'day'), ['filters' => $filtersData]);
    }

    public function scheduleTable(Request $request)
    {
        $sortBy = $request->get('sort_by', '');
        $sortOrder = $request->get('sort_order', '');
        $search = $request->get('search', '');

        $filters = [
            'day' => ($request->get('day', '') !== 'Semua') ? $request->get('day', '') : null,
            'practicum_name' => $request->get('practicum_name', ''),
            'laboratorium_name' => $request->get('laboratorium_name', ''),
            'tahun_ajar' => $request->get('tahun_ajar', ''),
            'jenis_semester' => $request->get('jenis_semester', ''),
            'start_time' => $request->get('start_time', ''),
            'end_time' => $request->get('end_time', ''),
            'assistant_count' => $request->get('assistant_count', ''),
            'assistant_nim' => $request->get('assistant_nim', ''),
        ];

        $filters = array_filter($filters, function ($value) {
            return $value !== null && $value !== '';
        });

        try {
            $schedules = $this->scheduleService->getScheduleData($sortBy, $sortOrder, $search, $filters);

            return response()->json([
                'status' => 'success',
                'data' => $schedules,
                'message' => 'Schedules retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve schedules: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function currentScheduleTable(Request $request)
    {
        $schedule = $this->scheduleService->getNewestTahunAjaran();
        $filters = [
            'tahun_ajar' => $schedule ? $schedule->tahun_ajar : null,
            'jenis_semester' => $schedule ? $schedule->jenis_semester : null,
            'assistant_nim' => $request->get('assistant_nim', ''),
        ];

        $filters = array_filter($filters, function ($value) {
            return $value !== null && $value !== '';
        });

        try {
            $schedules = $this->scheduleService->getCurrentScheduleData(filters: $filters);

            return response()->json([
                'status' => 'success',
                'data' => $schedules,
                'message' => 'Schedules retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve schedules: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function indexScheduleAssistant(Request $request)
    {
        $schedule = $this->scheduleService->getNewestTahunAjaran();
        $semester = $schedule ? $schedule->jenis_semester : null;
        $tahunAjar = $schedule ? $schedule->tahun_ajar : null;
        if ($semester === 'genap') {
            $tahunAjaran = ($tahunAjar - 1) . '/' . $tahunAjar;
        } else {
            $tahunAjaran = $tahunAjar . '/' . ($tahunAjar + 1);
        }
        $day = $request->query('day', 'Semua');

        $data = $this->scheduleService->getFilterSchedules($semester);
        $filtersData = [
            'day' => $day,
            'tahun_ajar' => $tahunAjar,
            'jenis_semester' => $semester,
            'practicums' => $data['practicums'],
            'labs' => $data['labs'],
        ];

        return view('schedule.show', compact('semester', 'tahunAjaran', 'tahunAjar', 'day'), ['filters' => $filtersData]);
    }

    public function generateAssistants(Request $request)
    {
        ini_set('max_execution_time', 1800);

        $filters = [
            'tahun_ajar' => $request->input('tahun_ajar'),
            'jenis_semester' => $request->input('jenis_semester')
        ];

        try {
            // Panggil GA service untuk mendapatkan solusi
            $solution = $this->gaService->generateAssistantSchedule(array_filter($filters));

            if (!$solution) {
                throw new \Exception('Algoritma Genetika tidak menghasilkan solusi.');
            }

            // Simpan solusi (baik sempurna maupun parsial)
            $result = $this->saveSolution($solution);

            // Kirim respons berdasarkan hasil penyimpanan
            if ($result['clash_count'] > 0) {
                return response()->json([
                    'status' => 'warning', // Gunakan status 'warning' untuk di-handle frontend
                    'message' => "Sebanyak {$result['saved_count']} jadwal berhasil disimpan. Namun {$result['clash_count']} jadwal dilewati karena bentrok.",
                    'data' => $solution,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal asisten berhasil digenerate dan disimpan tanpa ada bentrokan!',
                'data' => $solution,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan solusi (sempurna atau parsial) yang sudah final ke database.
     * @return array Informasi tentang hasil penyimpanan
     */
    private function saveSolution(array $solution): array
    {
        // Data penugasan yang akan disimpan (bisa sempurna atau parsial)
        $assignmentsToSave = $solution['assignments'];
        $clashingIds = [];

        // Jika ada bentrok, filter penugasan yang akan disimpan
        if ($solution['clashes'] > 0) {
            $clashingIds = $solution['clashing_schedule_ids'];
            $assignmentsToSave = array_filter($assignmentsToSave, function ($assignment) use ($clashingIds) {
                return !in_array($assignment['schedule_id'], $clashingIds);
            });
        }

        DB::transaction(function () use ($assignmentsToSave) {
            // Simpan hanya penugasan yang baru dan tidak bentrok
            foreach ($assignmentsToSave as $assignment) {
                $this->scheduleService->assignAssistantsToSchedule(
                    $assignment['schedule_id'],
                    $assignment['assistant_nims']
                );
            }
        });

        return [
            'saved_count' => count($assignmentsToSave),
            'clash_count' => count($clashingIds)
        ];
    }
}
