<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePracticumRequest;
use App\Services\PracticumService;
use Illuminate\Http\Request;

class PracticumController extends Controller
{
    protected $practicumService;

    public function __construct(PracticumService $practicumService)
    {
        $this->practicumService = $practicumService;
    }

    public function index()
    {
        return view('practicum.index');
    }

    public function practicumsTable(Request $request)
    {
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $search = $request->get('search', '');

        $filters = [
            'kode_praktikum' => $request->get('kode_praktikum', ''),
            'name' => $request->get('name', ''),
            'semester' => $request->get('semester', ''),
            'for_prodi' => $request->get('for_prodi', ''),
        ];

        try {
            $data = $this->practicumService->getPracticumData($sortBy, $sortOrder, $search, $filters);
            return response()->json([
                'status' => 'success',
                'data' => $data,
                'message' => 'Data praktikum berhasil diambil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data praktikum: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return view('practicum.form')->render();
    }

    public function store(StorePracticumRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->practicumService->storePracticum($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Practicum created successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create practicum: ' . $e->getMessage(),
            ], 500);
        }
    }
}
