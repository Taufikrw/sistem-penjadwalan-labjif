<?php

namespace App\Http\Controllers;

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
}
