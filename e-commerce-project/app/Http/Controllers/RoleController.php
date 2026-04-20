<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view role', only: ['index']),
            new Middleware('permission:create role', only: ['create', 'store', 'addPermission', 'givePermission']),
            new Middleware('permission:update role', only: ['edit', 'update']),
            new Middleware('permission:delete role', only: ['destroy']),
        ];
    }

    public function index()
    {
        $roles = Role::get();
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.roles.index', compact('roles'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.roles.index', compact('roles'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.roles.index', compact('roles'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.roles.index', compact('roles'));
        }
        // return view('dashboards.role-permission.roles.index', compact('roles'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.roles.create',);
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.roles.create',);
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.roles.create',);
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.roles.create',);
        }
        // return view('dashboards.role-permission.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('status', 'Role Created Successfully.');
    }

    public function edit(Role $role)
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.roles.edit', compact('role'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.roles.edit', compact('role'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.roles.edit', compact('role'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.roles.edit', compact('role'));
        }
        // return view('dashboards.role-permission.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('roles.index')->with('status', 'Role Updated Successfully.');
    }

    public function destroy(Role $role)
    {
        $role = Role::findOrFail($role->id);
        $role->delete();
        return redirect()->route('roles.index')->with('status', 'Role Deleted Successfully.');
    }

    public function addPermission(string $id)
    {
        $permissions = Permission::get();
        $role = Role::findOrFail($id);
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return Str::after($permission->name, ' ');
        });
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id', 'permission_id')
            ->all();

        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.roles.add-permission', compact('groupedPermissions','permissions', 'role', 'rolePermissions'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.roles.add-permission', compact('groupedPermissions','permissions', 'role', 'rolePermissions'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.roles.add-permission', compact('groupedPermissions','permissions', 'role', 'rolePermissions'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.roles.add-permission', compact('groupedPermissions','permissions', 'role', 'rolePermissions'));
        }
        // return view('dashboards.role-permission.roles.add-permission', compact('groupedPermissions','permissions', 'role', 'rolePermissions'));
    }

    public function givePermission(Request $request, string $id)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permission);
        return redirect()->back()->with('status', 'Permissions added to role.');
    }
}
