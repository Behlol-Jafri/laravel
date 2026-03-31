<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function loginForm(Request $request)
    {
        if (!Auth::check()) {
            return view('login');
        }
        return redirect()->route('dashboard');
    }
    public function signupForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $roles = Role::pluck('name', 'name')->all();
        return view('signup', compact('roles'));
    }
}
