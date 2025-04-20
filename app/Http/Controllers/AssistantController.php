<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistantRequest;
use App\Http\Requests\StoreAssistantScheduleRequest;
use App\Http\Requests\StoreCourseScheduleRequest;
use App\Http\Requests\UpdateAssistantScheduleRequest;
use App\Services\AssistantService;
use App\Services\ScheduleService;

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
        $data = $this->assistantService->getAssistantsList();

        return view('assistant.index', $data);
    }

    public function show(string $nim)
    {
        $data = $this->assistantService->getAssistantWithCourse($nim);

        if (!$data) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('assistant.show', $data);
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
            return redirect()->route('assistant.show', $course_owner)->with('success', 'Course schedule created successfully');
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
            return redirect()->route('assistant.show', $nim)->with('success', 'Course schedule updated successfully');
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
            return redirect()->route('assistant.show', $nim)->with('success', 'Course schedule deleted successfully');
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

    public function updateAssistant(UpdateAssistantScheduleRequest $request, string $id)
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
}
