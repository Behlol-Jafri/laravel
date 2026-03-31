<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $user = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $users = User::create($user);
        if ($users) {
            return redirect()->route('login');
        }
    }
     public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($user)) {
            return redirect()->route('dashboard');
        }
    }
     public function dashboardPage(Request $request)
    {
        
        if (Auth::check()) {
            return view('dashboard');
        }else{
            return redirect()->route('login');
        }
    }

     public function innerPage(Request $request)
    {
        
        if (Auth::check()) {
            return view('inner');
        }else{
            return redirect()->route('login');
        }
    }

    public function logout(){
        Auth::logout();
        return view('login');
    }
}
