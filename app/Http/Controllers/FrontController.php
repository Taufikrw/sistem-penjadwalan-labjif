<?php

namespace App\Http\Controllers;

use App\Services\FrontService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }
    
    public function dashboard()
    {
        $data = $this->frontService->getDashboardData();

        return view('dashboard', $data);
    }
}