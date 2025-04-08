<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class FrontService
{
    public function getDashboardData()
    {
        $user = Auth::check() ? Auth::user() : null;
        
        return compact('user');
    }
}