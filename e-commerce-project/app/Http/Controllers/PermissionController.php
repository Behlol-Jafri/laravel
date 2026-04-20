<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller 
// implements HasMiddleware
{

    //   public static function middleware(): array
    // {
    //     return [
    //         new Middleware('permission:view permission', only: ['index']),
    //         new Middleware('permission:create permission', only: ['create', 'store']),
    //         new Middleware('permission:update permission', only: ['edit', 'update']),
    //         new Middleware('permission:delete permission', only: ['destroy']),
    //     ];
    // }


    public function index()
    {
        $permissions = Permission::get();
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.permissions.index', compact('permissions'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.permissions.index', compact('permissions'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.permissions.index', compact('permissions'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.permissions.index', compact('permissions'));
        }
        // return view('dashboards.role-permission.permissions.index', compact('permissions'));
    }

    public function create()
    {
         if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.permissions.create');
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.permissions.create');
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.permissions.create');
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.permissions.create');
        }
        // return view('dashboards.role-permission.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('status', 'Permission Created Successfully.');
    }

    public function edit(Permission $permission)
    {
         if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.role-permission.permissions.edit', compact('permission'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.role-permission.permissions.edit', compact('permission'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.role-permission.permissions.edit', compact('permission'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.role-permission.permissions.edit', compact('permission'));
        }
        // return view('dashboards.role-permission.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('permissions.index')->with('status', 'Permission Updated Successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission = Permission::findOrFail($permission->id);
        $permission->delete();
        return redirect()->route('permissions.index')->with('status', 'Permission Deleted Successfully.');
    }
}
