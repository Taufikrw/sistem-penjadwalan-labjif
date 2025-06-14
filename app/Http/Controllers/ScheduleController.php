<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePracticumRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\StoreScheduleRequest;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }
    
    public function listPracticums()
    {
        $data = $this->scheduleService->getPracticumList();

        return view('practicum.index', $data);
    }

    public function createPracticum()
    {
        return view('practicum.form');
    }

    public function storePracticum(StorePracticumRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->scheduleService->storePracticum($validated);
            return redirect()->route('practicum.index')->with('success', 'Practicum created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create practicum: ' . $e->getMessage()]);
        }
    }

    public function editPracticum(string $kode_praktikum)
    {
        $practicum = $this->scheduleService->getPracticumDetails($kode_praktikum);

        if (!$practicum) {
            return response()->view('errors.not-found', ['message' => 'Practicum not found'], 404);
        }

        return view('practicum.form', compact('practicum'));
    }

    public function updatePracticum(StorePracticumRequest $request, $kode_praktikum)
    {
        if (!$this->scheduleService->isPracticumExists($kode_praktikum)) {
            return response()->view('errors.not-found', ['message' => 'Practicum not found'], 404);
        }
        
        $validated = $request->validated();

        $this->scheduleService->updatePracticum($kode_praktikum, $validated);

        return redirect()->route('practicum.index')->with('success', 'Practicum updated successfully.');
    }

    public function destroyPracticum($kode_praktikum)
    {
        if (!$this->scheduleService->isPracticumExists($kode_praktikum)) {
            return response()->view('errors.not-found', ['message' => 'Practicum not found'], 404);
        }

        $this->scheduleService->deletePracticum($kode_praktikum);

        return redirect()->route('practicum.index')->with('success', 'Practicum deleted successfully.');
    }

    public function listLaboratoriums()
    {
        $data = $this->scheduleService->getLaboratoriumList();

        return view('lab.index', $data);
    }

    public function createLab()
    {
        return view('lab.form');
    }

    public function storeLab(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->scheduleService->createLab($validated);
            return redirect()->route('lab.index')->with('success', 'Laboratorium created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create lab: ' . $e->getMessage()]);
        }
    }

    public function editLab($id)
    {
        $laboratorium = $this->scheduleService->getLabDetails($id);

        if (!$laboratorium) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        return view('lab.form', compact('laboratorium'));
    }

    public function updateLab(StoreRoomRequest $request, $id)
    {
        if (!$this->scheduleService->isRoomExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        $validated = $request->validated();

        $this->scheduleService->updateLab($id, $validated);

        return redirect()->route('lab.index')->with('success', 'Room updated successfully.');
    }

    public function destroyLab($id)
    {
        if (!$this->scheduleService->isRoomExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        $this->scheduleService->deleteLab($id);

        return redirect()->route('lab.index')->with('success', 'Room deleted successfully.');
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
}
