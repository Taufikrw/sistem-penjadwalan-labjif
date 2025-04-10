<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        session()->regenerate();
        
        return redirect()->intended('dashboard')->with('success', 'Login successful!');
    }

    public function destroy()
    {
        Auth::logout();

        session()->invalidate();
        
        session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout successful!');
    }
}
