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
            new Middleware('permission:view category', only: ['index','show']),
            new Middleware('permission:create category', only: ['create', 'store']),
            new Middleware('permission:update category', only: ['edit', 'update']),
            new Middleware('permission:delete category', only: ['destroy']),
        ];
    }


    public function index()
    {
        $categories = Category::all();
        return view('dashboards.category.categoryData', compact('categories'));
    }

    public function create()
    {
        return view('dashboards.category.addCategory');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);

        $category = Category::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('category.index');
    }

    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('dashboards.category.viewCategory', compact('category'));
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('dashboards.category.updateCategory', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);


        $category->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);


        return redirect()->route('category.index');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index');
    }
}
