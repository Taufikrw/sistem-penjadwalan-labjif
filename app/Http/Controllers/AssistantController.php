<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseScheduleRequest;
use App\Services\AssistantService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    protected $assistantService;

    public function __construct(AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
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

    public function courseCreate(string $nim)
    {
        $assistant = $this->assistantService->getAssistantsDetails($nim);

        if (!$assistant) {
            return response()->view('errors.not-found', ['message' => 'Assistant not found'], 404);
        }

        return view('course.create', compact('assistant'));
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
}
