<?php

namespace App\Http\Controllers;

use App\Models\AccessControl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{

    public function manageAccess()
    {
        $granted_users = User::where('role','!=','Super Admin')->get();
        $target_users = User::where('role','!=','Super Admin')->get();

        return view('dashboards.manageAccess', compact('granted_users', 'target_users'));
    }

    public function grant(Request $request)
    {
        AccessControl::create([
            'granted_by' => Auth::user()->id,
            'granted_to' => $request->user_id,
            'target_user' => $request->target_user
        ]);

        return back()->with('success', 'Access Granted');
    }

    public function revoke($id)
    {
        AccessControl::findOrFail($id)->delete();
        return back()->with('success', 'Access Removed');
    }
}
