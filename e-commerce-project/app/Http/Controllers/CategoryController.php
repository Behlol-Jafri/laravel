<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view category', only: ['index', 'show']),
            new Middleware('permission:create category', only: ['create', 'store']),
            new Middleware('permission:update category', only: ['edit', 'update']),
            new Middleware('permission:delete category', only: ['destroy']),
        ];
    }


    public function index()
    {
        $categories = Category::all();
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.category.categoryData', compact('categories'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.category.categoryData', compact('categories'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.category.categoryData', compact('categories'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.category.categoryData', compact('categories'));
        }
        // return view('dashboards.category.categoryData', compact('categories'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.category.addCategory');
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.category.addCategory');
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.category.addCategory');
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.category.addCategory');
        }
        // return view('dashboards.category.addCategory');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);

        $category = Category::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('category.index')->with('status', 'Category Add Successfully.');
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.category.viewCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.category.viewCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.category.viewCategory', compact('category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.category.viewCategory', compact('category'));
        }
        // return view('dashboards.category.viewCategory', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.category.updateCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.category.updateCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.category.updateCategory', compact('category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.category.updateCategory', compact('category'));
        }
        // return view('dashboards.category.updateCategory', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);


        $category->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);


        return redirect()->route('category.index')->with('status', 'Category Updated Successfully.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('status', 'Category Deleted Successfully.');
    }
}
