<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePracticumRequest;
use App\Http\Requests\StoreRoomRequest;
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

    public function listRooms()
    {
        $data = $this->scheduleService->getRoomList();

        return view('room.index', $data);
    }

    public function createRoom()
    {
        return view('room.form');
    }

    public function storeRoom(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->scheduleService->createRoom($validated);
            return redirect()->route('room.index')->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create room: ' . $e->getMessage()]);
        }
    }

    public function editRoom($id)
    {
        $room = $this->scheduleService->getRoomDetails($id);

        if (!$room) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        return view('room.form', compact('room'));
    }

    public function updateRoom(StoreRoomRequest $request, $id)
    {
        if (!$this->scheduleService->isRoomExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        $validated = $request->validated();

        $this->scheduleService->updateRoom($id, $validated);

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    public function destroyRoom($id)
    {
        if (!$this->scheduleService->isRoomExists($id)) {
            return response()->view('errors.not-found', ['message' => 'Room not found'], 404);
        }

        $this->scheduleService->deleteRoom($id);

        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }
}
