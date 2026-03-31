<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Routing\Controllers\HasMiddleware;
// use Illuminate\Routing\Controllers\Middleware;
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
        return view('dashboards.role-permission.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('dashboards.role-permission.permissions.create');
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
        return view('dashboards.role-permission.permissions.edit', compact('permission'));
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
