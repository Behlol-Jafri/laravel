<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
   

    public function index()
    {
        $users = User::where('role', 'user')->get();

        // Build permission map: [user_id][target_user_id] = true/false
        $permissions = [];
        foreach ($users as $user) {
            foreach ($users as $target) {
                if ($user->id === $target->id) continue;
                $permissions[$user->id][$target->id] = UserPermission::where('user_id', $user->id)
                    ->where('target_user_id', $target->id)
                    ->where('can_read', true)
                    ->exists();
            }
        }

        return view('admin.permissions', compact('users', 'permissions'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'target_user_id' => 'required|exists:users,id',
            'can_read'       => 'required|boolean',
        ]);

        UserPermission::updateOrCreate(
            [
                'user_id'        => $request->user_id,
                'target_user_id' => $request->target_user_id,
            ],
            [
                'can_read'   => $request->can_read,
                'granted_by' => Auth::user()->id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->can_read ? 'Access granted!' : 'Access revoked!',
            'type'    => $request->can_read ? 'success' : 'danger',
        ]);
    }
}
