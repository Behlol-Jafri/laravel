<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view user', only: ['usersData', 'viewUser']),
            new Middleware('permission:create user', only: ['addUserForm', 'addUser']),
            new Middleware('permission:update user', only: ['updateShowUser', 'updateUser']),
            new Middleware('permission:delete user', only: ['deleteUser']),
        ];
    }


    public function signup(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|min:3|max:15',
            'secondName' => 'required|string|min:3|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'dob' => 'required|date',
            'phoneNumber' => 'required|',
            'role' => 'required',
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'secondName' => $request->secondName,
            'email' => $request->email,
            'password' => $request->password,
            'dob' => $request->dob,
            'phoneNumber' => $request->phoneNumber,
        ]);
        $user->assignRole($request->role);

        return redirect()->route('loginForm');
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);


        if (Auth::attempt($user)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('loginForm');
        }
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('loginForm');
        }
        return view('dashboards.dashboardLayout');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('loginForm');
    }

    public function addUserForm()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('dashboards.user.addUser', compact('roles'));
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|min:3|max:15',
            'secondName' => 'required|string|min:3|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'dob' => 'required|date',
            'phoneNumber' => 'required|',
            'role' => 'required',
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'secondName' => $request->secondName,
            'email' => $request->email,
            'password' => $request->password,
            'dob' => $request->dob,
            'phoneNumber' => $request->phoneNumber,
        ]);
        $user->assignRole($request->role);

        return redirect()->route('users')->with('status', 'User Add Successfully.');
    }

    public function usersData()
    {
        $users = User::all();
        return view('dashboards.user.usersData', compact('users'));
    }

    public function viewUser($id)
    {
        $user = User::findOrFail($id);
        return view('dashboards.user.viewUser', compact('user'));
    }

    public function updateShowUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        return view('dashboards.user.updateUser', compact('user', 'roles'));
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'firstName' => 'required|string|min:3|max:15',
            'secondName' => 'required|string|min:3|max:15',
            'email' => 'required|email',
            'dob' => 'required|date',
            'phoneNumber' => 'required|',
            'role' => 'required',
        ]);


        $user->update([
            'firstName' => $request->firstName,
            'secondName' => $request->secondName,
            'email' => $request->email,
            'dob' => $request->dob,
            'phoneNumber' => $request->phoneNumber,
        ]);
        $user->assignRole($request->role);


        return redirect()->route('users')->with('status', 'User Updated Successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users')->with('status', 'User Deleted Successfully.');
    }

    public function addPermission(string $id)
    {
        $permissions = Permission::get();
        $user = User::findOrFail($id);
        $role = Role::where('name', $user->getRoleNames()->first())->first();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::after($permission->name, ' ');
        });
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id', 'permission_id')
            ->all();
        $userPermissions = DB::table('model_has_permissions')
            ->where('model_id', $user->id)
            ->pluck('permission_id', 'permission_id')
            ->all();
        return view('dashboards.role-permission.users.add-permission', compact('groupedPermissions','permissions', 'user', 'rolePermissions', 'userPermissions'));
    }

    public function givePermission(Request $request, string $id)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $user = User::findOrFail($id);
        $user->syncPermissions($request->permission);
        return redirect()->back()->with('status', 'Permissions added to user.');
    }

    public function userData()
    {
        // $users = User::whereDoesntHave('roles', function ($q) {
        //     $q->where('name', 'Super Admin');
        // })->get();
        $users = User::all();
        return view('dashboards.role-permission.users.index', compact('users'));
    }
}
