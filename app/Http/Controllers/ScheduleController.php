<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePracticumRequest;
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
        return view('practicum.create');
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

        return view('practicum.create', compact('practicum'));
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
}
