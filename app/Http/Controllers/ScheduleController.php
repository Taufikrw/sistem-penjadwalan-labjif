<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseScheduleRequest;
use App\Http\Requests\StorePracticumRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Services\AssistantService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    protected $scheduleService;
    protected $assistantService;

    public function __construct(ScheduleService $scheduleService, AssistantService $assistantService)
    {
        $this->scheduleService = $scheduleService;
        $this->assistantService = $assistantService;
    }

    public function index()
    {
        $data = $this->scheduleService->getScheduleList();

        return view('schedule.index', $data);
    }

    public function create()
    {
        $data = $this->scheduleService->getScheduleCreatePage();

        return view('schedule.form', $data);
    }

    public function store(StoreScheduleRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->scheduleService->storeSchedule($validated);
            return redirect()->route('schedule.index')->with('success', 'Schedule created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create schedule: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $schedule = $this->scheduleService->getScheduleDetails($id);

        if (!$schedule) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        $data = $this->scheduleService->getScheduleCreatePage($schedule);

        return view('schedule.form', $data, compact('schedule'));
    }

    public function update(StoreScheduleRequest $request, $id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        $validated = $request->validated();

        $this->scheduleService->updateSchedule($id, $validated);

        return redirect()->route('schedule.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy($id)
    {
        if (!$this->scheduleService->isScheduleExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Schedule not found'], 404);
        }

        $this->scheduleService->deleteSchedule($id);

        return redirect()->route('schedule.index')->with('success', 'Schedule deleted successfully.');
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
        return view('course.index');
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
            return response()->view('errors.not-found', ['message' => 'Asisten tidak ditemukan'], 404);
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
}
