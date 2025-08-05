<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistantBulkDeleteRequest;
use App\Http\Requests\StoreAssistantRequest;
use App\Http\Requests\StoreAssistantScheduleRequest;
use App\Http\Requests\StoreCourseScheduleRequest;
use App\Http\Requests\UpdateAssistantRequest;
use App\Services\AssistantService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssistantController extends Controller
{
    protected $assistantService;
    protected $scheduleService;

    public function __construct(
        AssistantService $assistantService,
        ScheduleService $scheduleService
    ) {
        $this->assistantService = $assistantService;
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        return view('assistant.index');
    }

    public function assistantTable(Request $request)
    {
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $search = $request->get('search', '');

        $filters = [
            'status' => $request->get('status', ''),
            'prodi' => $request->get('prodi', ''),
            'angkatan' => $request->get('angkatan', ''),
            'tahun_masuk' => $request->get('tahun_masuk', ''),
        ];

        $filters = array_filter($filters);

        try {
            $data = $this->assistantService->getAssistantsData($sortBy, $sortOrder, $search, $filters);

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Practicum data retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve practicum data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function assistantDetails(string $nim)
    {
        try {
            $data = $this->assistantService->getAssistantDetail($nim);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Assistant not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Assistant data retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve assistant data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showSchedule(string $nim)
    {
        $data = $this->assistantService->getAssistantWithSchedule($nim);

        if (!$data) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.show-schedule', $data);
    }

    public function create()
    {
        return view('assistant.form');
    }

    public function store(StoreAssistantRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->assistantService->createAssistant($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data asisten berhasil dibuat.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat data asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }

        return view('assistant.form', compact('assistant'));
    }

    public function update(UpdateAssistantRequest $request, string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Asisten tidak ditemukan'], 404);
        }

        $validated = $request->validated();
        $validated['password'] = $request->input('ubah-password', null);

        try {
            $this->assistantService->updateAssistant($validated, $nim);
            return response()->json([
                'status' => 'success',
                'message' => 'Data asisten berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.404', ['message' => 'Assistant not found'], 404);
        }

        try {
            $this->assistantService->deleteAssistant($nim);
            return response()->json([
                'status' => 'success',
                'message' => 'Data asisten berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkDelete(StoreAssistantBulkDeleteRequest $request)
    {
        $validated = $request->validated();

        try {
            $nims = $validated['ids'];
            $this->assistantService->bulkDeleteAssistants($nims);

            $count = count($nims);

            return response()->json([
                'status' => 'success',
                'message' => "{$count} data asisten berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function setAssistant(string $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.404', ['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $data = $this->assistantService->getSetAssistantPage($id);

        return view('schedule.set-assistant', $data);
    }

    public function storeSetAssistant(StoreAssistantScheduleRequest $request, string $id)
    {
        $validated = $request->validated();

        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.404', ['message' => 'Jadwal tidak ditemukan'], 404);
        }

        try {
            $this->assistantService->setAssistantToSchedule($validated, $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Asisten berhasil ditetapkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menetapkan asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function editAssistant(string $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.404', ['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $data = $this->assistantService->getSetAssistantPage($id);

        return view('schedule.set-assistant', $data);
    }

    public function updateAssistant(StoreAssistantScheduleRequest $request, string $id)
    {
        $validated = $request->validated();

        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.404', ['message' => 'Jadwal tidak ditemukan'], 404);
        }

        try {
            $this->assistantService->updateAssistantToSchedule($validated, $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Asisten berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui asisten: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function historyTable(string $nim)
    {
        try {
            $history = $this->assistantService->getAssistantHistory($nim);
            $history->makeHidden(['laboratorium_name', 'practicum_name', 'jam', 'assistant_names', 'laboratorium', 'practicum', 'assistantSchedules']);
            return response()->json([
                'status' => 'success',
                'data' => $history,
                'message' => 'Schedules retrieved successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data riwayat: ' . $e->getMessage()
            ]);
        }
    }
}
