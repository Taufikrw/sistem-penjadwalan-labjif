<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistantRequest;
use App\Http\Requests\StoreAssistantScheduleRequest;
use App\Http\Requests\StoreCourseScheduleRequest;
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
        $data = $this->assistantService->getAssistantsList('nim', 'asc');
        
        return view('assistant.index', $data);
    }

    public function assistantTable(Request $request)
    {
        $sortBy = $request->get('sort_by', 'nim');
        $sortOrder = $request->get('sort_order', 'asc');
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

    public function show(string $nim)
    {
        $data = $this->assistantService->getAssistantWithCourse($nim);

        if (!$data) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.show', $data);
    }

    public function courseSchedulesData(Request $request, string $nim)
    {
        $sortBy = $request->get('sort_by', 'day');
        $sortOrder = $request->get('sort_order', 'asc');

        try {
            $data = $this->scheduleService->getCourseSchedules($nim, $sortBy, $sortOrder);

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
            return redirect()->route('assistant.index')->with('success', 'Assistant created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create assistant: ' . $e->getMessage());
        }
    }

    public function edit(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.form', compact('assistant'));
    }

    public function update(StoreAssistantRequest $request, string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $validated = $request->validated();

        try {
            $this->assistantService->updateAssistant($validated, $nim);
            return redirect()->route('assistant.index', $nim)->with('success', 'Assistant updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update assistant: ' . $e->getMessage());
        }
    }

    public function destroy(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        try {
            $this->assistantService->deleteAssistant($nim);
            return redirect()->route('assistant.index')->with('success', 'Assistant deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete assistant: ' . $e->getMessage());
        }
    }

    public function courseCreate(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('course.form', compact('assistant'));
    }

    public function courseStore(StoreCourseScheduleRequest $request, string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $validated = $request->validated();
        $validated['owner'] = $nim;

        try {
            $course_owner = $this->assistantService->createCourseSchedule($validated);
            return redirect()->route('assistant.showCourse', $course_owner)->with('success', 'Jadwal perkuliahan berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create course schedule: ' . $e->getMessage());
        }
    }

    public function courseEdit(string $nim, string $id)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        return view('course.form', compact('assistant', 'courseItem'));
    }

    public function courseUpdate(StoreCourseScheduleRequest $request, string $nim, string $id)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        $validated = $request->validated();
        $validated['owner'] = $nim;

        try {
            $this->assistantService->updateCourseSchedule($validated, $id);
            return redirect()->route('assistant.showCourse', $nim)->with('success', 'Jadwal perkuliahan berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update course schedule: ' . $e->getMessage());
        }
    }

    public function courseDestroy(string $nim, string $id)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        try {
            $this->assistantService->deleteCourseSchedule($id);
            return redirect()->route('assistant.showCourse', $nim)->with('success', 'Jadwal perkuliahan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete course schedule: ' . $e->getMessage());
        }
    }

    public function setAssistant(string $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        $data = $this->assistantService->getSetAssistantPage($id);

        return view('schedule.set-assistant', $data);
    }
    
    public function storeSetAssistant(StoreAssistantScheduleRequest $request, string $id)
    {
        $validated = $request->validated();
        
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        try {
            $this->assistantService->setAssistantToSchedule($validated, $id);
            return redirect()->route('schedule.index')->with('success', 'Assistant set successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set assistant: ' . $e->getMessage());
        }
    }

    public function editAssistant(string $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        $data = $this->assistantService->getSetAssistantPage($id);

        return view('schedule.set-assistant', $data);
    }

    public function updateAssistant(StoreAssistantScheduleRequest $request, string $id)
    {
        $validated = $request->validated();

        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        try {
            $this->assistantService->updateAssistantToSchedule($validated, $id);
            return redirect()->route('schedule.index')->with('success', 'Assistant updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update assistant: ' . $e->getMessage());
        }
    }

    public function courseSchedules()
    {
        $nim = Auth::user()->username;
        $data = $this->assistantService->getAssistantWithCourse($nim);

        if (!$data) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.show', $data);
    }

    public function createCourseSchedule()
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('course.form', compact('assistant'));
    }

    public function storeCourseSchedule(StoreCourseScheduleRequest $request)
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $validated = $request->validated();
        $validated['owner'] = $nim;

        try {
            $this->assistantService->createCourseSchedule($validated);
            return redirect()->route('course.index')->with('success', 'Jadwal perkuliahan berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create course schedule: ' . $e->getMessage());
        }
    }

    public function editCourseSchedule(string $id)
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        return view('course.form', compact('assistant', 'courseItem'));
    }

    public function updateCourseSchedule(StoreCourseScheduleRequest $request, string $id)
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        $validated = $request->validated();
        $validated['owner'] = $nim;

        try {
            $this->assistantService->updateCourseSchedule($validated, $id);
            return redirect()->route('course.index')->with('success', 'Jadwal perkuliahan berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update course schedule: ' . $e->getMessage());
        }
    }

    public function destroyCourseSchedule(string $id)
    {
        $nim = Auth::user()->username;
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        $courseItem = $this->assistantService->getCourseDetails($id);

        if (!$courseItem) {
            return response()->view('errors.not-found', ['message' => 'Course schedule not found'], 404);
        }

        try {
            $this->assistantService->deleteCourseSchedule($id);
            return redirect()->route('course.index')->with('success', 'Jadwal perkuliahan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete course schedule: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $nim = Auth::user()->username;
        $data = $this->assistantService->getAssistantHistory($nim);

        if (!$data) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.history', $data);
    }
}
