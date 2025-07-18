<?php

namespace App\Http\Controllers;

use App\Services\FrontService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function dashboard()
    {
        if (Auth::user()->role === 'assistant') {
            $data = $this->frontService->getDashboardAssistantData(Auth::user()->username);

            if (!$data) {
                return abort(404, 'Asistant not found');
            }

            return view('dashboard-assistant', $data);
        } elseif (Auth::user()->role === 'admin') {
            return view('dashboard');
        }
    }
}
