<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabBulkDeleteRequest;
use App\Http\Requests\StoreLabRequest;
use App\Services\LaboratoriumService;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    protected $laboratoriumService;

    public function __construct(LaboratoriumService $laboratoriumService)
    {
        $this->laboratoriumService = $laboratoriumService;
    }

    public function index()
    {
        return view('lab.index');
    }

    public function labsTable(Request $request)
    {
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $search = $request->get('search', '');
    
        $filters = [
            'location' => $request->get('location', ''),
        ];
    
        $filters = array_filter($filters);
    
        try {
            $data = $this->laboratoriumService->getLaboratoriumData($sortBy, $sortOrder, $search, $filters);
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Berhasil mengambil data laboratorium.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laboratorium: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return view('lab.form');
    }

    public function store(StoreLabRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->laboratoriumService->createLab($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data laboratorium berhasil dibuat.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat data laboratorium: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $laboratorium = $this->laboratoriumService->getLabDetails($id);
        
        if (!$laboratorium) {
            return response()->view('errors.404', ['message' => 'Laboratorium tidak ditemukan'], 404);
        }

        return view('lab.form', compact('laboratorium'));
    }

    public function update(StoreLabRequest $request, $id)
    {
        if (!$this->laboratoriumService->isLabExists($id)) {
            return response()->view('errors.404', ['message' => 'Laboratorium tidak ditemukan'], 404);
        }

        $validated = $request->validated();

        try {
            $this->laboratoriumService->updateLab($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Data laboratorium berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data laboratorium: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (!$this->laboratoriumService->isLabExists($id)) {
            return abort(404, 'Laboratorium tidak ditemukan');
        }

        $this->laboratoriumService->deleteLab($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Laboratorium deleted successfully.',
        ]);
    }

    public function bulkDelete(StoreLabBulkDeleteRequest $request)
    {
        $validated = $request->validated();

        try {
            $ids = $validated['ids'];
            $this->laboratoriumService->bulkDeleteLabs($ids);

            $count = count($ids);
            
            return response()->json([
                'status' => 'success',
                'message' => "{$count} data laboratorium berhasil dihapus.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data laboratorium: ' . $e->getMessage(),
            ], 500);
        }
    }
}
