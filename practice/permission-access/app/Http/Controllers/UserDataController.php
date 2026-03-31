<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserDataController extends Controller
{

    // Show all users current user can access
    public function index()
    {
        $authUser = Auth::user();

        if ($authUser->role === 'admin') {
            $users = User::where('role', 'user')->get();
        } else {
            $users = User::with('accessibleUsers')->get();
        }

        return view('user.index', compact('users'));
    }

    // Show one user's profile
    public function show(User $user)
    {
        Gate::authorize('read-user-data', $user);
        return view('user.show', compact('user'));
    }
}
