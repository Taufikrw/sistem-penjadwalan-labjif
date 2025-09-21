<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLecturerBulkDeleteRequest;
use App\Http\Requests\StoreLecturerRequest;
use App\Services\LecturerService;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    protected $lecturerService;

    public function __construct(LecturerService $lecturerService)
    {
        $this->lecturerService = $lecturerService;
    }
    
    public function index()
    {
        return view('lecturer.index');
    }
    
    public function lecturersTable(Request $request)
    {
        $sortBy = $request->get('sort_by', '');
        $sortOrder = $request->get('sort_order', '');
        $search = $request->get('search', '');
    
    
        try {
            $data = $this->lecturerService->getLecturerData($sortBy, $sortOrder, $search);
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Berhasil mengambil data dosen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data dosen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return view('lecturer.form');
    }

    public function store(StoreLecturerRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->lecturerService->createLecturer($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Dosen berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $lecturer = $this->lecturerService->getLecturerData('name', 'asc', '')->firstWhere('id', $id);

        if (!$lecturer) {
            return redirect()->route('lecturer.index')->with('error', 'Dosen tidak ditemukan.');
        }

        return view('lecturer.form', compact('lecturer'));
    }

    public function update(StoreLecturerRequest $request, $id)
    {
        if (!$this->lecturerService->isLecturerExists($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen tidak ditemukan.'
            ], 404);
        }
        
        $validated = $request->validated();

        try {
            $this->lecturerService->updateLecturer($id, $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Dosen berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (!$this->lecturerService->isLecturerExists($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dosen tidak ditemukan.'
            ], 404);
        }

        try {
            $this->lecturerService->deleteLecturer($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Dosen berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(StoreLecturerBulkDeleteRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->lecturerService->bulkDeleteLecturers($validated['ids']);

            return response()->json([
                'status' => 'success',
                'message' => count($validated['ids']) . ' dosen berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus dosen: ' . $e->getMessage()
            ], 500);
        }
    }
}
