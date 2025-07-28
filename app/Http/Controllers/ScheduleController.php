<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePracticumRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
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
}
